<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $table = 'pengingat';

    protected $fillable = [
        'judul', 'deskripsi', 'jenis', 'tenggat', 'waktu_pengingat',
        'berulang', 'jenis_pengulangan', 'pengguna_id', 'dibuat_oleh',
        'selesai', 'sudah_diberitahu',
    ];

    protected $casts = [
        'tenggat' => 'date',
        'berulang' => 'boolean',
        'selesai' => 'boolean',
        'sudah_diberitahu' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->jenis) {
            'deadline_laporan' => 'Deadline Laporan',
            'bkd' => 'Pengumpulan BKD',
            'evaluasi_semester' => 'Evaluasi Semester',
            'tugas' => 'Tugas',
            'lainnya' => 'Lainnya',
            default => ucfirst(str_replace('_', ' ', $this->jenis)),
        };
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tenggat', '>=', today())->where('selesai', false);
    }

    public function scopeOverdue($query)
    {
        return $query->where('tenggat', '<', today())->where('selesai', false);
    }
}
