<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'pengguna_id', 'judul', 'deskripsi', 'kategori',
        'prioritas', 'status', 'lampiran',
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'submitted' => 'primary',
            'reviewed' => 'warning',
            'completed' => 'success',
            default => 'light',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->kategori) {
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'inventaris' => 'Inventaris',
            'keuangan' => 'Keuangan',
            'kegiatan' => 'Kegiatan',
            'lainnya' => 'Lainnya',
            default => $this->kategori,
        };
    }
}
