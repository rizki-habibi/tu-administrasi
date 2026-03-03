<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun', 'aspek', 'kondisi_saat_ini', 'target',
        'program_tindak_lanjut', 'status', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
