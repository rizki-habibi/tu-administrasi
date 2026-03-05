<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembelajaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembelajaran';

    protected $fillable = [
        'nama_metode', 'jenis', 'deskripsi', 'langkah_pelaksanaan',
        'kelebihan', 'kekurangan', 'hasil', 'mata_pelajaran',
        'path_file', 'nama_file', 'status', 'dibuat_oleh',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'model_pembelajaran' => 'Model Pembelajaran',
            'teknologi_pembelajaran' => 'Teknologi Pembelajaran',
            'media_pembelajaran' => 'Media Pembelajaran',
            default => ucfirst(str_replace('_', ' ', $this->jenis)),
        };
    }
}
