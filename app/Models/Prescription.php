<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model {
  use HasFactory;

  protected $table = 'prescriptions';
  public $timestamps = false;

  protected $fillable = [
    'consultation_id',
    'medicine_id',
    'doctor_id',
    'dosage',
    'frequency',
    'duration',
    'instructions',
  ];

  public function medicine() {
    return $this->belongsTo(PrescriptionItem::class, 'medicine_id', 'id');
  }

  public function consultation() {
    return $this->belongsTo(Consultation::class, 'consultation_id', 'id');
  }
   public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }
}
