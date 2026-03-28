<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
  use HasFactory, Notifiable;

  protected $fillable = [
    'username',
    'email',
    'password',
    'role',
    'is_active',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'is_active' => 'boolean',
    ];
  }

  public function patientRecord() {
    return $this->hasOne(PatientRecord::class, 'user_id', 'id');
  }

  public function clinicStaff() {
    return $this->hasOne(ClinicStaff::class, 'User_ID', 'id');
  }

  public function doctor() {
    return $this->hasOne(Doctor::class);
  }

}
