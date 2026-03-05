<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiFisik extends Model
{
    use HasFactory;

    protected $table = 'bukti_fisik';

    protected $fillable = [
        'judul', 'kategori', 'deskripsi', 'path_file', 'nama_file',
        'tipe_file', 'ukuran_file', 'terkait', 'diunggah_oleh',
    ];

    public function uploader()
    {
        return $this->belongsTo(Pengguna::class, 'diunggah_oleh');
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
