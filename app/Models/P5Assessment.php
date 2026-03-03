<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P5Assessment extends Model
{
    use HasFactory;

    protected $table = 'p5_assessments';

    protected $fillable = [
        'tema', 'judul_projek', 'deskripsi', 'kelas', 'fase',
        'academic_year', 'semester', 'dimensi', 'target_capaian',
        'file_path', 'file_name', 'status', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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
