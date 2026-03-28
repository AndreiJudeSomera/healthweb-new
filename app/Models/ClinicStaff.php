<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicStaff extends Model
{
    use HasFactory;

    // Define table name (since it's not pluralized)
    protected $table = 'clinic_staff';

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'Lname',
        'Fname',
        'Mname',
        'ContactNumber',
        'Address',
        'DateofBirth',
        'Age',
        'Gender',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'user_id');
    }

    public function secretary()
    {
        return $this->hasOne(Secretary::class, 'user_id', 'user_id');
    }
    
}
