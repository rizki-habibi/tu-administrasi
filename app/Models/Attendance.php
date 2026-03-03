<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'clock_in', 'clock_out', 'status',
        'latitude_in', 'longitude_in', 'latitude_out', 'longitude_out',
        'photo_in', 'photo_out', 'note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isLate(): bool
    {
        $setting = AttendanceSetting::first();
        if (!$setting || !$this->clock_in) return false;

        return $this->clock_in > $setting->clock_in_time;
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
