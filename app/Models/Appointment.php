<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
  protected $table = 'appointments';
  protected $primaryKey = 'id';

  protected $fillable = [
    'patient_pid',
    'guest_name',
    'guest_age',
    'guest_sex',
    'guest_contact',
    'appointment_type',
    'appointment_date',
    'appointment_time',
    'attended_by',
    'status',
  ];

  public function patient() {
    return $this->belongsTo(PatientRecord::class, 'patient_pid', 'pid');
  }

  public function doctor() {
    return $this->belongsTo(Doctor::class, 'attended_by', 'user_id');
  }
}
