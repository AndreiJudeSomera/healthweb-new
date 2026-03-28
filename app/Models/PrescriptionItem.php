<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model {
  use HasFactory;

  protected $table = 'prescription_items';
  public $timestamps = false;

  protected $fillable = [
    'medicine_name',
    'manually_added',
  ];

  protected $casts = [
    'manually_added' => 'boolean',
  ];

  public function prescriptions() {
    return $this->hasMany(Prescription::class, 'medicine_id', 'id');
  }
}
