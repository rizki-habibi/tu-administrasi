<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'clock_in_time', 'clock_out_time', 'late_tolerance_minutes',
        'office_latitude', 'office_longitude', 'max_distance_meters',
    ];
}
