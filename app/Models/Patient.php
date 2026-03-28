<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model {
  use HasFactory;

  // Primary key = user_id (not default "id")
  protected $primaryKey = 'user_id';
  protected $keyType = 'unsignedBigInteger';
  public $incrementing = false; // kasi hindi auto_increment

  protected $fillable = [
    'user_id',
    'patient_type',
    'record_id',
  ];

  public function user() {
    return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
  }

  public function record() {
    return $this->belongsTo(\App\Models\PatientRecord::class, 'record_id');
  }
}
