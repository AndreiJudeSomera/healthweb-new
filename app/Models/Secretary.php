<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secretary extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = ['user_id','SecAssignedID'];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function clinicStaff() { return $this->hasOne(ClinicStaff::class, 'user_id'); }
}
