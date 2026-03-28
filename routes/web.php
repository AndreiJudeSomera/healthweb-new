<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ClinicScheduleController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConsultationPdfController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientRecordController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PrescriptionItemsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root: redirect to login
Route::get('/', fn() => redirect()->route('login'));

// Public: Forgot password (OTP)
Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password/send-otp', [ForgotPasswordOtpController::class, 'sendOtp'])->name('password.otp.send');
Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/change-password', [ForgotPasswordOtpController::class, 'showChangePassword'])->name('password.change.form');
Route::post('/change-password', [ForgotPasswordOtpController::class, 'changePassword'])->name('password.change');
Route::get('/password-changed', [ForgotPasswordOtpController::class, 'success'])->name('password.changed');

// Auth: common
Route::middleware('auth')->group(function () {

  // Profile: all roles
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // Notifications: all authenticated users
  Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('readAll');
    Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
  });

  // Messages: all authenticated users
  Route::get('/messages/unread', [MessageController::class, 'unread'])->name('messages.unread');
  Route::post('/messages/{id}/read', [MessageController::class, 'markMessageRead'])->whereNumber('id')->name('messages.markRead');
  Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

  Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
  Route::get('/appointments/available-slots', [AppointmentController::class, 'availableSlots'])->name('appointments.availableSlots');

  // Dashboard: role redirect
  Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
      0 => redirect()->route('patient.onboarding.usertype'),
      1 => redirect()->route('secretary.dashboard'),
      2 => redirect()->route('superadmin.dashboard'),
      default => abort(403),
    };
  })->name('dashboard');
});

// Patient: role 0 — onboarding (no record required)
Route::middleware(['auth', 'role:0'])->prefix('p')->name('patient.')->group(function () {
  Route::get('/onboarding/usertype', [PatientController::class, 'onboardingCheck'])->name('onboarding.usertype');
  Route::get('/onboarding/old', [PatientController::class, 'onboardingOldPatient'])->name('onboarding.old');
  Route::get('/onboarding/new', [PatientController::class, 'onboardingNewPatient'])->name('onboarding.new');
  Route::post('/onboarding/new', [PatientRecordController::class, 'storeNewPatientRecord'])->name('onboarding.store');
  Route::post('/bindrecord', [PatientController::class, 'bindRecord'])->name('bindrecord');
  Route::post('/createrecord', [PatientController::class, 'storeNewPatientRecord'])->name('createrecord');
});
Route::get('/account-created', function () {
    return view('auth.account-created');
})->name('account.created');

// Patient: role 0 — authenticated pages (record required)
Route::middleware(['auth', 'role:0', 'patient.record'])->prefix('p')->name('patient.')->group(function () {
  Route::get('/dashboard', [PatientController::class, 'dashboardPatientRole'])->name('dashboard');
  Route::get('/appointments', [PatientController::class, 'appointmentsPatientShow'])->name('appointments');
  Route::get('/records', [PatientController::class, 'recordsPatientShow'])->name('records');
  Route::get('/messages', [MessageController::class, 'indexPatientView'])->name('messages');
});

// Secretary: role 1
Route::middleware(['auth', 'role:1'])->group(function () {
  Route::get('/secdashboard', [DashboardController::class, 'staff'])->name('secretary.dashboard');

  // Secretary: messages shortcut
  Route::get('/secmessages', fn() => redirect()->route('messages.index'))->name('secretary.messages');
});

// Superadmin: role 2
Route::middleware(['auth', 'role:2'])->group(function () {

  Route::get('/superadmin-dashboard', [DashboardController::class, 'staff'])->name('superadmin.dashboard');


  // Audit Logs: superadmin only
  Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
  Route::get('/audit-logs/data', [AuditLogController::class, 'data'])->name('audit.data');

  // Accounts: superadmin only
  Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/list', [AccountController::class, 'list'])->name('list');
    Route::post('/', [AccountController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AccountController::class, 'edit'])->whereNumber('id')->name('edit');
    Route::put('/{id}', [AccountController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->whereNumber('id')->name('destroy');

    Route::post('/patient-info', [AccountController::class, 'storePatientInfo'])->name('patient.store');
    Route::get('/{id}/patient-info', [AccountController::class, 'getPatientInfo'])->whereNumber('id')->name('patient.show');
    Route::put('/{id}/patient-info', [AccountController::class, 'updatePatientInfo'])->whereNumber('id')->name('patient.update');

// Save patient info (create or update)
// Route::post('/accounts/patient-info', [AccountController::class, 'savePatientInfo'])
//     ->name('accounts.patient.save');
    
    Route::post('/secretary-info', [AccountController::class, 'storeSecretaryInfo'])->name('secretary.store');
    Route::get('/{id}/secretary-info', [AccountController::class, 'getSecretaryInfo'])->whereNumber('id')->name('secretary.show');
    Route::put('/{id}/secretary-info', [AccountController::class, 'updateSecretaryInfo'])->whereNumber('id')->name('secretary.update');

    Route::post('/doctor-info', [AccountController::class, 'storeDoctorInfo'])->name('doctor.store');
    Route::get('/{id}/doctor-info', [AccountController::class, 'getDoctorInfo'])->whereNumber('id')->name('doctor.show');
    Route::put('/{id}/doctor-info', [AccountController::class, 'updateDoctorInfo'])->whereNumber('id')->name('doctor.update');
  });
});



// Superadmin: role 1 and 2
// Route::middleware(['auth', 'role:1'])->group(function () {

//   // Accounts: superadmin only
//   Route::prefix('accounts')->name('accounts.')->group(function () {
//     Route::get('/', [AccountController::class, 'index'])->name('index');
//     Route::get('/list', [AccountController::class, 'list'])->name('list');
//     Route::post('/', [AccountController::class, 'store'])->name('store');
//     Route::get('/{id}/edit', [AccountController::class, 'edit'])->whereNumber('id')->name('edit');
//     Route::put('/{id}', [AccountController::class, 'update'])->whereNumber('id')->name('update');
//     Route::delete('/{id}', [AccountController::class, 'destroy'])->whereNumber('id')->name('destroy');

//     Route::post('/patient-info', [AccountController::class, 'storePatientInfo'])->name('patient.store');
//     Route::get('/{id}/patient-info', [AccountController::class, 'getPatientInfo'])->whereNumber('id')->name('patient.show');
//     Route::put('/{id}/patient-info', [AccountController::class, 'updatePatientInfo'])->whereNumber('id')->name('patient.update');

//   });
// });


  Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

Route::middleware(['auth'])->group(function () {

    Route::get('/consultations/{id}/consultation',
        [ConsultationPdfController::class, 'consultationDocument']
    )->whereNumber('id');

     Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->whereNumber('id')->name('appointments.destroy');
});
// Staff: secretary + superadmin (GARIC ops)
Route::middleware(['auth', 'role:1,2'])->group(function () {


 Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/list', [AccountController::class, 'list'])->name('list');
    Route::post('/', [AccountController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AccountController::class, 'edit'])->whereNumber('id')->name('edit');
    Route::put('/{id}', [AccountController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->whereNumber('id')->name('destroy');

    Route::post('/patient-info', [AccountController::class, 'storePatientInfo'])->name('patient.store');
    Route::get('/{id}/patient-info', [AccountController::class, 'getPatientInfo'])->whereNumber('id')->name('patient.show');
    Route::put('/{id}/patient-info', [AccountController::class, 'updatePatientInfo'])->whereNumber('id')->name('patient.update');

  });
  // Patients: management

  Route::get('/patients/records', [PatientRecordController::class, 'index'])->name('patients.records');
  Route::get('/patients/search', [PatientRecordController::class, 'search'])->name('patients.search');
  Route::post('/patients', [PatientRecordController::class, 'store'])->name('patients.store');

  Route::get('/patients/old', [PatientRecordController::class, 'getOldPatients'])->name('patients.old');
  Route::get('/patients/newusers', [PatientRecordController::class, 'getNewUsers'])->name('patients.newusers');
  Route::put('/patients/bind/{id}', [PatientRecordController::class, 'bindUser'])->whereNumber('id')->name('patients.bind');

  Route::put('/patients/{pid}', [PatientRecordController::class, 'update'])->name('patients.update');
  Route::delete('/patients/{pid}', [PatientRecordController::class, 'destroy'])->name('patients.destroy');
  Route::get('/patients/{patient:pid}', [PatientRecordController::class, 'show'])->name('patients.show');
  Route::get('/patients/{patient:pid}/documents', [PatientRecordController::class, 'documentShow'])->name('patients.documents');

  // Appointments
  Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
  Route::get('/appointments/queue', [AppointmentController::class, 'queue'])->name('appointments.queue');
  // Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
  // Route::get('/appointments/available-slots', [AppointmentController::class, 'availableSlots'])->name('appointments.availableSlots');
  Route::get('/appointments/patient/{pid}', [AppointmentController::class, 'byPid'])->name('appointments.byPid');

  Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->whereNumber('id')->name('appointments.show');
  Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->whereNumber('id')->name('appointments.update');
  // Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->whereNumber('id')->name('appointments.destroy');//try sa patient

  // Doctors
  Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
  Route::get('/doctors/{id}', [DoctorController::class, 'show'])->whereNumber('id')->name('doctors.show');
  Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
  Route::put('/doctors/{id}', [DoctorController::class, 'update'])->whereNumber('id')->name('doctors.update');
  Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->whereNumber('id')->name('doctors.destroy');

  // Consultations / documents
  Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
  Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
  Route::get('/consultations/patient/{pid}', [ConsultationController::class, 'byPid'])->name('consultations.byPid');

  Route::get('/consultations/{id}', [ConsultationController::class, 'byId'])->whereNumber('id')->name('consultations.show');
  Route::put('/consultations/{id}', [ConsultationController::class, 'update'])->whereNumber('id')->name('consultations.update');
  Route::delete('/consultations/{id}', [ConsultationController::class, 'destroy'])->whereNumber('id')->name('consultations.destroy');

  //prescription
  Route::get('/medicines', [PrescriptionItemsController::class, 'index'])->name('prescription_items.index');

  Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
  Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
  Route::put('/prescriptions/{id}', [PrescriptionController::class, 'update'])->whereNumber('id')->name('prescriptions.update');
  Route::delete('/prescriptions/{id}', [PrescriptionController::class, 'destroy'])->whereNumber('id')->name('prescriptions.destroy');

  Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show'])->whereNumber('id')->name('prescriptions.show');

  // Consultation PDFs
  Route::get('/consultations/{id}/prescription', [ConsultationPdfController::class, 'prescription'])->whereNumber('id');
  Route::get('/consultations/{id}/referral-letter', [ConsultationPdfController::class, 'referralLetter'])->whereNumber('id');
  Route::get('/consultations/{id}/medical-certificate', [ConsultationPdfController::class, 'medicalCertificate'])->whereNumber('id');
  // Route::get('/consultations/{id}/consultation', [ConsultationPdfController::class, 'consultationDocument'])->whereNumber('id'); // i want this line accesible to all user

  // Legacy appointment PDFs
  Route::get('/appointments/{id}/medical-certificate', [CertificateController::class, 'medicalCertificate'])->whereNumber('id');
  Route::get('/appointments/{id}/referral-letter', [CertificateController::class, 'referralLetter'])->whereNumber('id');
  Route::get('/appointments/{id}/prescription', [CertificateController::class, 'prescription'])->whereNumber('id');

  // Messages: staff/admin UI
  Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
  Route::get('/messages/{id}', [MessageController::class, 'show'])->whereNumber('id')->name('messages.show');

  
  // Reports: superadmin only
  Route::get('/reports', [ReportsController::class, 'index'])->name('reports.reports');
});

// API: messaging ajax + clinic schedule
Route::middleware('auth')->prefix('api')->group(function () {
  Route::get('/users/search', [MessageController::class, 'searchUsers']);
  Route::get('/conversations', [MessageController::class, 'apiConversations']);
  Route::get('/conversations/{id}/messages', [MessageController::class, 'apiConversationMessages']);
  Route::delete('/conversations/{id}/messages', [MessageController::class, 'apiDeleteConversationMessages']);
  Route::get('/clinic-schedule', [ClinicScheduleController::class, 'index']);
  Route::put('/clinic-schedule/{id}', [ClinicScheduleController::class, 'update'])->whereNumber('id');
});

// Settings: all authenticated users
Route::middleware('auth')->group(function () {
  Route::get('/settings', [SettingsController::class, 'index'])->name('settings.setting');
  Route::get('/settings/change-password', [SettingsController::class, 'changePasswordForm'])->name('settings.password.form');
  Route::get('/settings/update-profile', [SettingsController::class, 'updateProfileForm'])->name('settings.profile.form');
  Route::get('/settings/help', [SettingsController::class, 'helpGuide'])->name('settings.help');
  Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
  Route::patch('/settings/record', [SettingsController::class, 'updateRecord'])->name('settings.record.update');
  Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
  Route::post('/settings/layout-style', function (\Illuminate\Http\Request $request) {
      $style = $request->input('style', 'modern');
      return back()->withCookie(cookie()->forever('layout_style', $style));
  })->name('settings.layout-style');
});

// Legacy: backward compatibility
Route::get('/message', fn() => view('messages.message'))->name('message.legacy');

require __DIR__ . '/auth.php';
