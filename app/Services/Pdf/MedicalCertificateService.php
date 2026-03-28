<?php

namespace App\Services\Pdf;

use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalCertificateService {
  public function render(Consultation $consultation) {
    return Pdf::loadView('pdfs.medical-certificate', [
      'consultation' => $consultation,
    ])->setPaper('a4');
  }
}
