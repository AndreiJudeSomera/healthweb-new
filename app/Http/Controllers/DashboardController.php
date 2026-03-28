<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\PatientRecord;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 0: // normal user
                return redirect()->route('user.dashboard');
            case 1: // secretary
                return redirect()->route('secretary.dashboard');
            case 2: // superadmin
                return redirect()->route('superadmin.dashboard');
            default:
                abort(403, 'Unauthorized');
        }
    }

    public function staff()
    {
        $totalPatients      = PatientRecord::count();
        $totalRecords       = Consultation::count();
        $todayAppointments  = Appointment::whereDate('appointment_date', today())->count();
$totalConsultationsToday = Appointment::where('appointment_type', 'consultation')
    ->whereDate('appointment_date', Carbon::today())
    ->count();
       

        return view('dashboard', compact(
            'totalPatients',
            'totalRecords',
            'todayAppointments',
            'totalConsultationsToday',
        ));
    }
}
