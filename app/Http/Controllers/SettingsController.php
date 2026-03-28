<?php

namespace App\Http\Controllers;

use App\Models\ClinicStaff;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleLabel = $this->roleLabel($user);
        $isPatient = $user->role === 0 && !Doctor::where('user_id', $user->id)->exists();

        $patientGender = null;
        $patientRecord = null;
        if ($user->role === 0) {
            $patientRow = Patient::where('user_id', $user->id)->first();
            if ($patientRow) {
                $patientRecord = PatientRecord::find($patientRow->record_id);
                $patientGender = $patientRecord?->gender;
            }
        }

        $contactNumber = null;
        $clinicStaff = null;
        if (!$isPatient) {
            $clinicStaff = ClinicStaff::where('user_id', $user->id)->first();
            $contactNumber = $clinicStaff?->ContactNumber;
        }

        $view = $isPatient ? 'usersettings.mobilesettings' : 'settings.settings';
        return view($view, compact('user', 'roleLabel', 'patientGender', 'contactNumber', 'patientRecord', 'clinicStaff'));
    }

    public function changePasswordForm()
    {
        $isPatient = $this->isPatient();
        $view = $isPatient ? 'usersettings.change-password' : 'settings.change-password';
        return view($view);
    }

    public function updateProfileForm()
    {
        $user = Auth::user();
        $isPatient = $this->isPatient();
        $clinicStaff = $isPatient ? null : ClinicStaff::where('user_id', $user->id)->first();
        $view = $isPatient ? 'usersettings.update-profile' : 'settings.update-profile';
        return view($view, compact('user', 'clinicStaff'));
    }
  
    public function helpGuide()
    {
        $isPatient = $this->isPatient();
        $view = $isPatient ? 'usersettings.help' : 'settings.help';
        return view($view);
    }

    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user();
    //     $request->validate([
    //         'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
    //         'email'    => ['required', 'email', 'lowercase', 'max:255', Rule::unique('users')->ignore($user->id)],
    //     ]);

    //     $user->username = $request->username;
    //     $user->email    = strtolower($request->email);
    //     $user->save();

    //     return redirect()->route('settings.setting')->with('status', 'profile-updated');
    // }
public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'username' => [
            'required',
            'string',
            'min:3',
            'max:30',
            'regex:/^[A-Za-zÑñ]+$/u',  // Letters + Ññ ONLY (no spaces)
            Rule::unique('users')->ignore($user->id),
        ],
        'email' => [
            'required', 
            'email', 
            'lowercase', 
            'max:255', 
            Rule::unique('users')->ignore($user->id),
        ],
    ]);

    $user->username = $request->username;
    $user->email = strtolower($request->email);
    $user->save();

    return redirect()->route('settings.setting')
        ->with('status', 'profile-updated');
}

    public function updateRecord(Request $request)
    {
        $user = Auth::user();
        $isPatient = $this->isPatient();

        if ($isPatient) {
            $request->validate([
                'first_name'     => 'nullable|string|max:100',
                'last_name'      => 'nullable|string|max:100',
                'middle_name'    => 'nullable|string|max:100',
                'date_of_birth'  => 'nullable|date',
                'gender'         => 'nullable|in:male,female,Male,Female',
                'nationality'    => 'nullable|string|max:100',
                'contact_number' => 'nullable|string|max:20',
                'address'        => 'nullable|string|max:255',
            ]);

            $patient = Patient::where('user_id', $user->id)->first();
            if ($patient && $patient->record_id) {
                $record = PatientRecord::find($patient->record_id);
                $record?->update($request->only([
                    'first_name', 'last_name', 'middle_name', 'date_of_birth',
                    'gender', 'nationality', 'contact_number', 'address',
                ]));
            }
        } else {
            $request->validate([
                'Fname'         => 'nullable|string|max:100',
                'Lname'         => 'nullable|string|max:100',
                'Mname'         => 'nullable|string|max:100',
                'ContactNumber' => 'nullable|string|max:20',
                'Address'       => 'nullable|string|max:255',
                'DateofBirth'   => 'nullable|date',
                'Gender'        => 'nullable|in:Male,Female',
            ]);

            ClinicStaff::where('user_id', $user->id)->update($request->only([
                'Fname', 'Lname', 'Mname', 'ContactNumber', 'Address', 'DateofBirth', 'Gender',
            ]));
        }

        return redirect()->route('settings.setting')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return redirect()->route('settings.setting')->with('status', 'password-updated');
    }

    private function isPatient(): bool
    {
        $user = Auth::user();
        return $user->role === 0 && !Doctor::where('user_id', $user->id)->exists();
    }

    private function roleLabel($user): string
    {
        if (Doctor::where('user_id', $user->id)->exists()) {
            return 'Doctor';
        }
        return match ($user->role) {
            2       => 'Admin',
            1       => 'Secretary',
            default => 'Patient',
        };
    }
}
