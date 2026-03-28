<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\PatientRecord;
use App\Services\IprogSmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature   = 'appointments:send-reminders';
    protected $description = 'Send SMS reminders to patients 2 days and 30 minutes before their appointment';

    public function handle(IprogSmsService $sms): void
    {
        $this->send2DayReminders($sms);
        $this->send30MinReminders($sms);
    }

    private function send2DayReminders(IprogSmsService $sms): void
    {
        $targetDate = now()->addDays(2)->toDateString();

        $appointments = Appointment::query()
            ->where('appointment_date', $targetDate)
            ->where('status', 'approved')
            ->whereNull('reminder_2day_sent_at')
            ->get();

        foreach ($appointments as $appointment) {
            $record = PatientRecord::where('pid', $appointment->patient_pid)->first();

            if ($record?->contact_number) {
                $time = Carbon::parse($appointment->appointment_time)->format('h:i A');
                $sms->send(
                    $record->contact_number,
                    "HealthWeb: Reminder - your {$appointment->appointment_type} appointment is in 2 days on {$appointment->appointment_date} at {$time}. See you at the clinic!"
                );
            }

            $appointment->update(['reminder_2day_sent_at' => now()]);
        }

        $this->info("2-day reminders sent: {$appointments->count()}");
    }

    private function send30MinReminders(IprogSmsService $sms): void
    {
        $windowStart = now()->addMinutes(28);
        $windowEnd   = now()->addMinutes(32);

        $timeStart = $windowStart->format('H:i:s');
        $timeEnd   = $windowEnd->format('H:i:s');
        $today     = now()->toDateString();

        $appointments = Appointment::query()
            ->where('appointment_date', $today)
            ->where('status', 'approved')
            ->whereNull('reminder_30min_sent_at')
            ->whereBetween('appointment_time', [$timeStart, $timeEnd])
            ->get();

        foreach ($appointments as $appointment) {
            $record = PatientRecord::where('pid', $appointment->patient_pid)->first();

            if ($record?->contact_number) {
                $time = Carbon::parse($appointment->appointment_time)->format('h:i A');
                $sms->send(
                    $record->contact_number,
                    "HealthWeb: Your {$appointment->appointment_type} appointment starts in 30 minutes at {$time}. Please make your way to the clinic."
                );
            }

            $appointment->update(['reminder_30min_sent_at' => now()]);
        }

        $this->info("30-min reminders sent: {$appointments->count()}");
    }
}
