<?php

namespace App\Http\Controllers;

use App\Models\ClinicStaff;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class AccountController extends Controller {
  public function index() {
    return view('accounts.viewaccounts');
  }

  public function list() {
    return response()->json(User::orderBy('id', 'desc')->get());
  }

  // public function store(Request $request) {
  //   $validator = Validator::make($request->all(), [
  //     'role' => 'required|in:0,1,2',
  //     'email' => 'required|email|unique:users,email',
  //     'username' => 'required|string|unique:users,username',
  //     'password' => 'required|string|min:6|confirmed',
  //   ]);

  //   if ($validator->fails()) {
  //     return response()->json(['errors' => $validator->errors()], 422);
  //   }

  //   User::create([
  //     'role' => $request->role,
  //     'email' => $request->email,
  //     'username' => $request->username,
  //     'password' => Hash::make($request->password),
  //   ]);

  //   return response()->json(['message' => 'User account created successfully.']);
  // }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'role' => 'required|in:0,1,2',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|string|unique:users,username',
        'password' => 'required|string|min:6|confirmed',

        'patient_type' => 'nullable|in:new,old',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();

    try {

        // 1️⃣ create user
        $user = User::create([
            'role' => $request->role,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // 2️⃣ if role = patient create patient row
        if ($request->role == 0) {

            Patient::create([
                'user_id' => $user->id, // same id as users table
                'patient_type' => $request->patient_type,
            ]);

        }

        DB::commit();

        return response()->json([
            'message' => 'User created successfully.'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'message' => 'Failed to create user.'
        ], 500);
    }
}

  public function edit($id) {
    $user = User::findOrFail($id);

    return response()->json([
      'id' => $user->id,
      'email' => $user->email,
      'username' => $user->username,
      'role' => $user->role,
      'is_active' => $user->is_active,
    ]);
  }

  public function update(Request $request, $id) {
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'role' => 'required|in:0,1,2',
      'email' => 'required|email|unique:users,email,' . $id,
      'username' => 'required|string|unique:users,username,' . $id,
      'password' => 'nullable|string|min:6|confirmed',
    ]);

    
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $user->role = $request->role;
    $user->email = $request->email;
    $user->username = $request->username;
    $user->is_active = $request->boolean('is_active');

    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json(['message' => 'User account updated successfully.']);
  }

  public function destroy($id) {
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully.']);
  }

  public function getPatientInfo($id) {
    $patient = Patient::with('record')->where('user_id', $id)->first();
    if (!$patient || !$patient->record) {
      return response()->json(null);
    }

    $r = $patient->record;
    $fh = [];
    if ($r->hypertension) $fh[] = 'Hypertension';
    if ($r->asthma)       $fh[] = 'Asthma';
    if ($r->diabetes)     $fh[] = 'Diabetes';
    if ($r->cancer)       $fh[] = 'Cancer';
    if ($r->thyroid)      $fh[] = 'Thyroid';

    return response()->json([
      'Fname'                => $r->first_name,
      'Lname'                => $r->last_name,
      'Mname'                => $r->middle_name,
      'Gender'               => $r->gender,
      'DateofBirth'          => $r->date_of_birth?->format('Y-m-d'),
      'Nationality'          => $r->nationality,
      'ContactNumber'        => $r->contact_number,
      'Address'              => $r->address,
      'GuardianName'         => $r->guardian_name,
      'GuardianRelation'     => $r->guardian_relation,
      'GuardianContact'      => $r->guardian_contact,
      'Allergy'              => $r->allergy,
      'Alcohol'              => $r->alcohol,
      'Years_of_Smoking'     => $r->years_of_smoking,
      'IllicitDrugUse'       => $r->illicit_drug_use,
      'family_history'       => $fh,
      'family_history_other' => $r->others,
    ]);
  }

  // public function updatePatientInfo(Request $request, $id) {
  //   $patient = Patient::with('record')->where('user_id', $id)->firstOrFail();

  //   $validator = Validator::make($request->all(), [
  //     'Fname'       => 'required|string',
  //     'Lname'       => 'required|string',
  //     'DateofBirth' => 'required|date',
  //   ]);

  //   if ($validator->fails()) {
  //     return response()->json(['errors' => $validator->errors()], 422);
  //   }

  //   $familyHistory = $request->input('family_history', []);

  //   $patient->record->update([
  //     'first_name'        => $request->Fname,
  //     'last_name'         => $request->Lname,
  //     'middle_name'       => $request->Mname,
  //     'gender'            => $request->Gender,
  //     'date_of_birth'     => $request->DateofBirth,
  //     'nationality'       => $request->Nationality,
  //     'contact_number'    => $request->ContactNumber,
  //     'address'           => $request->Address,
  //     'guardian_name'     => $request->GuardianName,
  //     'guardian_relation' => $request->GuardianRelation,
  //     'guardian_contact'  => $request->GuardianContact,
  //     'allergy'           => $request->input('Allergy', 'None'),
  //     'alcohol'           => $request->input('Alcohol', 'None'),
  //     'years_of_smoking'  => $request->Years_of_Smoking ?: 0,
  //     'illicit_drug_use'  => $request->IllicitDrugUse,
  //     'hypertension'      => in_array('Hypertension', $familyHistory),
  //     'asthma'            => in_array('Asthma', $familyHistory),
  //     'diabetes'          => in_array('Diabetes', $familyHistory),
  //     'cancer'            => in_array('Cancer', $familyHistory),
  //     'thyroid'           => in_array('Thyroid', $familyHistory),
  //     'others'            => $request->family_history_other,
  //   ]);

  //   return response()->json(['message' => 'Patient information updated successfully.']);
  // }

//   public function updatePatientInfo(Request $request, $id) {
//     // Get patient first
//     $patient = Patient::where('user_id', $id)->firstOrFail();

//     // ❗ Prevent old patients from creating new record if not bound
//       if ($patient->patient_type === 'old' && is_null($patient->record_id)) {
//           return response()->json([
//               'message' => 'This account already has an existing Clinical record. Please bind record'
//           ], 403);
//       }

//     // Validate input
//     $validator = Validator::make($request->all(), [
//         'Fname'       => 'required|string',
//         'Lname'       => 'required|string',
//         'DateofBirth' => 'required|date',
//     ]);

  
//     // Stop if validation fails
//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     $familyHistory = $request->input('family_history', []);

//     // Check if record exists, if not create one
//     if (!$patient->record) {
//         $record = PatientRecord::create([
//         'first_name'        => $request->Fname,
//         'last_name'         => $request->Lname,
//         'middle_name'       => $request->Mname,
//         'gender'            => $request->Gender,
//         'date_of_birth'     => $request->DateofBirth,
//         'nationality'       => $request->Nationality,
//         'contact_number'    => $request->ContactNumber,
//         'address'           => $request->Address,
//         'guardian_name'     => $request->GuardianName,
//         'guardian_relation' => $request->GuardianRelation,
//         'guardian_contact'  => $request->GuardianContact,
//         'allergy'           => $request->input('Allergy', 'None'),
//         'alcohol'           => $request->input('Alcohol', 'None'),
//         'years_of_smoking'  => $request->Years_of_Smoking ?: 0,
//         'illicit_drug_use'  => $request->IllicitDrugUse,
//         'hypertension'      => in_array('Hypertension', $familyHistory),
//         'asthma'            => in_array('Asthma', $familyHistory),
//         'diabetes'          => in_array('Diabetes', $familyHistory),
//         'cancer'            => in_array('Cancer', $familyHistory),
//         'thyroid'           => in_array('Thyroid', $familyHistory),
//         'others'            => $request->family_history_other,
            
//             // you can leave other fields empty or default for now
//         ]);
//         $patient->record_id = $record->id;
//         $patient->save();
//     } else {
//         $record = $patient->record;
//     }

//     // Update the record
//     $record->update([
//         'first_name'        => $request->Fname,
//         'last_name'         => $request->Lname,
//         'middle_name'       => $request->Mname,
//         'gender'            => $request->Gender,
//         'date_of_birth'     => $request->DateofBirth,
//         'nationality'       => $request->Nationality,
//         'contact_number'    => $request->ContactNumber,
//         'address'           => $request->Address,
//         'guardian_name'     => $request->GuardianName,
//         'guardian_relation' => $request->GuardianRelation,
//         'guardian_contact'  => $request->GuardianContact,
//         'allergy'           => $request->input('Allergy', 'None'),
//         'alcohol'           => $request->input('Alcohol', 'None'),
//         'years_of_smoking'  => $request->Years_of_Smoking ?: 0,
//         'illicit_drug_use'  => $request->IllicitDrugUse,
//         'hypertension'      => in_array('Hypertension', $familyHistory),
//         'asthma'            => in_array('Asthma', $familyHistory),
//         'diabetes'          => in_array('Diabetes', $familyHistory),
//         'cancer'            => in_array('Cancer', $familyHistory),
//         'thyroid'           => in_array('Thyroid', $familyHistory),
//         'others'            => $request->family_history_other,
//     ]);

//     return response()->json(['message' => 'Patient information updated successfully.']);
// }


public function updatePatientInfo(Request $request, $id)
{
    $patient = Patient::where('user_id', $id)->firstOrFail();

    // ❗ Prevent old patients from creating new record if not bound
    if ($patient->patient_type === 'old' && is_null($patient->record_id)) {
        return response()->json([
            'message' => 'This account already has an existing Clinical record. Please bind record'
        ], 403);
    }

    // Validate input
    $validator = Validator::make($request->all(), [
        'Fname'       => 'required|string',
        'Lname'       => 'required|string',
        'DateofBirth' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $familyHistory = $request->input('family_history', []);

    /*
    |--------------------------------------------------------------------------
    | SENTENCE CASE NORMALIZATION
    |--------------------------------------------------------------------------
    */

    $firstName = $this->toSentenceCase($request->Fname);
    $lastName  = $this->toSentenceCase($request->Lname);
    $middleName = $request->Mname ? $this->toSentenceCase($request->Mname) : null;

    /*
    |--------------------------------------------------------------------------
    | DUPLICATE CHECK (case-insensitive)
    |--------------------------------------------------------------------------
    */

    $duplicate = PatientRecord::whereDate('date_of_birth', $request->DateofBirth)
        ->get()
        ->first(function ($record) use ($firstName, $lastName, $patient) {
            return $record->id !== optional($patient->record)->id &&
                mb_strtolower(trim($record->first_name)) === mb_strtolower(trim($firstName)) &&
                mb_strtolower(trim($record->last_name)) === mb_strtolower(trim($lastName));
        });

    if ($duplicate) {
        return response()->json([
            'message' => 'Patient record already exists.'
        ], 409);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE OR UPDATE RECORD
    |--------------------------------------------------------------------------
    */

    if (!$patient->record) {
        $record = PatientRecord::create([
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'middle_name'       => $middleName,
            'gender'            => $request->Gender,
            'date_of_birth'     => $request->DateofBirth,
            'nationality'       => $request->Nationality,
            'contact_number'    => $request->ContactNumber,
            'address'           => $request->Address,
            'guardian_name'     => $request->GuardianName,
            'guardian_relation' => $request->GuardianRelation,
            'guardian_contact'  => $request->GuardianContact,
            'allergy'           => $request->input('Allergy', 'None'),
            'alcohol'           => $request->input('Alcohol', 'None'),
            'years_of_smoking'  => $request->Years_of_Smoking ?: 0,
            'illicit_drug_use'  => $request->IllicitDrugUse,
            'hypertension'      => in_array('Hypertension', $familyHistory),
            'asthma'            => in_array('Asthma', $familyHistory),
            'diabetes'          => in_array('Diabetes', $familyHistory),
            'cancer'            => in_array('Cancer', $familyHistory),
            'thyroid'           => in_array('Thyroid', $familyHistory),
            'others'            => $request->family_history_other,
        ]);

        $patient->record_id = $record->id;
        $patient->save();

    } else {
        $record = $patient->record;

        $record->update([
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'middle_name'       => $middleName,
            'gender'            => $request->Gender,
            'date_of_birth'     => $request->DateofBirth,
            'nationality'       => $request->Nationality,
            'contact_number'    => $request->ContactNumber,
            'address'           => $request->Address,
            'guardian_name'     => $request->GuardianName,
            'guardian_relation' => $request->GuardianRelation,
            'guardian_contact'  => $request->GuardianContact,
            'allergy'           => $request->input('Allergy', 'None'),
            'alcohol'           => $request->input('Alcohol', 'None'),
            'years_of_smoking'  => $request->Years_of_Smoking ?: 0,
            'illicit_drug_use'  => $request->IllicitDrugUse,
            'hypertension'      => in_array('Hypertension', $familyHistory),
            'asthma'            => in_array('Asthma', $familyHistory),
            'diabetes'          => in_array('Diabetes', $familyHistory),
            'cancer'            => in_array('Cancer', $familyHistory),
            'thyroid'           => in_array('Thyroid', $familyHistory),
            'others'            => $request->family_history_other,
        ]);
    }

    return response()->json([
        'message' => 'Patient information updated successfully.'
    ]);
}

private function toSentenceCase($value)
{
    if (!$value) return $value;

    return collect(explode(' ', trim($value)))
        ->map(fn($word) => ucfirst(strtolower($word)))
        ->implode(' ');
}

  public function getSecretaryInfo($id) {
    $staff = ClinicStaff::where('user_id', $id)->first();
    if (!$staff) {
      return response()->json(null);
    }

    $secretary = Secretary::where('user_id', $id)->first();

    return response()->json([
      'Fname'         => $staff->Fname,
      'Lname'         => $staff->Lname,
      'Mname'         => $staff->Mname,
      'DateofBirth'   => $staff->DateofBirth,
      'Gender'        => $staff->Gender,
      'ContactNumber' => $staff->ContactNumber,
      'Address'       => $staff->Address,
      'SecAssignedID' => $secretary?->SecAssignedID,
    ]);
  }

  public function updateSecretaryInfo(Request $request, $id) {
    $validator = Validator::make($request->all(), [
      'Fname'       => 'required|string',
      'Lname'       => 'required|string',
      'DateofBirth' => 'required|date',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::transaction(function () use ($request, $id) {
      ClinicStaff::where('user_id', $id)->update([
        'Fname'         => $request->Fname,
        'Lname'         => $request->Lname,
        'Mname'         => $request->Mname,
        'DateofBirth'   => $request->DateofBirth,
        'Gender'        => $request->Gender,
        'ContactNumber' => $request->ContactNumber,
        'Address'       => $request->Address,
      ]);

      Secretary::where('user_id', $id)->update([
        'SecAssignedID' => $request->SecAssignedID,
      ]);
    });

    return response()->json(['message' => 'Secretary information updated successfully.']);
  }

  public function storePatientInfo(Request
   $request) {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'Fname' => 'required|string',
      'Lname' => 'required|string',
      'DateofBirth' => 'required|date',
    ]);

     
    $familyHistory = $request->input('family_history', []);

    DB::transaction(function () use ($request, $familyHistory) {
      $record = PatientRecord::create([
        'first_name' => $request->Fname,
        'last_name' => $request->Lname,
        'middle_name' => $request->Mname,
        'gender' => $request->Gender,
        'date_of_birth' => $request->DateofBirth,
        'nationality' => $request->Nationality,
        'contact_number' => $request->ContactNumber,
        'address' => $request->Address,
        'guardian_name' => $request->GuardianName,
        'guardian_relation' => $request->GuardianRelation,
        'guardian_contact' => $request->GuardianContact,
        'allergy' => $request->input('Allergy', 'None'),
        'alcohol' => $request->input('Alcohol', 'None'),
        'years_of_smoking' => $request->Years_of_Smoking ?: 0,
        'illicit_drug_use' => $request->IllicitDrugUse,
        'hypertension' => in_array('Hypertension', $familyHistory),
        'asthma' => in_array('Asthma', $familyHistory),
        'diabetes' => in_array('Diabetes', $familyHistory),
        'cancer' => in_array('Cancer', $familyHistory),
        'thyroid' => in_array('Thyroid', $familyHistory),
        'others' => $request->family_history_other,
        'patient_type' => 'new',
        'is_bound' => true,
      ]);

      Patient::create([
        'user_id' => $request->user_id,
        'patient_type' => 'new',
        'record_id' => $record->id,
      ]);
    });

    return response()->json(['message' => 'Patient information saved successfully.']);
  }

  public function storeSecretaryInfo(Request $request) {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'Fname' => 'required|string',
      'Lname' => 'required|string',
      'DateofBirth' => 'required|date',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::transaction(function () use ($request) {
      ClinicStaff::create([
        'user_id' => $request->user_id,
        'Fname' => $request->Fname,
        'Lname' => $request->Lname,
        'Mname' => $request->Mname,
        'DateofBirth' => $request->DateofBirth,
        'Age' => $request->Age,
        'Gender' => $request->Gender,
        'ContactNumber' => $request->ContactNumber,
        'Address' => $request->Address,
      ]);

      Secretary::create([
        'user_id' => $request->user_id,
        'SecAssignedID' => $request->SecAssignedID,
      ]);
    });

    return response()->json(['message' => 'Secretary information saved successfully.']);
  }

  public function getDoctorInfo($id) {
    $doctor = Doctor::where('user_id', $id)->first();
    if (!$doctor) {
      return response()->json(null);
    }

    $staff = ClinicStaff::where('user_id', $id)->first();

    return response()->json([
      'Fname'         => $staff?->Fname,
      'Lname'         => $staff?->Lname,
      'Mname'         => $staff?->Mname,
      'DateofBirth'   => $staff?->DateofBirth,
      'Gender'        => $staff?->Gender,
      'ContactNumber' => $staff?->ContactNumber,
      'Address'       => $staff?->Address,
      'dr_license_no' => $doctor->dr_license_no,
      'ptr_no'        => $doctor->ptr_no,
    ]);
  }

  public function storeDoctorInfo(Request $request) {
    $validator = Validator::make($request->all(), [
      'user_id'       => 'required|exists:users,id',
      'Fname'         => 'required|string',
      'Lname'         => 'required|string',
      'dr_license_no' => 'required|string|max:50',
      'ptr_no'        => 'required|string|max:50',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::transaction(function () use ($request) {
      ClinicStaff::updateOrCreate(
        ['user_id' => $request->user_id],
        [
          'Fname'         => $request->Fname,
          'Lname'         => $request->Lname,
          'Mname'         => $request->Mname,
          'DateofBirth'   => $request->DateofBirth ?: null,
          'Gender'        => $request->Gender ?: 'Male',
          'ContactNumber' => $request->ContactNumber,
          'Address'       => $request->Address,
        ]
      );

      Doctor::create([
        'user_id'       => $request->user_id,
        'dr_license_no' => $request->dr_license_no,
        'ptr_no'        => $request->ptr_no,
      ]);
    });

    return response()->json(['message' => 'Doctor information saved successfully.']);
  }

  public function updateDoctorInfo(Request $request, $id) {
    $validator = Validator::make($request->all(), [
      'Fname'         => 'required|string',
      'Lname'         => 'required|string',
      'dr_license_no' => 'required|string|max:50',
      'ptr_no'        => 'required|string|max:50',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::transaction(function () use ($request, $id) {
      ClinicStaff::updateOrCreate(
        ['user_id' => $id],
        [
          'Fname'         => $request->Fname,
          'Lname'         => $request->Lname,
          'Mname'         => $request->Mname,
          'DateofBirth'   => $request->DateofBirth ?: null,
          'Gender'        => $request->Gender ?: 'Male',
          'ContactNumber' => $request->ContactNumber,
          'Address'       => $request->Address,
        ]
      );

      Doctor::updateOrCreate(
        ['user_id' => $id],
        [
          'dr_license_no' => $request->dr_license_no,
          'ptr_no'        => $request->ptr_no,
        ]
      );
    });

    return response()->json(['message' => 'Doctor information updated successfully.']);
  }
}
