<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\Pdf\MedicalCertificateService;
use App\Services\Pdf\PrescriptionService;
use App\Services\Pdf\ReferralLetterService;

class CertificateController extends Controller {
  public function medicalCertificate(int $id, MedicalCertificateService $pdf) {
    $appointment = Appointment::with(['patient', 'doctor.user'])->findOrFail($id);

    // stream() previews in browser; download() forces download
    return $pdf->render($appointment)->stream("medical-certificate-{$appointment->id}.pdf");
  }

  public function referralLetter(int $id, ReferralLetterService $pdf) {
    $appointment = \App\Models\Appointment::with(['patient', 'doctor.user'])->findOrFail($id);
    return $pdf->render($appointment)->stream("referral-letter-{$appointment->id}.pdf");
  }

  public function prescription(int $id, PrescriptionService $pdf) {
    $appointment = \App\Models\Appointment::with(['patient', 'doctor.user'])->findOrFail($id);
    return $pdf->render($appointment)->stream("prescription-{$appointment->id}.pdf");
  }

}
