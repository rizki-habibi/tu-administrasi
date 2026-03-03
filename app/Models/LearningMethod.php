<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_metode', 'jenis', 'deskripsi', 'langkah_pelaksanaan',
        'kelebihan', 'kekurangan', 'hasil', 'mata_pelajaran',
        'file_path', 'file_name', 'status', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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
