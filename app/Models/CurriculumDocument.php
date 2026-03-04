<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumDocument extends Model
{
    use HasFactory;

    protected $table = 'dokumen_kurikulum';

    protected $fillable = [
        'judul', 'deskripsi', 'jenis', 'tahun_ajaran', 'semester',
        'mata_pelajaran', 'tingkat_kelas', 'path_file', 'nama_file', 'tipe_file',
        'ukuran_file', 'status', 'diunggah_oleh',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'diunggah_oleh');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->jenis) {
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
            default => ucfirst(str_replace('_', ' ', $this->jenis)),
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->ukuran_file;
        if (!$bytes) return '-';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
