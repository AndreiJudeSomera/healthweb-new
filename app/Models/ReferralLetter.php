<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ReferralLetter extends Model
{
    use HasFactory;

    protected $primaryKey = 'Referral_ID';
    protected $table = 'referral_letter';

    protected $fillable = [
        'Consultation_ID',
        'DateIssued',
        'Recipient',
        'ReasonforReferral'
    ];

    protected $encryptable = ['ReasonforReferral'];

    /* Relationships */
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'Consultation_ID', 'Consultation_ID');
    }

    /* Accessors & Mutators */
    private function decryptValue($value) { return $value ? Crypt::decryptString($value) : $value; }
    private function encryptValue($value) { return $value ? Crypt::encryptString($value) : $value; }

    public function getReasonforReferralAttribute($value) { return $this->decryptValue($value); }
    public function setReasonforReferralAttribute($value) { $this->attributes['ReasonforReferral'] = $this->encryptValue($value); }
}
