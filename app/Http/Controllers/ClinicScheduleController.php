<?php

namespace App\Http\Controllers;

use App\Models\ClinicSchedule;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicScheduleController extends Controller
{
    private static array $defaults = [
        ['day_of_week' => 0, 'day_name' => 'Sunday',    'is_open' => false, 'open_time' => null,       'close_time' => null],
        ['day_of_week' => 1, 'day_name' => 'Monday',    'is_open' => true,  'open_time' => '09:00:00', 'close_time' => '16:00:00'],
        ['day_of_week' => 2, 'day_name' => 'Tuesday',   'is_open' => true,  'open_time' => '09:00:00', 'close_time' => '16:00:00'],
        ['day_of_week' => 3, 'day_name' => 'Wednesday', 'is_open' => true,  'open_time' => '09:00:00', 'close_time' => '16:00:00'],
        ['day_of_week' => 4, 'day_name' => 'Thursday',  'is_open' => true,  'open_time' => '09:00:00', 'close_time' => '16:00:00'],
        ['day_of_week' => 5, 'day_name' => 'Friday',    'is_open' => true,  'open_time' => '09:00:00', 'close_time' => '16:00:00'],
        ['day_of_week' => 6, 'day_name' => 'Saturday',  'is_open' => true,  'open_time' => '09:00:00', 'close_time' => '16:00:00'],
    ];

    public function index()
    {
        $this->ensureScheduleExists();
        $schedule = ClinicSchedule::orderBy('day_of_week')->get();
        return response()->json(['data' => $schedule]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isDoctor = Doctor::where('user_id', $user->id)->exists();

        if (!($isDoctor || $user->role >= 1)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'is_open'    => 'required|boolean',
            'open_time'  => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
        ]);

        $day = ClinicSchedule::findOrFail($id);
        $day->update([
            'is_open'    => $request->is_open,
            'open_time'  => $request->is_open ? $request->open_time : null,
            'close_time' => $request->is_open ? $request->close_time : null,
        ]);

        return response()->json(['message' => 'Clinic hours updated.', 'data' => $day]);
    }

    private function ensureScheduleExists(): void
    {
        if (ClinicSchedule::count() < 7) {
            foreach (self::$defaults as $day) {
                ClinicSchedule::firstOrCreate(['day_of_week' => $day['day_of_week']], $day);
            }
        }
    }
}
