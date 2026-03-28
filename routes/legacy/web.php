<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConsultationPdfController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientInfoController;
use App\Http\Controllers\PatientRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect()->route('login');
});

Route::get('/send-test', function () {
  try {
    Mail::raw('This is a test email from Laravel', function ($message) {
      $message->to('someraandrei25@gmail.com')
        ->subject('Test Email');
    });

    return "✅ Test email has been sent!";
  } catch (\Exception $e) {
    return "❌ Error: " . $e->getMessage();
  }
});

//dashboard route
Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//profile
Route::middleware(['auth', 'role:0'])->group(function () {
  Route::get('/userdashboard', fn() => view('userdashboard.mobiledash'))
    ->name('user.dashboard');
});

Route::middleware(['auth', 'role:1'])->group(function () {
  Route::get('/secdashboard', fn() => view('dashboard'))
    ->name('secretary.dashboard');
});
Route::middleware(['auth', 'role:2'])->group(function () {
  Route::get('/superadmin-dashboard', fn() => view('dashboard'))->name('superadmin.dashboard');
});

// Dashboard redirect based on role
Route::middleware('auth')->get('/dashboard', function () {
  $role = Auth::user()->role;

  return match ($role) {
    0 => redirect()->route('userinfo.usertype'), // role 0 -> user info type
    1 => redirect()->route('secretary.dashboard'),
    2 => redirect()->route('superadmin.dashboard'),
    default => abort(403),
  };
})->name('dashboard');

// Route::middleware(['auth', 'role:0'])->post('/userinfo/choice', [UserInfoController::class, 'submitChoice'])
//     ->name('userinfo.choice');

// Role-based dashboards
Route::middleware(['auth', 'role:0'])->group(function () {
  // Patient registration
  Route::get('/userinfo/usertype', [PatientController::class, 'onboardingCheck'])->name('userinfo.usertype');
  Route::get('/userinfo/new', fn() => view('userinfo.newpatientregistration'))->name('userinfo.new');
  Route::get('/userinfo/old', fn() => view('userinfo.oldpatientregistration'))->name('userinfo.old');

  // Store new patient records
  Route::post('/userinfo/new', [PatientRecordController::class, 'store'])->name('userinfo.store');
});

Route::middleware(['auth', 'role:1'])->group(function () {
  Route::get('/secdashboard', fn() => view('dashboard'))->name('secretary.dashboard');
});

Route::middleware(['auth', 'role:2'])->group(function () {
  Route::get('/superadmin-dashboard', fn() => view('dashboard'))->name('superadmin.dashboard');
});

//route for forget password

Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password/send-otp', [ForgotPasswordOtpController::class, 'sendOtp'])->name('password.otp.send');

Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyOtp'])->name('password.otp.verify');

Route::get('/change-password', [ForgotPasswordOtpController::class, 'showChangePassword'])->name('password.change.form');
Route::post('/change-password', [ForgotPasswordOtpController::class, 'changePassword'])->name('password.change');

Route::get('/password-changed', [ForgotPasswordOtpController::class, 'success'])->name('password.changed');

//appointment

// Route::resource('appointments', AppointmentController::class);
// Route::get('/appointments/booked-times', [AppointmentController::class, 'bookedTimes']);

//patient

// (~) GARIC Routes: Patients ...
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::get('/patients/records', [PatientRecordController::class, 'index']);
Route::get('/patients/search', [PatientRecordController::class, 'search']);
Route::post('/patients', [PatientRecordController::class, 'store'])->name('patients.store');
// (~) GARIC Routes: Patients -> Bind endpoints
Route::get('/patients/old', [PatientRecordController::class, 'getOldPatients']);
Route::get('/patients/newusers', [PatientRecordController::class, 'getNewUsers']);
Route::put('/patients/bind/{id}', [PatientRecordController::class, 'bindUser']);
// (~) ... GARIC Routes: Patients
Route::put('/patients/{pid}', [PatientRecordController::class, 'update'])->name('patients.update');
Route::delete('/patients/{pid}', [PatientRecordController::class, 'destroy'])->name('patients.destroy');
Route::get('/patients/{patient:pid}', [PatientRecordController::class, 'show'])->name('patients.show');
Route::get('/patients/{patient:pid}/documents', [PatientRecordController::class, 'documentShow'])->name('patients.documentShow');

// (~) GARIC Routes: Appointments
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointments/available-slots', [AppointmentController::class, 'availableSlots'])
  ->name('appointments.availableSlots');
Route::get('/appointments/patient/{pid}', [AppointmentController::class, 'byPid'])->name('appointments.byPid');
Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

// (~) GARIC Routes: Doctors
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{id}', [DoctorController::class, 'show'])->whereNumber('id')->name('doctors.show');
Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
Route::put('/doctors/{id}', [DoctorController::class, 'update'])->whereNumber('id')->name('doctors.update');
Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->whereNumber('id')->name('doctors.destroy');

// (~) GARIC Routes: Certificates
Route::get('/appointments/{id}/medical-certificate', [CertificateController::class, 'medicalCertificate'])
  ->name('appointments.medical-certificate');
Route::get('/appointments/{id}/referral-letter', [CertificateController::class, 'referralLetter'])->name('appointments.referral-letter');
Route::get('/appointments/{id}/prescription', [CertificateController::class, 'prescription'])->name('appointments.prescription');

// (~) GARIC Routes: Consultations / Patient Documents
Route::get('/consultations', [ConsultationController::class, 'index']);
Route::post('/consultations', [ConsultationController::class, 'store']);
Route::get('/consultations/{id}', [ConsultationController::class, 'byId']);
Route::put('/consultations/{id}', [ConsultationController::class, 'update']);
Route::delete('/consultations/{id}', [ConsultationController::class, 'destroy']);
Route::get('/consultations/patient/{pid}', [ConsultationController::class, 'byPid']);

// (~) GARIC Routes: Certificates v2
Route::get('/consultations/{id}/prescription', [ConsultationPdfController::class, 'prescription']);
Route::get('/consultations/{id}/referral-letter', [ConsultationPdfController::class, 'referralLetter']);
Route::get('/consultations/{id}/medical-certificate', [ConsultationPdfController::class, 'medicalCertificate']);

// (~) GARIC Routes: Patient onboarding
Route::get('/onboarding/old', [PatientController::class, 'onboardingOldPatient'])->name('onboarding.old');
Route::get('/onboarding/new', [PatientController::class, 'onboardingNewPatient'])->name('onboarding.new');

// (~) GARIC Routes: Patient (role) routes
Route::get('/p/dashboard', [PatientController::class, 'dashboardPatientRole'])->name('dashboard.patient');
Route::post('/p/bindrecord', [PatientController::class, 'bindRecord']);
Route::post('/p/createrecord', [PatientController::class, 'storeNewPatientRecord']);
Route::get('/p/appointments', [PatientController::class, 'appointmentsPatientShow'])->name('appointments.patient');
Route::get('/p/records', [PatientController::class, 'recordsPatientShow'])->name('records.patient');

// (~) GARIC Routes: Patient (role) routes -> Messages
Route::middleware('auth')->group(function () {
  Route::get('/p/messages', [App\Http\Controllers\MessageController::class, 'indexPatientView'])->name('messages.patient');
  Route::get('/messages/{id}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
  Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

// Route::get('/patients', [PatientInfoController::class, 'index'])->name('patients.index');
// Route::get('/patients/list', [PatientInfoController::class, 'list'])->name('patients.index'); // for AJAX
// Route::get('/patients/{id}', [PatientInfoController::class, 'show'])->name('patients.show');
// Route::post('/patients', [PatientInfoController::class, 'store'])->name('patients.store');
// Route::put('/patients/{id}', [PatientInfoController::class, 'update'])->name('patients.update');
// Route::delete('/patients/{id}', [PatientInfoController::class, 'destroy'])->name('patients.destroy');
// Route::get('/patients/{id}/data', [PatientInfoController::class, 'getData'])->name('patients.data');

// Route::put('/patients/{patient}/update-basic', [PatientInfoController::class, 'updateBasic'])->name('patients.update-basic');
// Route::put('/patients/{id}/update-history', [PatientInfoController::class, 'updateHistory'])->name('patients.updateHistory');
// Route::get('/patients/{id}/data', [PatientInfoController::class, 'getData']);

// Route::get('/patients/list', [PatientInfoController::class, 'list'])->name('patients.list');

// -------------------------
// Consultation routes (AJAX)
// -------------------------
// If using PatientInfoController for consultations
Route::get('/patients/{patientId}/consultations', [PatientInfoController::class, 'listConsultations'])->name('consultations.list');
Route::post('/patients/{patientId}/consultations/store', [PatientInfoController::class, 'storeConsultation'])->name('consultations.store');
Route::get('/consultations/{id}', [PatientInfoController::class, 'showConsultation'])->name('consultations.show');
Route::put('/consultations/{id}/update', [PatientInfoController::class, 'updateConsultation'])->name('consultations.update');
Route::delete('/consultations/{id}/delete', [PatientInfoController::class, 'destroyConsultation'])->name('consultations.destroy');

Route::prefix('accounts')->group(function () {
  Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
  Route::get('/list', [AccountController::class, 'list'])->name('accounts.list');
  Route::post('/store', [AccountController::class, 'store'])->name('accounts.store');
  Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
  Route::put('/{id}', [AccountController::class, 'update'])->name('accounts.update');
  Route::delete('/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

  Route::post('/accounts/patient-info', [AccountController::class, 'storePatientInfo'])
    ->name('accounts.patient.store');

  Route::post('/accounts/secretary-info', [AccountController::class, 'storeSecretaryInfo'])
    ->name('accounts.secretary.store');

});

Route::get('/addpatient', function () {
  return view('patients.addpatient');
});
Route::get('/patientinfo', function () {
  return view('patients.patientinfo');
});
Route::get('/forms', function () {
  return view('patients.forms');
});

//appointment
Route::get('/viewappointment', function () {
  return view('appointments.viewappointment');
});

Route::get('/currentappointment', function () {
  return view('appointments.currentappointment');
});

Route::get('/appointmentform', function () {
  return view('appointments.appointmentform');
});

//mmessages - backend controller routes
Route::middleware('auth')->group(function () {
  Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
  Route::get('/messages/{id}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
  Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

// Legacy admin messages view kept in place at /message for backward compatibility
Route::get('/message', function () {return view('messages.message');});

//reports
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.reports');

//accounts
Route::get('/viewaccounts', function () {
  return view('accounts.viewaccounts  ');
});

//accounts
Route::get('/settings', function () {
  return view('settings.settings  ');
});

//userinfo
Route::post('/patient/register', [PatientController::class, 'store'])->name('patient.register');

// Pagkatapos ma-submit, dito siya ireredirect
// routes/web.php

//patientrecords
//Route::post('/userinfo/new', [PatientRecordController::class, 'store'])->name('userinfo.store');

// patient records

//user messages (mobile) - handled by MessageController userIndex
Route::middleware('auth')->get('/usermessages', [App\Http\Controllers\MessageController::class, 'userIndex'])->name('usermessages.index');

// API endpoints for AJAX-driven messaging UI
Route::middleware('auth')->prefix('api')->group(function () {
  Route::get('/users/search', [App\Http\Controllers\MessageController::class, 'searchUsers']);
  Route::get('/conversations', [App\Http\Controllers\MessageController::class, 'apiConversations']);
  Route::get('/conversations/{id}/messages', [App\Http\Controllers\MessageController::class, 'apiConversationMessages']);
  Route::delete('/conversations/{id}/messages', [App\Http\Controllers\MessageController::class, 'apiDeleteConversationMessages']);

});

// Dedicated secretaries URL (redirect to messages index)
Route::middleware(['auth', 'role:1'])->get('/secmessages', function () {
  return redirect()->route('messages.index');
})->name('secretary.messages');

//usersettings
Route::get('/usersettings', function () {
  return view('usersettings.mobilesettings');

});
//mobiledashboard
// Route::get('/userdashboard', function () {
//     return view('userdashboard.mobiledash');

// });

Route::get('/userrecord', function () {
  return view('userrecord.mobilerecord');

});

require __DIR__ . '/auth.php';
