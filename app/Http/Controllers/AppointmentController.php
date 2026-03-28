<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicStaff;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\User;
use App\Notifications\AppointmentBooked;
use App\Notifications\AppointmentCreated;
use App\Notifications\AppointmentStatusUpdated;
use App\Services\IprogSmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AppointmentController extends Controller {
  public function index(Request $request) {
    $appointments = Appointment::with(['patient', 'doctor.user:id,username'])->get();
    if ($request->expectsJson()) {
      $a = $appointments->map(function ($appt) {
        $arr = $appt->toArray();

        $arr['for_patient'] = $appt->patient_pid
            ? trim(($appt->patient?->first_name ?? '') . ' ' . ($appt->patient?->last_name ?? ''))
            : ($appt->guest_name ?? '—');

        return $arr;
      });

      return response()->json($a);
    }
    return view('appointments.index');
  }

  public function show(int $id) {
    $appointment = Appointment::with(['patient', 'doctor.user:id,username'])->find($id);

    if (!$appointment) {
      return response()->json(['message' => 'Appointment not found'], 404);
    }

    return response()->json($appointment);
  }

  public function byPid(string $pid) {
    $appointments = Appointment::with(['patient', 'doctor.user:id,username'])
      ->where('patient_pid', $pid)
      ->get();

    if ($appointments->isEmpty()) {
      return response()->json([]);
    }

    return response()->json($appointments);
  }

  public function store(Request $request) {
    $isGuest = $request->boolean('is_guest');

    $rules = [
      'appointment_type' => ['required', 'string'],
      'appointment_date' => ['required', 'date_format:Y-m-d'],
      'appointment_time' => ['required', 'date_format:H:i:s'],
      'status'           => ['required', 'string'],
      'attended_by'      => ['nullable', 'integer', 'exists:doctors,user_id'],
    ];

    if ($isGuest) {
      $rules['guest_name']    = ['required', 'string', 'max:255'];
      $rules['guest_age']     = ['required', 'integer', 'min:1', 'max:150'];
      $rules['guest_sex']     = ['required', 'in:male,female'];
      $rules['guest_contact'] = ['nullable', 'string', 'max:20'];
    } else {
      $rules['patient_pid'] = ['required', 'exists:patient_records,pid'];
    }

    $validated = $request->validate($rules);

    $allowedTimes = config('appointments.allowed_times');
    if (!in_array($request->appointment_time, $allowedTimes, true)) {
      return response()->json(['message' => 'Invalid appointment time slot.'], 422);
    }

    $capacity = (int) config('appointments.slot_capacity');
    $countStatuses = config('appointments.count_statuses');

    // Count existing bookings for that slot (excluding cancelled)
    $booked = Appointment::query()
      ->where('appointment_date', $request->appointment_date)
      ->where('appointment_time', $request->appointment_time)
      ->whereIn('status', $countStatuses)
      ->count();

    if ($booked >= $capacity) {
      return response()->json(['message' => 'That slot is already full.'], 409);
    }

    $appointmentData = [
      'attended_by'      => $request->attended_by,
      'appointment_type' => $request->appointment_type,
      'appointment_date' => $request->appointment_date,
      'appointment_time' => $request->appointment_time,
      'status'           => $validated['status'],
    ];

    if ($isGuest) {
      $appointmentData['guest_name']    = $request->guest_name;
      $appointmentData['guest_age']     = $request->guest_age;
      $appointmentData['guest_sex']     = $request->guest_sex;
      $appointmentData['guest_contact'] = $request->guest_contact;
    } else {
      $appointmentData['patient_pid'] = $request->patient_pid;
    }

    $appointment = Appointment::create($appointmentData);

    $staffUsers = User::whereIn('role', [1, 2])->get();
    Notification::send($staffUsers, new AppointmentCreated($appointment));

    // Notify the registered patient whose appointment was booked
    $record  = $appointment->patient_pid ? PatientRecord::where('pid', $appointment->patient_pid)->first() : null;
    $patient = $record ? Patient::where('record_id', $record->id)->first() : null;
    $user    = $patient ? User::find($patient->user_id) : null;
    $user?->notify(new AppointmentBooked($appointment));

    // SMS notifications
    $sms  = app(IprogSmsService::class);
    $time = Carbon::parse($appointment->appointment_time)->format('h:i A');

    // SMS → patient (registered or guest)
    $contactNumber = $record?->contact_number ?? $appointment->guest_contact;
    if ($contactNumber) {
      $sms->send($contactNumber, "HealthWeb: Your {$appointment->appointment_type} appointment on {$appointment->appointment_date} at {$time} has been booked.");
    }

    // SMS → staff
    foreach ($staffUsers as $staffUser) {
      $staff = ClinicStaff::where('user_id', $staffUser->id)->first();
      if ($staff?->ContactNumber) {
        $sms->send($staff->ContactNumber, "HealthWeb: New {$appointment->appointment_type} appointment booked for {$appointment->appointment_date} at {$time}.");
      }
    }

    return response()->json($appointment, 201);
  }

  public function update(Request $request, int $id) {
    $appointment = Appointment::find($id);

    if (!$appointment) {
      return response()->json(['message' => 'Appointment not found'], 404);
    }

    $validated = $request->validate([
      'appointment_type' => ['required', 'string'],
      'status' => ['required', 'in:pending,approved,completed,cancelled'],
      'attended_by' => ['nullable', 'integer', 'exists:doctors,user_id'],
    ]);

    $oldStatus = $appointment->status;
    $appointment->update($validated);

    if ($oldStatus !== $appointment->status) {
      $record  = PatientRecord::where('pid', $appointment->patient_pid)->first();
      $patient = $record ? Patient::where('record_id', $record->id)->first() : null;
      $user    = $patient ? User::find($patient->user_id) : null;
      $user?->notify(new AppointmentStatusUpdated($appointment));

      // SMS → patient on approval (registered or guest)
      if ($appointment->status === 'approved') {
        $contactNumber = $record?->contact_number ?? $appointment->guest_contact;
        if ($contactNumber) {
          $time = Carbon::parse($appointment->appointment_time)->format('h:i A');
          app(IprogSmsService::class)->send(
            $contactNumber,
            "HealthWeb: Your {$appointment->appointment_type} appointment on {$appointment->appointment_date} at {$time} has been approved. Please arrive on time."
          );
        }
      }
      // SMS → patient on cancellation (registered or guest)
        if ($appointment->status === 'cancelled') {
          $contactNumber = $record?->contact_number ?? $appointment->guest_contact;
          if ($contactNumber) {
            $time = Carbon::parse($appointment->appointment_time)->format('h:i A');
            app(IprogSmsService::class)->send(
              $contactNumber,
              "HealthWeb: Your {$appointment->appointment_type} appointment on {$appointment->appointment_date} at {$time} has been cancelled. Please check the clinic’s available days and reschedule your appointment at a convenient time. Please contact the clinic if you have questions."
            );
          }
        }
    }

    return response()->json([
      'message' => 'Appointment updated successfully',
      'data' => $appointment,
    ]);
  }

  public function destroy(int $id) {
  $appointment = Appointment::find($id);

  if (!$appointment) {
    return response()->json(['message' => 'Appointment not found'], 404);
  }

  // If already cancelled, avoid duplicate SMS
  if ($appointment->status === 'cancelled') {
    return response()->json([
      'message' => 'Appointment is already cancelled',
      'data' => $appointment
    ]);
  }

  // Set status to cancelled instead of deleting
  $appointment->update(['status' => 'cancelled']);

  // SMS → patient (registered or guest)
  $record        = $appointment->patient_pid
      ? PatientRecord::where('pid', $appointment->patient_pid)->first()
      : null;

  $contactNumber = $record?->contact_number ?? $appointment->guest_contact;

  if ($contactNumber) {
    $time = Carbon::parse($appointment->appointment_time)->format('h:i A');
    app(IprogSmsService::class)->send(
      $contactNumber,
      "HealthWeb: Your {$appointment->appointment_type} appointment on {$appointment->appointment_date} at {$time} has been cancelled. Please check the clinic’s available days and reschedule your appointment at a convenient time. Please contact the clinic if you have questions."
    );
  }

  return response()->json([
    'message' => 'Appointment cancelled successfully',
    'data' => $appointment
  ]);
}
  // public function destroy(int $id) {
  //   $appointment = Appointment::find($id);

  //   if (!$appointment) {
  //     return response()->json(['message' => 'Appointment not found'], 404);
  //   }

  //   // SMS → patient before deleting (registered or guest)
  //   $record        = $appointment->patient_pid ? PatientRecord::where('pid', $appointment->patient_pid)->first() : null;
  //   $contactNumber = $record?->contact_number ?? $appointment->guest_contact;
  //   if ($contactNumber) {
  //     $time = Carbon::parse($appointment->appointment_time)->format('h:i A');
  //     app(IprogSmsService::class)->send(
  //       $contactNumber,
  //       "HealthWeb: Your {$appointment->appointment_type} appointment on {$appointment->appointment_date} at {$time} has been cancelled. Please check the clinic’s available days and reschedule your appointment at a convenient time. Please contact the clinic if you have questions."
  //     );
  //   }

  //   $appointment->delete();

  //   return response()->json([
  //     'message' => 'Appointment cancelled successfully',
  //   ]);
  // }

  public function availableSlots(Request $request) {
    $request->validate([
      'date' => ['required', 'date_format:Y-m-d'],
    ]);

    $date = Carbon::createFromFormat('Y-m-d', $request->date);

    $allowedTimes = config('appointments.allowed_times');
    $capacity = (int) config('appointments.slot_capacity');
    $countStatuses = config('appointments.count_statuses');

    // counts per time for that date (excluding cancelled)
    $counts = DB::table('appointments')
      ->select('appointment_time', DB::raw('COUNT(*) as booked'))
      ->where('appointment_date', $date->toDateString())
      ->whereIn('status', $countStatuses)
      ->groupBy('appointment_time')
      ->pluck('booked', 'appointment_time'); // [time => booked]

    $slots = collect($allowedTimes)->map(function ($time) use ($counts, $capacity, $date) {
      $booked = (int) ($counts[$time] ?? 0);
      $remaining = max(0, $capacity - $booked);

      return [
        'date' => $date->toDateString(),
        'time' => $time, // "08:00:00"
        'label' => Carbon::createFromFormat('H:i:s', $time)->format('h:i A'),
        'booked' => $booked,
        'capacity' => $capacity,
        'remaining' => $remaining,
        'is_full' => $remaining === 0,
      ];
    });

    return response()->json([
      'date' => $date->toDateString(),
      'capacity' => $capacity,
      'slots' => $slots,
    ]);
  }

  public function queue(Request $request) {
    $date = $request->query('date', today()->toDateString());

    $appointments = Appointment::with(['patient', 'doctor.user:id,username'])
      ->where('appointment_date', $date)
      ->whereIn('status', ['pending', 'approved'])
      ->orderBy('appointment_time')
      ->get()
      ->groupBy('appointment_time');

    $slots = config('appointments.allowed_times');

    return view('appointments.queue.index', compact('appointments', 'slots', 'date'));
  }
}
