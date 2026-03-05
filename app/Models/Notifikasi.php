<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'pengguna_id', 'judul', 'pesan', 'jenis', 'sudah_dibaca', 'tautan',
    ];

    protected function casts(): array
    {
        return [
            'sudah_dibaca' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('sudah_dibaca', false);
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->jenis) {
            'kehadiran' => 'success',
            'izin' => 'info',
            'event' => 'primary',
            'laporan' => 'warning',
            'sistem' => 'danger',
            'pengumuman' => 'dark',
            default => 'light',
        };
    }
}
