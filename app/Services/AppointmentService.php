<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\User;
use App\Notifications\AppointmentCreated;
use App\Notifications\AppointmentStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AppointmentService {
  public function __construct(
    protected SlotService $slotService,
    protected AuditLogService $auditLogService,
  ) {}

  public function create(array $validated): Appointment {
    // expects: patient_pid, appointment_type, appointment_date (Y-m-d), appointment_time (H:i:s), status?, attended_by?
    $date = $validated['appointment_date'];
    $time = $validated['appointment_time'];

    $this->slotService->assertValidSlot($date, $time);

    return DB::transaction(function () use ($validated) {
      $appt = Appointment::create([
        'patient_pid'      => $validated['patient_pid'],
        'appointment_type' => $validated['appointment_type'],
        'appointment_date' => $validated['appointment_date'],
        'appointment_time' => $validated['appointment_time'],
        'attended_by'      => $validated['attended_by'] ?? null,
        // force initial status
        'status' => 'pending',
      ]);

      $this->auditLogService->log('appointment_created', [
        'appointment_id' => $appt->id,
        'patient_pid'    => $appt->patient_pid,
      ]);

      // Notify all staff of the new appointment
      $staffUsers = User::whereIn('role', [1, 2])->get();
      Notification::send($staffUsers, new AppointmentCreated($appt));

      return $appt;
    });
  }

  public function update(Appointment $appt, array $validated): Appointment {
    $oldStatus = $appt->status;

    // allow updating only allowed fields
    $appt->update([
      'appointment_type' => $validated['appointment_type'],
      'status'           => $validated['status'],
      'attended_by'      => $validated['attended_by'] ?? null,
    ]);

    $this->auditLogService->log('appointment_updated', [
      'appointment_id' => $appt->id,
    ]);

    // Notify the patient if their appointment status changed
    if ($oldStatus !== $appt->status) {
      $record  = PatientRecord::where('pid', $appt->patient_pid)->first();
      $patient = $record ? Patient::where('record_id', $record->id)->first() : null;
      $user    = $patient ? User::find($patient->user_id) : null;

      $user?->notify(new AppointmentStatusUpdated($appt));
    }

    return $appt->refresh();
  }
}