<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller {
  public function index() {
    // Only return what the frontend needs for dropdowns / lists
    $doctors = Doctor::query()
      ->with('user:id,username')
      ->latest('created_at')
      ->get(['user_id']);

    return response()->json($doctors);
  }

  public function show(int $userId) {
    $doctor = Doctor::query()
      ->with('user:id,username')
      ->find($userId);

    if (!$doctor) {
      return response()->json(['message' => 'Doctor not found'], 404);
    }

    return response()->json($doctor);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'user_id' => ['required', 'integer', 'exists:users,id'],
      'dr_license_no' => ['required', 'string', 'max:50'],
      'ptr_no' => ['required', 'string', 'max:50'],
    ]);

    $doctor = Doctor::create($validated);

    // return with user for convenience
    $doctor->load('user:id,username');

    return response()->json([
      'message' => 'Doctor added!',
      'data' => $doctor,
    ], 201);
  }

  public function update(Request $request, int $userId) {
    $doctor = Doctor::find($userId);

    if (!$doctor) {
      return response()->json(['message' => 'Doctor not found'], 404);
    }

    $validated = $request->validate([
      'dr_license_no' => ['sometimes', 'string', 'max:50'],
      'ptr_no' => ['sometimes', 'string', 'max:50'],
    ]);

    $doctor->update($validated);
    $doctor->load('user:id,username');

    return response()->json([
      'message' => 'Doctor updated!',
      'data' => $doctor,
    ]);
  }

  public function destroy(int $userId) {
    $doctor = Doctor::find($userId);

    if (!$doctor) {
      return response()->json(['message' => 'Doctor not found'], 404);
    }

    $doctor->delete();

    return response()->json(['message' => 'Doctor deleted!']);
  }
}
