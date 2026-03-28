<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $table = 'consultations';

    protected $fillable = [
        'patient_pid',
        'appointment_id',
        'linked_consultation_id',
        'doctor_id',               // ✅ added
        'document_type',
        'consultation_date',
        'wt', 'bp', 'cr', 'rr', 'temperature', 'sp02',
        'history_physical_exam',
        'diagnosis',
        'treatment',
        'referral_to',
        'referral_reason',
        'prescription_meds',
        'remarks',
        'created_at',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(PatientRecord::class, 'patient_pid', 'pid');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'consultation_id', 'id');
    }

    public function linkedConsultation()
    {
        return $this->belongsTo(Consultation::class, 'linked_consultation_id', 'id');
    }

    public function generatedDocuments()
    {
        return $this->hasMany(Consultation::class, 'linked_consultation_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id'); 
        // ⚠ doctor_id stores doctor.user_id since doctor table has no id
    }
}
