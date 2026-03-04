<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_sekolah';

    protected $fillable = [
        'tahun', 'aspek', 'kondisi_saat_ini', 'target',
        'program_tindak_lanjut', 'status', 'dibuat_oleh',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
