<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Str;


class PatientController extends Controller {
  public function index(Request $request) {
    $patients = Patient::query()
      ->leftJoin('users', 'patients.user_id', '=', 'users.id')
      ->leftJoin('patient_records', 'patients.record_id', '=', 'patient_records.id')
      ->select([
        'patients.user_id',
        'patients.patient_type',
        'patients.record_id',
        'patients.created_at',

        'users.email as user_email',

        'patient_records.pid as pid',
        'patient_records.last_name as user_last_name',
        'patient_records.first_name as user_first_name',
        'patient_records.gender as gender',
        'patient_records.date_of_birth as date_of_birth',
      ])
      ->orderBy('patients.user_id')
      ->get();

    if ($request->expectsJson()) {
      return response()->json($patients);
    }

    return view('patients.index', compact('patients'));
  }

  public function search(Request $request) {
    $q = trim((string) $request->query('q', ''));

    if ($q === '') {
      return response()->json([]);
    }

    $patients = Patient::query()
      ->leftJoin('users', 'patients.user_id', '=', 'users.id')
      ->leftJoin('patient_records', 'patients.record_id', '=', 'patient_records.id')
      ->select([
        'patients.user_id',
        'patients.patient_type',
        'patients.record_id',
        'patients.created_at',

        'users.email as user_email',

        'patient_records.pid as pid',
        'patient_records.last_name as user_last_name',
        'patient_records.first_name as user_first_name',
        'patient_records.gender as gender',
        'patient_records.date_of_birth as date_of_birth',
      ])
      ->where(function ($query) use ($q) {
        $query->where('users.first_name', 'like', "%{$q}%")
          ->orWhere('users.last_name', 'like', "%{$q}%")
          ->orWhere('users.email', 'like', "%{$q}%")
          ->orWhere('patient_records.pid', 'like', "%{$q}%");

        if (ctype_digit($q)) {
          $query->orWhere('patients.user_id', (int) $q);
        }
      })
      ->orderBy('patients.user_id')
      ->get();

    $patients = $patients->map(function ($r) {
      $age = null;

      if (!empty($r->date_of_birth)) {
        $age = Carbon::parse($r->date_of_birth)->age;
      }

      return [
        'user_id' => $r->user_id,
        'patient_type' => $r->patient_type,
        'record_id' => $r->record_id,
        'created_at' => $r->created_at,

        'user_first_name' => $r->user_first_name,
        'user_last_name' => $r->user_last_name,
        'user_email' => $r->user_email,

        'pid' => $r->pid,
        'gender' => $r->gender,
        'age' => $age,
      ];
    });

    if ($request->expectsJson()) {
      return response()->json($patients);
    }

    return view('patients.index');
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'patient_type' => ['required', 'in:new,old'],
    ]);

    // Insert or update patient record
    $patient = Patient::updateOrCreate(
      ['user_id' => Auth::id()],
      ['patient_type' => $validated['patient_type']]
    );
  }

  public function bindRecord(Request $request, AuditLogService $audit) {
    $validated = $request->validate([
      'pid' => ['required'],
    ]);

    $userId = Auth::id();

    if (!$userId) {
      return redirect()->route('login');
    }

    $record = PatientRecord::where('pid', $validated['pid'])->first();

    if (!$record) {
      return redirect()
        ->route('patient.onboarding.usertype')
        ->with('failed', 'Patient record not found!');
    }

    $patient = Patient::updateOrCreate(
      ['user_id' => Auth::id()],
      [
        'user_id' => Auth::id(),
        'record_id' => $record->id,
        'patient_type' => 'old',
      ]
    );

    $audit->log(
      action: 'patient_record.bind',
      userId: $userId,
      entityType: 'PatientRecord',
      entityId: $record->id,
      meta: [
        'pid' => $record->pid,
        'patient_id' => $patient->id ?? null,
        'patient_type' => 'old',
      ]
    );

    return redirect()
      ->route('patient.dashboard')
      ->with('success', 'Patient record bound successfully!');
  }

  public function onboardingCheck() {
    $patient = Patient::where('user_id', auth()->id())->first();

    if (!$patient) {
      return view('userinfo.usertype');
    }

    if ($patient->record_id) {
      return redirect()->route('patient.dashboard');
    }

    return view('userinfo.usertype');
  }

  // public function onboardingOldPatient() {
  //   $authId = Auth::id();
  //   return view('mobilelayouts.onboarding.old-patient', ['authId' => $authId]);
  // }

  public function onboardingOldPatient()
{
    $userId = Auth::id();

    if (!$userId) {
        return redirect()->route('login');
    }

    // Store patient type as OLD and remove record_id
    Patient::updateOrCreate(
        ['user_id' => $userId],
        [
            'patient_type' => 'old',
            'record_id' => null,
        ]
    );

    return view('mobilelayouts.onboarding.old-patient', [
        'authId' => $userId
    ]);
}
  public function onboardingNewPatient() {
    $authId = Auth::id();
    return view('mobilelayouts.onboarding.new-patient', ['authId' => $authId]);
  }

  // public function storeNewPatientRecord(Request $request, AuditLogService $audit) {
  //   $userId = Auth::id();
  //   if (!$userId) {
  //     return redirect()->route('login')->with('failed', 'Please login again.');
  //   }

  //   // Validate form (matches your Blade fields)
  //   $validated = $request->validate([
  //     'last_name' => ['required', 'string', 'max:100'],
  //     'first_name' => ['required', 'string', 'max:100'],
  //     'middle_name' => ['nullable', 'string', 'max:100'],

  //     'date_of_birth' => ['required', 'date'],
  //     'gender' => ['required', 'in:male,female'],
  //     'nationality' => ['required', 'string', 'max:100'],

  //     'contact_number' => ['required', 'string', 'max:30'],
  //     'address' => ['required', 'string', 'max:255'],

  //     'guardian_name' => ['nullable', 'string', 'max:100'],
  //     'guardian_relation' => ['nullable', 'string', 'max:50'],
  //     'guardian_contact' => ['nullable', 'string', 'max:30'],

  //     'allergy' => ['required', 'string', 'max:255'],
  //     'alcohol' => ['required', 'in:never,occasional,heavy'],
  //     'years_of_smoking' => ['required', 'integer', 'min:0', 'max:120'],
  //     'illicit_drug_use' => ['required', 'string', 'max:50'],

  //     // checkboxes: if checked => "on", else missing
  //     'hypertension' => ['nullable'],
  //     'asthma' => ['nullable'],
  //     'diabetes' => ['nullable'],
  //     'cancer' => ['nullable'],
  //     'thyroid' => ['nullable'],

  //     'others' => ['nullable', 'string', 'max:255'],
  //   ]);

  //   // Normalize checkbox values to 0/1 (optional but recommended)
  //   $validated['hypertension'] = $request->boolean('hypertension');
  //   $validated['asthma'] = $request->boolean('asthma');
  //   $validated['diabetes'] = $request->boolean('diabetes');
  //   $validated['cancer'] = $request->boolean('cancer');
  //   $validated['thyroid'] = $request->boolean('thyroid');

  //   try {
  //     DB::beginTransaction();

  //     // 1) create patient record
  //     $record = PatientRecord::create($validated);

  //     // 2) bind to patients table (upsert)
  //     Patient::updateOrCreate(
  //       ['user_id' => $userId],
  //       [
  //         'record_id' => $record->id,
  //         'patient_type' => 'new',
  //       ]
  //     );

  //     DB::commit();

  //     $audit->log(
  //       action: 'patient_record.create',
  //       userId: $userId,
  //       entityType: 'PatientRecord',
  //       entityId: $record->id,
  //       meta: [
  //         'patient_id' => $patient->id ?? null,
  //         'patient_type' => 'new',
  //         'pid' => $record->pid ?? null, // if you have pid column
  //         'name' => trim(($record->last_name ?? '') . ', ' . ($record->first_name ?? '') . ' ' . ($record->middle_name ?? '')),
  //       ]
  //     );

  //     return redirect()
  //       ->route('patient.dashboard')
  //       ->with('success', 'New patient record created successfully!');
  //   } catch (\Throwable $e) {
  //     DB::rollBack();

  //     return back()
  //       ->withInput()
  //       ->with('failed', 'Failed to create record. Please try again.');
  //   }
  // }



public function storeNewPatientRecord(Request $request, AuditLogService $audit)
{
    $userId = Auth::id();

    if (!$userId) {
        return redirect()->route('login')->with('failed', 'Please login again.');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */
    $validated = $request->validate([
        'last_name' => ['required', 'string', 'max:100'],
        'first_name' => ['required', 'string', 'max:100'],
        'middle_name' => ['nullable', 'string', 'max:100'],

        'date_of_birth' => ['required', 'date'],
        'gender' => ['required', 'in:male,female'],
        'nationality' => ['required', 'string', 'max:100'],

        'contact_number' => ['required', 'string', 'max:30'],
        'address' => ['required', 'string', 'max:255'],

        'guardian_name' => ['nullable', 'string', 'max:100'],
        'guardian_relation' => ['nullable', 'string', 'max:50'],
        'guardian_contact' => ['nullable', 'string', 'max:30'],

        'allergy' => ['required', 'string', 'max:255'],
        'alcohol' => ['required', 'in:never,occasional,heavy'],
        'years_of_smoking' => ['required', 'integer', 'min:0', 'max:120'],
        'illicit_drug_use' => ['required', 'string', 'max:50'],

        'hypertension' => ['nullable'],
        'asthma' => ['nullable'],
        'diabetes' => ['nullable'],
        'cancer' => ['nullable'],
        'thyroid' => ['nullable'],

        'others' => ['nullable', 'string', 'max:255'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE CHECKBOXES
    |--------------------------------------------------------------------------
    */
    $validated['hypertension'] = $request->boolean('hypertension');
    $validated['asthma'] = $request->boolean('asthma');
    $validated['diabetes'] = $request->boolean('diabetes');
    $validated['cancer'] = $request->boolean('cancer');
    $validated['thyroid'] = $request->boolean('thyroid');

    /*
    |--------------------------------------------------------------------------
    | SENTENCE CASE NORMALIZATION
    |--------------------------------------------------------------------------
    */
    $validated['first_name'] = $this->toSentenceCase($validated['first_name']);
    $validated['last_name']  = $this->toSentenceCase($validated['last_name']);
    $validated['middle_name'] = $validated['middle_name']
        ? $this->toSentenceCase($validated['middle_name'])
        : null;

    /*
    |--------------------------------------------------------------------------
    | DUPLICATE CHECK (case-insensitive)
    |--------------------------------------------------------------------------
    */
    $duplicate = PatientRecord::whereDate('date_of_birth', $validated['date_of_birth'])
        ->get()
        ->first(function ($record) use ($validated) {
            return
                mb_strtolower(trim($record->first_name)) === mb_strtolower(trim($validated['first_name'])) &&
                mb_strtolower(trim($record->last_name)) === mb_strtolower(trim($validated['last_name']));
        });

    if ($duplicate) {
        return back()
            ->withInput()
            ->with('failed', 'Patient record already exists.');
    }

    try {
        DB::beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | CREATE RECORD
        |--------------------------------------------------------------------------
        */
        $record = PatientRecord::create($validated);

        /*
        |--------------------------------------------------------------------------
        | BIND PATIENT
        |--------------------------------------------------------------------------
        */
        $patient = Patient::updateOrCreate(
            ['user_id' => $userId],
            [
                'record_id' => $record->id,
                'patient_type' => 'new',
            ]
        );

        DB::commit();

        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG (FIXED $patient usage)
        |--------------------------------------------------------------------------
        */
        $audit->log(
            action: 'patient_record.create',
            userId: $userId,
            entityType: 'PatientRecord',
            entityId: $record->id,
            meta: [
                'patient_id' => $patient->id,
                'patient_type' => 'new',
                'pid' => $record->pid ?? null,
                'name' => trim(($record->last_name ?? '') . ', ' . ($record->first_name ?? '') . ' ' . ($record->middle_name ?? '')),
            ]
        );

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'New patient record created successfully!');

    } catch (\Throwable $e) {
        DB::rollBack();

        return back()
            ->withInput()
            ->with('failed', 'Failed to create record. Please try again.');
    }
}

private function toSentenceCase($value)
{
    if (!$value) return $value;

    return collect(explode(' ', trim($value)))
        ->map(fn($word) => ucfirst(strtolower($word)))
        ->implode(' ');
}

  public function dashboardPatientRole() {
    $patient = Patient::with('record', 'user')->where('user_id', auth()->id())->first();

    if (!$patient) {
      return view('mobilelayouts.dashboard.dashboard', []);
    }

    $pid = $patient->record->pid;

    $upcomingAppointments = Appointment::where('patient_pid', $pid)
      ->whereIn('status', ['pending', 'approved'])
      ->orderBy('appointment_date')
      ->orderBy('appointment_time')
      ->get();

    $nextAppointment = $upcomingAppointments->first();

    // $totalDocuments = Consultation::where('patient_pid', $pid)->count();
  $totalDocuments = Consultation::where('patient_pid', $pid)
    ->whereIn('document_type', ['consultation'])
    ->count();
    $recentDocuments = Consultation::where('patient_pid', $pid)
      ->orderByDesc('created_at')
      ->limit(3)
      ->get();

    return view('mobilelayouts.dashboard.dashboard', [
      'first_name'           => $patient->record->first_name,
      'last_name'            => $patient->record->last_name,
      'pid'                  => $pid,
      'gender'               => $patient->record->gender,
      'patient_type'         => $patient->patient_type,
      'upcomingCount'        => $upcomingAppointments->count(),
      'nextAppointment'      => $nextAppointment,
      'totalDocuments'       => $totalDocuments,
      'recentDocuments'      => $recentDocuments,
    ]);
  }

  public function appointmentsPatientShow() {
    $patient = Patient::with('record', 'user')->where('user_id', auth()->id())->first();

    if (!$patient) {
      return redirect()->route('patient.dashboard');
    }

    $patientData =
      [
       ...$patient->toArray(),
      'user_id' => $patient->user_id,
      'patient_type' => $patient->patient_type,
      'record_id' => $patient->record_id,
      'created_at' => $patient->created_at,

      'first_name' => $patient->record->first_name,
      'last_name' => $patient->record->last_name,
      'email' => $patient->user->email,

      'pid' => $patient->record->pid,
      'gender' => $patient->record->gender,
    ];

    $appointments = Appointment::with(['patient', 'doctor.user:id,username'])
      ->where('patient_pid', $patientData['pid'])
      ->get();

    return view('mobilelayouts.appointments.patient-appointments', [
      'patient' => $patientData,
      'appointments' => $appointments ?? [],
    ]);
  }

  public function recordsPatientShow() {
    $patient = Patient::with('record', 'user')->where('user_id', auth()->id())->firstOrFail();

    $patientData =
      [
       ...$patient->toArray(),
      'user_id' => $patient->user_id,
      'patient_type' => $patient->patient_type,
      'record_id' => $patient->record_id,
      'created_at' => $patient->created_at,

      'first_name' => $patient->record->first_name,
      'last_name' => $patient->record->last_name,
      'email' => $patient->user->email,

      'pid' => $patient->record->pid,
      'gender' => $patient->record->gender,
    ];

    $consultations = Consultation::with(['appointment', 'patient'])
    ->where([
        ['patient_pid', $patientData['pid']],
        ['document_type', 'consultation']
    ])
    ->get();

    return view('mobilelayouts.records.patient-records', [
      'consultations' => $consultations,
      'patient' => $patientData,
    ]);

  }
}
