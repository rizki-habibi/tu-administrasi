<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $table = 'data_siswa';

    protected $fillable = [
        'nis', 'nisn', 'nama', 'kelas', 'tahun_ajaran', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat',
        'nama_orang_tua', 'telepon_orang_tua', 'foto', 'status',
        'tanggal_masuk', 'tanggal_keluar', 'notes', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function achievements()
    {
        return $this->hasMany(StudentAchievement::class, 'siswa_id');
    }

    public function violations()
    {
        return $this->hasMany(StudentViolation::class, 'siswa_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'Aktif',
            'mutasi_masuk' => 'Mutasi Masuk',
            'mutasi_keluar' => 'Mutasi Keluar',
            'lulus' => 'Lulus',
            'do' => 'Drop Out',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'success',
            'mutasi_masuk' => 'info',
            'mutasi_keluar' => 'warning',
            'lulus' => 'primary',
            'do' => 'danger',
            default => 'secondary',
        };
    }
}
