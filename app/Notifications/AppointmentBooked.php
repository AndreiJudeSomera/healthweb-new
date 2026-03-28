<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification {
  use Queueable;

  public function __construct(protected Appointment $appointment) {}

  public function via(object $notifiable): array {
    return ['database'];
  }

  public function toDatabase(object $notifiable): array {
    $appt = $this->appointment;
    $time = Carbon::parse($appt->appointment_time)->format('h:i A');

    return [
      'appointment_id' => $appt->id,
      'type'           => $appt->appointment_type,
      'date'           => $appt->appointment_date,
      'time'           => $time,
      'message'        => "Your {$appt->appointment_type} appointment on {$appt->appointment_date} at {$time} has been booked.",
      'link'           => '/p/appointments',
    ];
  }
}
