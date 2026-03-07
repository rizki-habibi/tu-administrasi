<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Panduan extends Model
{
    protected $table = 'panduan';

    protected $fillable = [
        'judul', 'slug', 'deskripsi', 'konten', 'ikon', 'warna',
        'versi', 'logo', 'kategori', 'visibilitas', 'urutan', 'aktif', 'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeUntukSemua($query)
    {
        return $query->where('visibilitas', 'semua');
    }

    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    protected static function booted(): void
    {
        static::creating(function ($panduan) {
            if (empty($panduan->slug)) {
                $panduan->slug = Str::slug($panduan->judul);
            }
        });
    }
}
