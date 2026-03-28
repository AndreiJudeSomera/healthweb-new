<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PatientInfoController extends Controller {
  // Display patients list
  public function index(Request $request) {
    $patients = PatientRecord::orderBy('created_at', 'desc')->get();
    if ($request->ajax()) {
      $patients = $patients->map(function ($p) {
        return [
          "pid" => $p->pid,
          "last_name" => $p->last_name,
          "first_name" => $p->first_name,
          "age" => $p->age,
          "gender" => $p->gender,
          "created_at" => $p->created_at ? $p->created_at->toDateTimeString() : null,
        ];
      });
      return response()->json($patients);
    }
    return view('patients.index');
  }

  public function show($id) {
    $patient = PatientRecord::findOrFail($id);
    return view('patients.patientinfo', compact('patient'));
  }

// Show a single patient via AJAX
  public function getData($id) {
    $patient = PatientRecord::findOrFail($id);

    $data = [
      'PatientRecord_ID' => $patient->PatientRecord_ID,
      'PID_Number' => $patient->PID_Number,
      'Lname' => $patient->Lname,
      'Fname' => $patient->Fname,
      'Mname' => $patient->Mname,
      'Age' => $patient->Age,
      'Gender' => $patient->Gender,
      'DateofBirth' => $patient->DateofBirth,
      'Nationality' => $patient->Nationality,
      'ContactNumber' => $patient->ContactNumber,
      'GuardianName' => $patient->GuardianName,
      'GuardianRelation' => $patient->GuardianRelation,
      'GuardianContact' => $patient->GuardianContact,
      'Address' => $patient->Address,
      'Allergy' => $patient->Allergy,
      'Alcohol' => $patient->Alcohol,
      'Years_of_Smoking' => $patient->Years_of_Smoking,
      'IllicitDrugUse' => $patient->IllicitDrugUse,
      'Hypertension' => $patient->Hypertension,
      'Asthma' => $patient->Asthma,
      'Diabetes' => $patient->Diabetes,
      'Cancer' => $patient->Cancer,
      'Thyroid' => $patient->Thyroid,
      'Others' => $patient->Others,
    ];

    return response()->json($data);
  }

  // Store new patient
  public function store(Request $request) {
    $request->validate([
      'Lname' => 'required|string|max:255',
      'Fname' => 'required|string|max:255',
      'Gender' => 'required|in:Male,Female',
      'DateofBirth' => 'required|date',
      'Age' => 'required|integer|min:0|max:120',
      'Nationality' => 'required|string|max:255',
      'ContactNumber' => 'required|digits:11',
      'Address' => 'required|string|max:500',
    ]);

    $dateCreated = now()->format('Ymd');
    $dayOfBirth = date('d', strtotime($request->DateofBirth));
    $lastRecord = PatientRecord::orderBy('PatientRecord_ID', 'desc')->first();
    $counter = $lastRecord ? intval(substr($lastRecord->PID_Number, -4)) + 1 : 1;

    $pid = $dateCreated . $dayOfBirth . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

    $userId = (Auth::check() && Auth::user()->role == 0) ? Auth::id() : null;

    $patient = PatientRecord::create([
      'User_ID' => $userId,
      'PID_Number' => $pid,
      'Lname' => $request->Lname,
      'Fname' => $request->Fname,
      'Mname' => $request->Mname,
      'Gender' => $request->Gender,
      'DateofBirth' => $request->DateofBirth,
      'Age' => $request->Age,
      'Nationality' => $request->Nationality,
      'ContactNumber' => $request->ContactNumber,
      'Address' => $request->Address,
      'GuardianName' => $request->GuardianName,
      'GuardianRelation' => $request->GuardianRelation,
      'GuardianContact' => $request->GuardianContact,
      'Allergy' => $request->Allergy,
      'Alcohol' => $request->Alcohol,
      'Years_of_Smoking' => $request->Years_of_Smoking,
      'IllicitDrugUse' => $request->IllicitDrugUse,
      'Hypertension' => $request->has('Hypertension'),
      'Asthma' => $request->has('Asthma'),
      'Diabetes' => $request->has('Diabetes'),
      'Cancer' => $request->has('Cancer'),
      'Thyroid' => $request->has('Thyroid'),
      'Others' => $request->Others,
    ]);

    return response()->json(['success' => true, 'patient' => $patient]);

  }

  // Update patient
  public function update(Request $request, $id) {
    $request->validate([
      'Lname' => 'required|string|max:255',
      'Fname' => 'required|string|max:255',
      'Gender' => 'required|in:Male,Female',
      'DateofBirth' => 'required|date',
      'Age' => 'required|integer|min:0|max:120',
      'Nationality' => 'required|string|max:255',
      'ContactNumber' => 'required|digits:11',
      'Address' => 'required|string|max:500',
    ]);

    $patient = PatientRecord::findOrFail($id);

    $patient->update([
      'Lname' => $request->Lname,
      'Fname' => $request->Fname,
      'Mname' => $request->Mname,
      'Gender' => $request->Gender,
      'DateofBirth' => $request->DateofBirth,
      'Age' => $request->Age,
      'Nationality' => $request->Nationality,
      'ContactNumber' => $request->ContactNumber,
      'Address' => $request->Address,
      'GuardianName' => $request->GuardianName,
      'GuardianRelation' => $request->GuardianRelation,
      'GuardianContact' => $request->GuardianContact,
      'Allergy' => $request->Allergy,
      'Alcohol' => $request->Alcohol,
      'Years_of_Smoking' => $request->Years_of_Smoking,
      'IllicitDrugUse' => $request->IllicitDrugUse,

      // Checkbox fields (boolean)
      'Hypertension' => $request->has('Hypertension'),
      'Asthma' => $request->has('Asthma'),
      'Diabetes' => $request->has('Diabetes'),
      'Cancer' => $request->has('Cancer'),
      'Thyroid' => $request->has('Thyroid'),

      'Others' => $request->Others,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Patient updated successfully.',
      'patient' => $patient,
    ]);
  }
  public function updateBasic(Request $request, $patientId) {
    $patient = PatientRecord::findOrFail($patientId);

    $patient->update($request->only([
      'Lname', 'Fname', 'Mname', 'Age', 'Gender', 'DateofBirth', 'Nationality', 'ContactNumber', 'Address',
      'GuardianName', 'GuardianRelation', 'GuardianContact',
    ]));

    return response()->json(['success' => true, 'message' => 'Patient info updated.']);
  }

  // --------------------------
  // Update Personal/Social History
  // --------------------------
//    public function updateHistory(Request $request, $id)
// {
//     // Find patient record
//     $patient = PatientRecord::findOrFail($id);

//     // Update personal/social history fields
//     $patient->Allergy        = $request->input('Allergy');
//     $patient->Alcohol        = $request->input('Alcohol');
//     $patient->Years_of_Smoking = $request->input('Years_of_Smoking');
//     $patient->IllicitDrugUse = $request->input('IllicitDrugUse');
//     $patient->Others         = $request->input('Others');

//     // Update family history checkboxes (store as boolean)
//     $familyHistoryFields = ['Hypertension','Asthma','Diabetes','Cancer','Thyroid'];
//     foreach ($familyHistoryFields as $field) {
//         $patient->$field = $request->has($field) ? 1 : 0;
//     }

//     // Save changes
//     if($patient->save()){
//         return response()->json([
//             'success' => true,
//             'message' => 'Patient history updated successfully!'
//         ]);
//     } else {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to update patient history.'
//         ]);
//     }
// }

  // Delete patient
  public function destroy($id) {
    $patient = PatientRecord::findOrFail($id);
    $patient->delete();

    return response()->json(['success' => true]);
  }

////////////////////////
//    Consultation    //
//                    //

  // List consultations for a patient
  public function listConsultations($patientId) {
    $consultations = Consultation::where('PatientRecord_ID', $patientId)
      ->orderBy('ConsultationDate', 'desc')
      ->get();

    return response()->json($consultations);
  }

  // Store new consultation
  public function storeConsultation(Request $request, $patientId) {
    $validated = $request->validate([
      'ConsultationDate' => 'required|date',
      'WT' => 'nullable|string|max:10',
      'BP' => 'nullable|string|max:10',
      'CR' => 'nullable|string|max:10',
      'RR' => 'nullable|string|max:10',
      'Temperature' => 'nullable|string|max:10',
      'SP02' => 'nullable|string|max:10',
      'History_PhysicalExam' => 'nullable|string',
      'Diagnosis' => 'nullable|string',
      'Treatment' => 'nullable|string',
    ]);

    $consultation = new Consultation();
    $consultation->PatientRecord_ID = $patientId;
    $consultation->ConsultationDate = $validated['ConsultationDate'];
    $consultation->WT = $validated['WT'] ?? null;
    $consultation->BP = $validated['BP'] ?? null;
    $consultation->CR = $validated['CR'] ?? null;
    $consultation->RR = $validated['RR'] ?? null;
    $consultation->Temperature = $validated['Temperature'] ?? null;
    $consultation->SP02 = $validated['SP02'] ?? null;
    $consultation->History_PhysicalExam = $validated['History_PhysicalExam'] ?? null;
    $consultation->Diagnosis = $validated['Diagnosis'] ?? null;
    $consultation->Treatment = $validated['Treatment'] ?? null;
    $consultation->save();

    {
      $validator = Validator::make($request->all(), [
        'ConsultationDate' => 'required|date',
        'WT' => 'nullable|string|max:10',
        'BP' => 'nullable|string|max:10',
        'CR' => 'nullable|string|max:10',
        'RR' => 'nullable|string|max:10',
        'Temperature' => 'nullable|string|max:10',
        'SP02' => 'nullable|string|max:10',
        'History_PhysicalExam' => 'nullable|string',
        'Diagnosis' => 'nullable|string',
        'Treatment' => 'nullable|string',
      ]);

      if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
      }

      User::create([
        'ConsultationDate' => $request->ConsultationDate,
        'WT' => $request->WT,
        'BP' => $request->BP,
        'CR' => $request->CR,
        'RR' => $request->RR,
        'Temperature' => $request->Temperature,
        'SP02' => $request->SP02,
        'History_PhysicalExam' => $request->History_PhysicalExam,
        'Diagnosis' => $request->Diagnosis,
        'Treatment' => $request->Treatment,
      ]);

      return response()->json(['message' => 'User account created successfully.']);
    }
    return response()->json(['message' => 'Consultation added successfully!']);
  }

  // Show single consultation
  public function showConsultation($id) {
    $consultation = Consultation::findOrFail($id);
    return response()->json($consultation);
  }

  // Update consultation
  public function updateConsultation(Request $request, $id) {
    $consultation = Consultation::findOrFail($id);

    $validated = $request->validate([
      'ConsultationDate' => 'required|date',
      'WT' => 'nullable|string|max:10',
      'BP' => 'nullable|string|max:10',
      'CR' => 'nullable|string|max:10',
      'RR' => 'nullable|string|max:10',
      'Temperature' => 'nullable|string|max:10',
      'SP02' => 'nullable|string|max:10',
      'History_PhysicalExam' => 'nullable|string',
      'Diagnosis' => 'nullable|string',
      'Treatment' => 'nullable|string',
    ]);

    $consultation->ConsultationDate = $validated['ConsultationDate'];
    $consultation->WT = $validated['WT'] ?? null;
    $consultation->BP = $validated['BP'] ?? null;
    $consultation->CR = $validated['CR'] ?? null;
    $consultation->RR = $validated['RR'] ?? null;
    $consultation->Temperature = $validated['Temperature'] ?? null;
    $consultation->SP02 = $validated['SP02'] ?? null;
    $consultation->History_PhysicalExam = $validated['History_PhysicalExam'] ?? null;
    $consultation->Diagnosis = $validated['Diagnosis'] ?? null;
    $consultation->Treatment = $validated['Treatment'] ?? null;
    $consultation->save();

    return response()->json(['success' => true, 'consultation' => $consultation]);
  }

  // Delete consultation
  public function destroyConsultation($id) {
    $consultation = Consultation::findOrFail($id);
    $consultation->delete();

    return response()->json(['success' => true]);
  }

}
