<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $table = 'template_dokumen';

    protected $fillable = [
        'nama', 'kode', 'kategori', 'konten', 'kolom_isian', 'format', 'aktif', 'dibuat_oleh',
    ];

    protected $casts = [
        'kolom_isian' => 'array',
        'aktif' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->kategori) {
            'akademik' => 'Akademik',
            'kepegawaian' => 'Kepegawaian',
            'kesiswaan' => 'Kesiswaan',
            'sarpras' => 'Sarana & Prasarana',
            'keuangan' => 'Keuangan',
            'akreditasi' => 'Akreditasi',
            default => ucfirst($this->kategori),
        };
    }
}
