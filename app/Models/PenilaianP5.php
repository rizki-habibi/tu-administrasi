<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianP5 extends Model
{
    use HasFactory;

    protected $table = 'penilaian_p5';

    protected $fillable = [
        'tema', 'judul_projek', 'deskripsi', 'kelas', 'fase',
        'tahun_ajaran', 'semester', 'dimensi', 'target_capaian',
        'path_file', 'nama_file', 'status', 'dibuat_oleh',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function getDimensiLabelAttribute(): string
    {
        return match ($this->dimensi) {
            'beriman' => 'Beriman, Bertakwa kepada Tuhan YME & Berakhlak Mulia',
            'mandiri' => 'Mandiri',
            'gotong_royong' => 'Gotong Royong',
            'berkebinekaan' => 'Berkebinekaan Global',
            'bernalar_kritis' => 'Bernalar Kritis',
            'kreatif' => 'Kreatif',
            default => ucfirst($this->dimensi),
        };
    }
}
