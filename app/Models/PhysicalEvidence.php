<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalEvidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'kategori', 'deskripsi', 'file_path', 'file_name',
        'file_type', 'file_size', 'terkait', 'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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
