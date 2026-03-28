<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller {
  // GET /prescriptions
  public function index() {
    $prescriptions = Prescription::query()
      ->with(['medicine:id,medicine_name'])
      ->latest('id')
      ->get();

    return response()->json($prescriptions);
  }

  // GET /prescriptions/{prescription}
  public function show(Prescription $prescription) {
    $prescription->load(['medicine:id,medicine_name']);

    return response()->json($prescription);
  }

  // POST /prescriptions
  // public function store(Request $request) {
  //   $data = $request->validate([
  //     'consultation_id' => ['required', 'integer', 'exists:consultations,id'],
  //     'medicine_id' => ['required', 'integer', 'exists:prescription_items,id'],
  //     'dosage' => ['nullable', 'string', 'max:255'],
  //     'frequency' => ['nullable', 'string', 'max:255'],
  //     'duration' => ['nullable', 'string', 'max:255'],
  //     'instructions' => ['nullable', 'string', 'max:2000'],
  //   ]);

  //   $prescription = Prescription::create($data);
  //   $prescription->load(['medicine:id,medicine_name']);

  //   return response()->json([
  //     'message' => 'Prescription created.',
  //     'data' => $prescription,
  //   ], 201);
  // }
  public function store(Request $request)
{
    $validated = $request->validate([
        'consultation_id' => ['required', 'integer', 'exists:consultations,id'],
        'medicine_id' => ['required', 'integer', 'exists:prescription_items,id'],
        'dosage' => ['nullable', 'string', 'max:255'],
        'frequency' => ['nullable', 'string', 'max:255'],
        'duration' => ['nullable', 'string', 'max:255'],
        'instructions' => ['nullable', 'string', 'max:2000'],
    ]);

    // ✅ Determine doctor_id based on logged-in user
    $user = Auth::user();

    $doctorId = null;

    if ($user->clinicstaff && $user->clinicstaff->doctor) {
        $doctorId = $user->clinicstaff->doctor->user_id;
    }

    // add doctor_id
    $validated['doctor_id'] = $doctorId;

    $prescription = Prescription::create($validated);

    $prescription->load([
        'medicine:id,medicine_name',
        'doctor.clinicStaff'
    ]);

    return response()->json([
        'message' => 'Prescription created.',
        'data' => $prescription,
    ], 201);
}

  // PUT/PATCH /prescriptions/{prescription}
  public function update(Request $request, Prescription $prescription) {
    $data = $request->validate([
      'consultation_id' => ['sometimes', 'integer', 'exists:consultations,id'],
      'medicine_id' => ['sometimes', 'integer', 'exists:prescription_items,id'],
      'dosage' => ['nullable', 'string', 'max:255'],
      'frequency' => ['nullable', 'string', 'max:255'],
      'duration' => ['nullable', 'string', 'max:255'],
      'instructions' => ['nullable', 'string', 'max:2000'],
    ]);

    $prescription->update($data);
    $prescription->load(['medicine:id,medicine_name']);

    return response()->json([
      'message' => 'Prescription updated.',
      'data' => $prescription,
    ]);
  }

  // DELETE /prescriptions/{prescription}
  public function destroy(Prescription $prescription) {
    $prescription->delete();

    return response()->json([
      'message' => 'Prescription deleted.',
    ]);
  }
}
