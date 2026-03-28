<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\Pdf\ConsultationService;
use App\Services\Pdf\MedicalCertificateService;
use App\Services\Pdf\PrescriptionService;
use App\Services\Pdf\ReferralLetterService;

class ConsultationPdfController extends Controller {

    public function medicalCertificate(int $id, MedicalCertificateService $pdf) {
        $consultation = Consultation::with([
            'patient',
            'doctor.clinicStaff.user', // ✅ load doctor for consultation
        ])->findOrFail($id);

        return $pdf->render($consultation)
                   ->stream("medical-certificate-{$consultation->id}.pdf");
    }

    public function prescription(int $id, PrescriptionService $pdf) {
        $consultation = Consultation::with([
            'patient',
            'doctor.clinicStaff.user', // ✅ load doctor
            'prescriptions.medicine',
        ])->findOrFail($id);

        return $pdf->render($consultation)
                   ->stream("prescription-{$consultation->id}.pdf");
    }

    public function referralLetter(int $id, ReferralLetterService $pdf) {
        $consultation = Consultation::with([
            'patient',
            'doctor.clinicStaff.user', // ✅ load doctor
        ])->findOrFail($id);

        return $pdf->render($consultation)
                   ->stream("referral-letter-{$consultation->id}.pdf");
    }

    // public function consultationDocument(int $id, ConsultationService $pdf) {
    //     $consultation = Consultation::with([
    //         'patient',
    //         'doctor.clinicStaff.user', // ✅ load doctor
    //     ])->findOrFail($id);

    //     return $pdf->render($consultation)
    //                ->stream("consultation-{$consultation->id}.pdf");
    // }

    public function consultationDocument(int $id, ConsultationService $pdf)
{
    $consultation = Consultation::with([
        'patient',
        'doctor.clinicStaff.user',
    ])->findOrFail($id);

    // No role check here
    // Anyone who is authenticated can access

    return $pdf->render($consultation)
               ->stream("consultation-{$consultation->id}.pdf");
}
//     public function consultationDocument(int $id, ConsultationService $pdf) {

//     $consultation = Consultation::with([
//         'patient',
//         'doctor.clinicStaff.user',
//     ])->findOrFail($id);

//     $user = auth()->user();

//     // ✅ If patient (role 0), allow only their own consultation
//     if ($user->role == 0) {

//         if (!$consultation->patient || $consultation->patient->user_id !== $user->id) {
//             abort(403, 'Unauthorized access.');
//         }
//     }

//     return $pdf->render($consultation)
//                ->stream("consultation-{$consultation->id}.pdf");
// }
}
