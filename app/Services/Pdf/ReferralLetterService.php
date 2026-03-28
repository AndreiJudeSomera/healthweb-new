<?php

namespace App\Services\Pdf;

use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;

class ReferralLetterService {
  public function render(Consultation $consultation) {
    return Pdf::loadView('pdfs.referral-letter', [
      'consultation' => $consultation,
    ])->setPaper('A4', 'portrait');
  }
}
