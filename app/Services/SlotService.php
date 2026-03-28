<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SlotService {
  public function getAvailableSlots(string $dateYmd): array {
    $date = Carbon::createFromFormat('Y-m-d', $dateYmd);

    if ($date->isWeekend()) {
      return [
        'date' => $date->toDateString(),
        'capacity' => (int) config('appointments.slot_capacity'),
        'slots' => [],
        'message' => 'No appointments on weekends.',
      ];
    }

    $allowedTimes = (array) config('appointments.allowed_times');
    $capacity = (int) config('appointments.slot_capacity');
    $countStatuses = (array) config('appointments.count_statuses');

    $counts = DB::table('appointments')
      ->select('appointment_time', DB::raw('COUNT(*) as booked'))
      ->where('appointment_date', $date->toDateString())
      ->whereIn('status', $countStatuses)
      ->groupBy('appointment_time')
      ->pluck('booked', 'appointment_time');

    $slots = collect($allowedTimes)->map(function ($time) use ($counts, $capacity, $date) {
      $booked = (int) ($counts[$time] ?? 0);
      $remaining = max(0, $capacity - $booked);

      return [
        'date' => $date->toDateString(),
        'time' => $time,
        'label' => Carbon::createFromFormat('H:i:s', $time)->format('h:i A'),
        'booked' => $booked,
        'capacity' => $capacity,
        'remaining' => $remaining,
        'is_full' => $remaining === 0,
      ];
    })->values()->all();

    return [
      'date' => $date->toDateString(),
      'capacity' => $capacity,
      'slots' => $slots,
    ];
  }

  public function assertValidSlot(string $dateYmd, string $timeHis): void {
    $date = Carbon::createFromFormat('Y-m-d', $dateYmd);

    if ($date->isWeekend()) {
      abort(422, 'Appointments are weekdays only.');
    }

    $allowedTimes = (array) config('appointments.allowed_times');
    if (!in_array($timeHis, $allowedTimes, true)) {
      abort(422, 'Invalid appointment time slot.');
    }

    $capacity = (int) config('appointments.slot_capacity');
    $countStatuses = (array) config('appointments.count_statuses');

    $booked = Appointment::query()
      ->where('appointment_date', $dateYmd)
      ->where('appointment_time', $timeHis)
      ->whereIn('status', $countStatuses)
      ->count();

    if ($booked >= $capacity) {
      abort(409, 'That slot is already full.');
    }
  }
}
