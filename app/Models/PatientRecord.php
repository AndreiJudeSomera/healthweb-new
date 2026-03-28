<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class PatientRecord extends Model {
  use HasFactory;

  protected $table = 'patient_records';

  protected $fillable = [
    "pid",
    "last_name",
    "first_name",
    "middle_name",
    "date_of_birth",
    "gender",
    "nationality",
    "contact_number",
    "address",
    "guardian_name",
    "guardian_relation",
    "guardian_contact",
    "allergy",
    "alcohol",
    "years_of_smoking",
    "illicit_drug_use",
    "hypertension",
    "asthma",
    "diabetes",
    "cancer",
    "thyroid",
    "others",
    "patient_type",
    "is_bound",
  ];

  protected $casts = [
    "date_of_birth" => "date",
    "years_of_smoking" => "integer",
    "hypertension" => "boolean",
    "asthma" => "boolean",
    "diabetes" => "boolean",
    "cancer" => "boolean",
    "thyroid" => "boolean",
  ];

  protected $encryptable = [
    'last_name',
    'first_name',
    'middle_name',
    'nationality',
    'contact_number',
    'address',
    'guardian_name',
    'guardian_relation',
    'guardian_contact',
    'allergy',
    'alcohol',
    'illicit_drug_use',
    'others',
    
  ];

  protected $appends = ['age'];

  public function appointments() {
    return $this->hasMany(
      Appointment::class,
      'patient_pid',
      'pid'
    );
  }

  private function decryptValue($value) {
    if (is_null($value) || $value === '') {
      return $value;
    }
    try {
      return Crypt::decryptString($value);
    } catch (\Throwable $e) {
      return $value;
    }
  }

  private function encryptValue($value) {
    return is_null($value) ? $value : Crypt::encryptString($value);
  }

  protected static function booted() {
    static::creating(function (self $record) {
      if (!empty($record->pid)) {
        return;
      }

      // Check: Do 5 retries until pid is unique
      // Note: Band-aid fix for race condition :))
      for ($i = 0; $i < 5; $i++) {
        $pid = self::generatePidForDob($record->date_of_birth);

        if (!self::where('pid', $pid)->exists()) {
          $record->pid = $pid;
          return;
        }
      }

      throw new \RuntimeException("Failed to generate unique PID after retires.");
    });
  }

  // Using: PID-YYYMMDD-DD-0000
  // Where: YYYMMDD = now() ; DD = $dateOfBirth ; 0000 = 4 digit counter from 1
  protected static function generatePidForDob($dateOfBirth): string {
    $dateCreated = now()->format('Ymd');

    // Type: Carbon|string|null
    $dob = $dateOfBirth ? \Illuminate\Support\Carbon::parse($dateOfBirth) : null;
    $dobDay = $dob ? $dob->format('d') : '00';

    $prefix = 'PID-' . $dateCreated . $dobDay . '-';

    // Find: latest PID
    $lastPid = self::where('pid', 'like', $prefix . '%')->orderByDesc('pid')->value('pid');

    $counter = 1;
    if ($lastPid) {
      $lastCounter = (int) substr($lastPid, -4);
      $counter = $lastCounter + 1;
    }

    return $prefix . str_pad((string) $counter, 4, '0', STR_PAD_LEFT);
  }

  protected function isEncryptable($key) {
    return in_array($key, $this->encryptable, true);
  }

  public function getAttributeValue($key) {
    $value = parent::getAttributeValue($key);
    return $this->isEncryptable($key) ? $this->decryptValue($value) : $value;
  }

  public function setAttribute($key, $value) {
    if ($this->isEncryptable($key)) {
      $value = $this->encryptValue($value);
    }
    return parent::setAttribute($key, $value);
  }

  public function getAgeAttribute(): ?int {
    if (empty($this->date_of_birth)) {
      return null;
    }

    $dob = $this->date_of_birth instanceof Carbon
    ? $this->date_of_birth
    : Carbon::parse($this->date_of_birth);

    return $dob->age;
  }
}
