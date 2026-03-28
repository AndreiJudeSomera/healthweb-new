<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\PatientRecord;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Services\IprogSmsService;
use App\Models\ClinicStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller {
  public function index() {
    $consultations = Consultation::with(['appointment', 'patient'])->get();
    return response()->json($consultations);
  }


  // public function store(Request $request) {
  //   $validated = $request->validate([
  //     'patient_pid' => ['required', 'exists:patient_records,pid'],
  //     'appointment_id' => ['nullable', 'exists:appointments,id'],
  //     'linked_consultation_id' => ['nullable', 'exists:consultations,id'],

  //     'document_type' => ['required', Rule::in([
  //       'medical-certificate',
  //       'referral-letter',
  //       'prescription',
  //       'consultation',
  //     ])],

  //     // consultations / med cert
  //     'consultation_date' => ['nullable', 'string'],

  //     // vitals
  //     'wt' => ['nullable', 'string'],
  //     'bp' => ['nullable', 'string'],
  //     'cr' => ['nullable', 'string'],
  //     'rr' => ['nullable', 'string'],
  //     'temperature' => ['nullable', 'string'],
  //     'sp02' => ['nullable', 'string'],

  //     // essays
  //     'history_physical_exam' => ['nullable', 'string'],
  //     'diagnosis' => ['nullable', 'string'],
  //     'treatment' => ['nullable', 'string'],

  //     // referral / med cert / misc
  //     'referral_to' => ['nullable', 'string'],
  //     'referral_reason' => ['nullable', 'string'],
  //     'remarks' => ['nullable', 'string'],

  //     // legacy (optional)
  //     'prescription_meds' => ['nullable', 'string'],

  //     // ✅ NEW: for prescription modal (hidden JSON input)
  //     'medicine_list' => ['nullable', 'string'],

  //     // ✅ NEW: for prescription date field (your modal uses created_at)
  //     'created_at' => ['nullable', 'date'],
  //   ]);

  //   // ✅ If not a prescription, keep old behavior (just store consultation row)
  //   if (($validated['document_type'] ?? null) !== 'prescription') {
  //     $consultation = Consultation::create($validated);
  //     $this->notifyPatient($validated['patient_pid'], $validated['document_type']);
  //     return response()->json($consultation, 201);
  //   }

  //   // ✅ Prescription path: decode medicine_list
  //   $medicineList = json_decode($validated['medicine_list'] ?? '[]', true);

  //   if (!is_array($medicineList) || count($medicineList) === 0) {
  //     return response()->json([
  //       'message' => 'Please add at least one medicine.',
  //       'errors' => ['medicine_list' => ['Please add at least one medicine.']],
  //     ], 422);
  //   }

  //   // Optional: build a legacy text version for fallback/PDF compatibility
  //   $legacyText = collect($medicineList)->map(function ($m) {
  //     $name = $m['medicine_name'] ?? 'MEDICINE';
  //     $parts = array_filter([
  //       $m['dosage'] ?? null,
  //       $m['frequency'] ?? null,
  //       $m['duration'] ?? null,
  //     ]);

  //     $line = $name;
  //     if (!empty($parts)) {
  //       $line .= ' — ' . implode(', ', $parts);
  //     }

  //     if (!empty($m['instructions'])) {
  //       $line .= "\n  • " . $m['instructions'];
  //     }

  //     return $line;
  //   })->implode("\n\n");

  //   return DB::transaction(function () use ($validated, $medicineList, $legacyText) {
  //     // 1) create the "document header" consultation row
  //     $consultation = Consultation::create([
  //       'patient_pid' => $validated['patient_pid'],
  //       'appointment_id' => $validated['appointment_id'] ?? null,
  //       'document_type' => 'prescription',

  //       // store prescription date in created_at (your UI uses created_at)
  //       'created_at' => $validated['created_at'] ?? now(),

  //       // optional legacy fallback
  //       'prescription_meds' => $legacyText,

  //       // optional notes/remarks if you start adding it later
  //       'remarks' => $validated['remarks'] ?? null,
  //     ]);

  //     // 2) create many prescription rows
  //     foreach ($medicineList as $m) {
  //       Prescription::create([
  //         'consultation_id' => $consultation->id,
  //         'medicine_id' => $m['medicine_id'] ?? null,
  //         'dosage' => $m['dosage'] ?? null,
  //         'frequency' => $m['frequency'] ?? null,
  //         'duration' => $m['duration'] ?? null,
  //         'instructions' => $m['instructions'] ?? null,
  //       ]);
  //     }

  //     $this->notifyPatient($validated['patient_pid'], 'prescription');

  //     return response()->json([
  //       'message' => 'Prescription created.',
  //       'consultation' => $consultation,
  //     ], 201);
  //   });
  // }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_pid' => ['required', 'exists:patient_records,pid'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'linked_consultation_id' => ['nullable', 'exists:consultations,id'],

            'document_type' => ['required', Rule::in([
                'medical-certificate',
                'referral-letter',
                'prescription',
                'consultation',
            ])],

            'consultation_date' => ['nullable', 'date'],

            'wt' => ['nullable', 'string'],
            'bp' => ['nullable', 'string'],
            'cr' => ['nullable', 'string'],
            'rr' => ['nullable', 'string'],
            'temperature' => ['nullable', 'string'],
            'sp02' => ['nullable', 'string'],

            'history_physical_exam' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'treatment' => ['nullable', 'string'],

            'referral_to' => ['nullable', 'string'],
            'referral_reason' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],

            'prescription_meds' => ['nullable', 'string'],
            'medicine_list' => ['nullable', 'string'],
            'created_at' => ['nullable', 'date'],
        ]);

            $user = Auth::user();
        $doctorId = $user->role == 2
            ? $user->clinicstaff->doctor->user_id ?? null
            : $user->clinicstaff->doctor->user_id ?? null;

        // // Add doctor_id to validated data
        // $validated['doctor_id'] = $doctorId;

        //  $user = Auth::user();
        // $doctorId = null;
        
        // if ($user && $user->clinicstaff && $user->clinicstaff->doctor) {
        //     $doctorId = $user->clinicstaff->doctor->user_id;
        // }

        // if (!$doctorId) {
        //     return response()->json([
        //         'message' => 'Doctor information not found.',
        //         'errors' => ['doctor_id' => ['Doctor information not found.']],
        //     ], 422);
        // }

        $validated['doctor_id'] = $doctorId;


        // Non-prescription
        if (($validated['document_type'] ?? null) !== 'prescription') {
            $consultation = Consultation::create($validated);
            $this->notifyPatient($validated['patient_pid'], $validated['document_type']);
            return response()->json($consultation, 201);
        }

        // Prescription logic
        $medicineList = json_decode($validated['medicine_list'] ?? '[]', true);
        if (!is_array($medicineList) || count($medicineList) === 0) {
            return response()->json([
                'message' => 'Please add at least one medicine.',
                'errors' => ['medicine_list' => ['Please add at least one medicine.']],
            ], 422);
        }

        $legacyText = collect($medicineList)->map(function ($m) {
            $name = $m['medicine_name'] ?? 'MEDICINE';
            $parts = array_filter([
                $m['dosage'] ?? null,
                $m['frequency'] ?? null,
                $m['duration'] ?? null,
            ]);

            $line = $name;
            if (!empty($parts)) $line .= ' — ' . implode(', ', $parts);
            if (!empty($m['instructions'])) $line .= "\n  • " . $m['instructions'];
            return $line;
        })->implode("\n\n");

        return DB::transaction(function () use ($validated, $medicineList, $legacyText) {
            $consultation = Consultation::create(array_merge($validated, [
                'document_type' => 'prescription',
                'prescription_meds' => $legacyText,
                'created_at' => $validated['created_at'] ?? now(),
            ]));

            foreach ($medicineList as $m) {
                $medicineId = $m['medicine_id'] ?? null;

                if (!$medicineId && !empty($m['medicine_name'])) {
                    $item = PrescriptionItem::firstOrCreate(
                        ['medicine_name' => $m['medicine_name']],
                        ['manually_added' => true]
                    );
                    $medicineId = $item->id;
                }

                Prescription::create([
                    'consultation_id' => $consultation->id,
                    'medicine_id'     => $medicineId,
                    'dosage'          => $m['dosage'] ?? null,
                    'frequency'       => $m['frequency'] ?? null,
                    'duration'        => $m['duration'] ?? null,
                    'instructions'    => $m['instructions'] ?? null,
                ]);
            }

            // $this->notifyPatient($validated['patient_pid'], 'prescription');

            return response()->json([
                'message' => 'Prescription created.',
                'consultation' => $consultation,
            ], 201);
        });
    }

  public function byId(int $id) {
    $consultation = Consultation::with(['appointment', 'patient', 'prescriptions.medicine'])->find($id);
    if (!$consultation) return response()->json(['message' => 'Not found'], 404);

    $data = $consultation->toArray();

    if ($consultation->document_type === 'prescription') {
      $data['medicine_list'] = $consultation->prescriptions->map(fn($p) => [
        'medicine_id'    => $p->medicine_id,
        'medicine_name'  => $p->medicine?->medicine_name ?? 'Unknown',
        'manually_added' => (bool) ($p->medicine?->manually_added ?? false),
        'dosage'        => $p->dosage ?? '',
        'frequency'     => $p->frequency ?? '',
        'duration'      => $p->duration ?? '',
        'instructions'  => $p->instructions ?? '',
      ])->values()->toArray();
    }

    return response()->json($data);
  }

  public function byPid(string $pid, Request $request) {
    $q = Consultation::with(['appointment', 'patient'])
      ->where('patient_pid', $pid);

    if ($request->filled('type')) {
      $q->where('document_type', $request->type);
    }

    return response()->json($q->get());
  }
public function update(Request $request, int $id)
{
    $consultation = Consultation::find($id);

    if (!$consultation) {
        return response()->json(['message' => 'Document/Consultation not found'], 404);
    }

    $validated = $request->validate([
        'document_type'         => ['nullable', 'in:medical-certificate,referral-letter,prescription,consultation'],
        'consultation_date'     => ['nullable', 'string'],
        'created_at'            => ['nullable', 'date'],
        'wt'                    => ['nullable', 'string'],
        'bp'                    => ['nullable', 'string'],
        'cr'                    => ['nullable', 'string'],
        'rr'                    => ['nullable', 'string'],
        'temperature'           => ['nullable', 'string'],
        'sp02'                  => ['nullable', 'string'],
        'history_physical_exam' => ['nullable', 'string'],
        'diagnosis'             => ['nullable', 'string'],
        'treatment'             => ['nullable', 'string'],
        'referral_to'           => ['nullable', 'string'],
        'referral_reason'       => ['nullable', 'string'],
        'prescription_meds'     => ['nullable', 'string'],
        'medicine_list'         => ['nullable', 'string'],
        'remarks'               => ['nullable', 'string'],
    ]);

    $user = Auth::user();

    if ($user->role == 2) {
        $doctorId = $user->clinicstaff->doctor->user_id ?? null;
        $validated['doctor_id'] = $doctorId;
    }

    // Prescription: replace medicine rows and rebuild legacy text
    if ($consultation->document_type === 'prescription' && isset($validated['medicine_list'])) {
        $medicineList = json_decode($validated['medicine_list'], true);
        if (is_array($medicineList) && count($medicineList) > 0) {
            $legacyText = collect($medicineList)->map(function ($m) {
                $name  = $m['medicine_name'] ?? 'MEDICINE';
                $parts = array_filter([$m['dosage'] ?? null, $m['frequency'] ?? null, $m['duration'] ?? null]);
                $line  = $name;
                if (!empty($parts)) $line .= ' — ' . implode(', ', $parts);
                if (!empty($m['instructions'])) $line .= "\n  • " . $m['instructions'];
                return $line;
            })->implode("\n\n");

            $validated['prescription_meds'] = $legacyText;
            $consultation->prescriptions()->delete();

            foreach ($medicineList as $m) {
                $medicineId = $m['medicine_id'] ?? null;

                if (!$medicineId && !empty($m['medicine_name'])) {
                    $item = PrescriptionItem::firstOrCreate(
                        ['medicine_name' => $m['medicine_name']],
                        ['manually_added' => true]
                    );
                    $medicineId = $item->id;
                }

                Prescription::create([
                    'consultation_id' => $consultation->id,
                    'medicine_id'     => $medicineId,
                    'dosage'          => $m['dosage'] ?? null,
                    'frequency'       => $m['frequency'] ?? null,
                    'duration'        => $m['duration'] ?? null,
                    'instructions'    => $m['instructions'] ?? null,
                ]);
            }
        }
        unset($validated['medicine_list']);
    }

    $consultation->update($validated);

    return response()->json([
        'message' => 'Consultation updated successfully.',
        'data'    => $consultation,
    ]);
}

  private function notifyPatient(string $pid, string $documentType): void {
    $patient = PatientRecord::where('pid', $pid)->first();
    $contact = $patient?->contact_number;

    if (!$contact) return;

    $message = match ($documentType) {
      'consultation'      => 'HealthWeb: A new consultation record has been added for you.',
      'medical-certificate' => 'HealthWeb: Your medical certificate has been issued.',
      'referral-letter'   => 'HealthWeb: A referral letter has been issued for you.',
      'prescription'      => 'HealthWeb: A new prescription has been issued for you.',
      default             => null,
    };

    if ($message) {
      app(IprogSmsService::class)->send($contact, $message);
    }
  }

  public function destroy(int $id) {
    $consultation = Consultation::find($id);

    if (!$consultation) {
      return response()->json(['message' => 'Document/Consultation not found'], 404);
    }

    $consultation->delete();

    return response()->json([
      'message' => 'Document/Consultation deleted successfully',
    ]);
  }
}
