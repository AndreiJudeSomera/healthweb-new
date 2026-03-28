<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentCreated extends Notification {
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
      'patient_pid'    => $appt->patient_pid,
      'type'           => $appt->appointment_type,
      'date'           => $appt->appointment_date,
      'time'           => $time,
      'message'        => "New {$appt->appointment_type} appointment booked for {$appt->appointment_date} at {$time}.",
      'link'           => '/appointments',
    ];
  }
}