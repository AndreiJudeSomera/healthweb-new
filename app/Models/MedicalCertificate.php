<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MedicalCertificate extends Model
{
    use HasFactory;

    protected $primaryKey = 'MedicalCertificate_ID';
    protected $table = 'medical_certificate';

    protected $fillable = [
        'Consultation_ID',
        'DateIssued',
        'Remarks',
        'Requestor',
        'ReasonforRequest'
    ];

    protected $encryptable = ['Remarks', 'ReasonforRequest'];

    /* Relationships */
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'Consultation_ID', 'Consultation_ID');
    }

    /* Accessors & Mutators */
    private function decryptValue($value) { return $value ? Crypt::decryptString($value) : $value; }
    private function encryptValue($value) { return $value ? Crypt::encryptString($value) : $value; }

    public function getRemarksAttribute($value) { return $this->decryptValue($value); }
    public function setRemarksAttribute($value) { $this->attributes['Remarks'] = $this->encryptValue($value); }

    public function getReasonforRequestAttribute($value) { return $this->decryptValue($value); }
    public function setReasonforRequestAttribute($value) { $this->attributes['ReasonforRequest'] = $this->encryptValue($value); }
}
