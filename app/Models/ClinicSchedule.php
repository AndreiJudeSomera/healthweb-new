<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSchedule extends Model
{
    protected $table = 'clinic_schedules';

    protected $fillable = ['day_of_week', 'day_name', 'is_open', 'open_time', 'close_time'];

    protected $casts = [
        'is_open' => 'boolean',
    ];
}
