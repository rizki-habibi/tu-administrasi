<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    protected $fillable = [
        'pengguna_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status',
        'lat_masuk', 'lng_masuk', 'alamat_masuk',
        'lat_pulang', 'lng_pulang', 'alamat_pulang',
        'foto_masuk', 'foto_pulang', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function isLate(): bool
    {
        $setting = AttendanceSetting::first();
        if (!$setting || !$this->jam_masuk) return false;

        return $this->jam_masuk > $setting->jam_masuk;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'success',
            'terlambat' => 'warning',
            'izin' => 'info',
            'sakit' => 'primary',
            'alpha' => 'danger',
            'cuti' => 'secondary',
            default => 'light',
        };
    }
}
