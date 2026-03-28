<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Doctor extends Model {
  use HasFactory;

  protected $table = 'doctors';
  protected $primaryKey = 'user_id';
  public $incrementing = false;

  protected $fillable = [
    'user_id',
    'dr_license_no',
    'ptr_no',
  ];

  /**
   * Hide sensitive fields from JSON
   */
  protected $hidden = [
    'dr_license_no',
    'ptr_no',
  ];

  /* === Encrypt / Decrypt license + PTR === */

  public function setDrLicenseNoAttribute($value) {
    $this->attributes['dr_license_no'] =
    $value !== null ? Crypt::encryptString($value) : null;
  }

  public function getDrLicenseNoAttribute($value) {
    return $value !== null ? Crypt::decryptString($value) : null;
  }

  public function setPtrNoAttribute($value) {
    $this->attributes['ptr_no'] =
    $value !== null ? Crypt::encryptString($value) : null;
  }

  public function getPtrNoAttribute($value) {
    return $value !== null ? Crypt::decryptString($value) : null;
  }

  public function user() {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function clinicStaff() {
    return $this->belongsTo(ClinicStaff::class, 'user_id', 'user_id');
  }
  
}
