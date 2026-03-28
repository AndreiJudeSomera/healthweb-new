<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusUpdated extends Notification {
  use Queueable;

  public function __construct(protected Appointment $appointment) {}

  public function via(object $notifiable): array {
    return ['database'];
  }

  public function toDatabase(object $notifiable): array {
    $appt = $this->appointment;

    return [
      'appointment_id' => $appt->id,
      'type'           => $appt->appointment_type,
      'date'           => $appt->appointment_date,
      'status'         => $appt->status,
      'message'        => "Your {$appt->appointment_type} appointment on {$appt->appointment_date} has been {$appt->status}.",
      'link'           => '/p/dashboard',
    ];
  }
}