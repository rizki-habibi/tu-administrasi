<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'type', 'academic_year', 'semester',
        'subject', 'class_level', 'file_path', 'file_name', 'file_type',
        'file_size', 'status', 'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'kalender_pendidikan' => 'Kalender Pendidikan',
            'jadwal_pelajaran' => 'Jadwal Pelajaran',
            'rpp' => 'RPP / Modul Ajar',
            'silabus' => 'Silabus',
            'modul_ajar' => 'Modul Ajar',
            'kisi_kisi' => 'Kisi-kisi Soal',
            'analisis_butir_soal' => 'Analisis Butir Soal',
            'berita_acara_ujian' => 'Berita Acara Ujian',
            'daftar_nilai' => 'Daftar Nilai Siswa',
            'rekap_nilai' => 'Rekap Nilai Semester',
            'leger' => 'Leger Nilai',
            'raport' => 'Raport',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return '-';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
