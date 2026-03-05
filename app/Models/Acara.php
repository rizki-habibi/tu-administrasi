<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'acara';

    protected $fillable = [
        'dibuat_oleh', 'judul', 'deskripsi', 'tanggal_acara',
        'waktu_mulai', 'waktu_selesai', 'lokasi', 'jenis', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_acara' => 'date',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->jenis) {
            'rapat' => 'Rapat',
            'kegiatan' => 'Kegiatan',
            'upacara' => 'Upacara',
            'pelatihan' => 'Pelatihan',
            'lainnya' => 'Lainnya',
            default => $this->jenis,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'upcoming' => 'primary',
            'ongoing' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'light',
        };
    }
}
