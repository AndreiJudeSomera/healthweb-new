<?php

namespace App\Services\Pdf;

use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsultationService {
  public function render(Consultation $consultation) {
    return Pdf::loadView('pdfs.consultation', [
      'consultation' => $consultation,
    ])->setPaper('A4', 'portrait');
  }
}
