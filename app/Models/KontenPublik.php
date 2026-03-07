<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenPublik extends Model
{
    protected $table = 'konten_publik';

    protected $fillable = [
        'judul', 'deskripsi', 'konten', 'kategori', 'tipe',
        'path_file', 'nama_file', 'tipe_file', 'ukuran_file',
        'url_external', 'thumbnail', 'bagian', 'urutan',
        'aktif', 'unggulan', 'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'unggulan' => 'boolean',
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

    public function scopeBagian($query, string $bagian)
    {
        return $query->where(function ($q) use ($bagian) {
            $q->where('bagian', $bagian)->orWhere('bagian', 'keduanya');
        });
    }

    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeUnggulan($query)
    {
        return $query->where('unggulan', true);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->path_file ? asset('storage/' . $this->path_file) : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        return asset('storage/' . $this->thumbnail);
    }

    public function getUkuranFormatAttribute(): string
    {
        if (!$this->ukuran_file) return '-';
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
