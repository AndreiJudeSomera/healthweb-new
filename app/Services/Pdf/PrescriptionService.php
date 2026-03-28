<?php

namespace App\Services\Pdf;

use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionService {
  public function render(Consultation $consultation) {
    return Pdf::loadView('pdfs.prescription', [
      'consultation' => $consultation,
    ])->setPaper('A6', 'portrait');
  }
}
