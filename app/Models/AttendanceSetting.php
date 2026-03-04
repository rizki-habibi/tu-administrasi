<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $table = 'pengaturan_kehadiran';

    protected $fillable = [
        'jam_masuk', 'jam_pulang', 'toleransi_terlambat_menit',
        'lat_kantor', 'lng_kantor', 'jarak_maksimal_meter',
    ];
}
