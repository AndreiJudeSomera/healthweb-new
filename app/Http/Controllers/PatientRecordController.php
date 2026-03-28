<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRecordRequest;
use App\Http\Requests\UpdatePatientRecordRequest;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PatientRecordController extends Controller {
  public function index() {
    $patients = PatientRecord::query()
      ->select([
        "*",
      ])
      ->orderBy('created_at')
      ->get()
      ->map(function ($r) {
        return [
           ...$r->toArray(),
          'pid' => $r->pid,
          'user_last_name' => $r->last_name,
          'user_first_name' => $r->first_name,
          'age' => $r->age,
        ];
      });

    return response()->json($patients);
  }

  public function search(Request $request) {
    $q = trim((string) $request->query('pid', ''));

    if ($q === '') {
      return response()->json([
        "data" => "No query",
      ]);
    }

    $patient = PatientRecord::query()
      ->where(function ($query) use ($q) {
        $query->where('pid', $q);
      })
      ->first();

    if (!$patient) {
      return response()->json([
        "data" => "Patient not found.",
      ]);
    }

    $age = null;
    if (!empty($patient->date_of_birth)) {
      $age = Carbon::parse($patient->date_of_birth)->age;
    }

    $data = collect($patient->toArray())
      ->mapWithKeys(function ($value, $key) use ($patient) {
        return [$key => $patient->$key];
      })->toArray();

    $data['age'] = $age;

    return response()->json($data);
  }

//   public function store(StorePatientRecordRequest $request, AuditLogService $audit) {

//    $validated = $request->validated();

//    $patient = PatientRecord::create([
//   ...$request->validated(),
//   'patient_type' => 'old',
// ]);

//     $audit->log(
//       action: 'patient_record.create_staff',
//       userId: auth()->id(),
//       entityType: 'PatientRecord',
//       entityId: $patient->id,
//       meta: [
//         'pid' => $patient->pid ?? null,
//         'created_via' => 'staff',
//       ]
//     );

//     return redirect()
//       ->route('patients.index')
//       ->with('success', 'Patient record created successfully! PID: '
//         . $patient->pid);
//   }
public function store(StorePatientRecordRequest $request, AuditLogService $audit)
{
    $data = $request->validated();

    $patient = PatientRecord::create([
        ...$data,
        'patient_type' => 'old',
    ]);

    $audit->log(
        action: 'patient_record.create_staff',
        userId: auth()->id(),
        entityType: 'PatientRecord',
        entityId: $patient->id,
        meta: [
            'pid' => $patient->pid ?? null,
            'created_via' => 'staff',
        ]
    );

    return response()->json([
        'message' => 'Patient created successfully',
        'pid' => $patient->pid,
    ]);
}

  public function update(UpdatePatientRecordRequest $request, string $pid, AuditLogService $audit) {
    $patient = PatientRecord::where('pid', $pid)->first();

    if (!$patient) {
      return response()->json(['data' => 'Patient not found.'], 404);
    }

    // capture BEFORE (only safe keys)
    $before = $patient->only([
      'first_name',
      'last_name',
      'middle_name',
      'contact_number',
      'address',
    ]);

    // perform update
    $patient->update($request->validated());

    // capture AFTER
    $after = $patient->only([
      'first_name',
      'last_name',
      'middle_name',
      'contact_number',
      'address',
    ]);

    // compute diff (using your service helper)
    $changes = $audit->diff($before, $after);

    // ✅ only log if something actually changed
    if (!empty($changes)) {
      $audit->log(
        action: 'patient_record.update',
        userId: auth()->id(),
        entityType: 'PatientRecord',
        entityId: $patient->id,
        meta: [
          'pid' => $patient->pid,
        ],
        changes: $changes
      );
    }

    // existing logic
    $age = !empty($patient->date_of_birth)
    ? Carbon::parse($patient->date_of_birth)->age
    : null;

    $data = collect($patient->toArray())
      ->mapWithKeys(fn($value, $key) => [$key => $patient->$key])
      ->toArray();

    $data['age'] = $age;

    return response()->json([
      'data' => 'Updated successfully.',
      'patient' => $data,
    ]);
  }

  public function destroy(Request $request, string $pid) {
    $patient = PatientRecord::where('pid', $pid)->first();

    if (!$patient) {
      return response()->json(['data' => 'Patient not found.'], 404);
    }

    $patient->delete();

    return response()->json([
      'data' => 'Deleted successfully.',
      'pid' => $pid,
    ]);
  }

  public function show(PatientRecord $patient) {
    return view('patients.show.index', compact('patient'));
  }

  public function documentShow(PatientRecord $patient) {
    return view('patients.show-documents.documents-index', compact('patient'));
  }

  public function getOldPatients() {
    $patients = PatientRecord::query()
      ->where('patient_type', 'old')
      ->get()
      ->map(function ($p) {
        return [
           ...$p->toArray(),
          "p_first_name" => $p->first_name,
          "p_last_name" => $p->last_name,
        ];
      });

    if (!$patients) {
      return response()->json([
        "data" => "Patient not found.",
      ]);
    }

    return response()->json($patients);
  }

  public function getNewUsers() {
    $patients = Patient::with('user')
      ->where('record_id', null)
      ->get();

    return response()->json($patients);
  }

  public function bindUser(Request $request, string $id, AuditLogService $audit) {
    $validated = $request->validate([
      'record_id' => ['required', 'integer', 'exists:patient_records,id'],
    ]);

    $actorId = auth()->id(); // the staff/admin doing the action

    DB::beginTransaction();

    try {
      $patient = Patient::where('user_id', $id)->firstOrFail();

      $before = [
        'record_id' => $patient->record_id,
      ];

      $patient->update([
        'record_id' => $validated['record_id'],
      ]);

      $patientRecord = PatientRecord::where('id', $patient->record_id)->firstOrFail();

      $beforeRecord = [
        'is_bound' => $patientRecord->is_bound,
      ];

      $patientRecord->update([
        'is_bound' => 1,
      ]);

      DB::commit();

      $audit->log(
        action: 'patient.bind_user',
        userId: $actorId,
        entityType: 'Patient',
        entityId: $patient->id,
        meta: [
          'target_user_id' => $id, // user being bound
          'patient_id' => $patient->id,
          'patient_record_id' => $patient->record_id, // new record_id
        ],
        changes: [
          'patient.record_id' => ['from' => $before['record_id'], 'to' => $patient->record_id],
          'patient_record.is_bound' => ['from' => $beforeRecord['is_bound'], 'to' => 1],
        ]
      );

      return response()->json([
        'data' => 'Updated successfully.',
        'patient' => $patient->fresh(),
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'message' => 'Failed to bind user.',
      ], 500);
    }
  }
}
