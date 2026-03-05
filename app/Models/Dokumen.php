<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'judul', 'deskripsi', 'kategori', 'path_file', 'nama_file', 'tipe_file', 'ukuran_file', 'diunggah_oleh',
    ];

    public function uploader()
    {
        return $this->belongsTo(Pengguna::class, 'diunggah_oleh');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getFileIconAttribute(): string
    {
        return match ($this->tipe_file) {
            'pdf' => 'bi-file-earmark-pdf-fill text-danger',
            'doc', 'docx' => 'bi-file-earmark-word-fill text-primary',
            'xls', 'xlsx' => 'bi-file-earmark-excel-fill text-success',
            'ppt', 'pptx' => 'bi-file-earmark-ppt-fill text-warning',
            'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image-fill text-info',
            default => 'bi-file-earmark-fill text-secondary',
        };
    }
}
