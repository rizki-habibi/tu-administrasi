<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'periode', 'jenis', 'nilai', 'predikat',
        'catatan', 'file_path', 'file_name', 'status', 'evaluated_by',
    ];

    protected $casts = ['nilai' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'pkg' => 'PKG (Penilaian Kinerja Guru)',
            'bkd' => 'BKD (Beban Kerja Dosen/Guru)',
            'skp' => 'SKP (Sasaran Kinerja Pegawai)',
            default => strtoupper($this->jenis),
        };
    }
}
