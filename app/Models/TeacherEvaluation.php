<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEvaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_guru';

    protected $fillable = [
        'pengguna_id', 'periode', 'jenis', 'nilai', 'predikat',
        'catatan', 'path_file', 'nama_file', 'status', 'dievaluasi_oleh',
    ];

    protected $casts = ['nilai' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'dievaluasi_oleh');
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
