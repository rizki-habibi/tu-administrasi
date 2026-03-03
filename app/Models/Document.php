<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category', 'file_path', 'file_name', 'file_type', 'file_size', 'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getFileIconAttribute(): string
    {
        return match ($this->file_type) {
            'pdf' => 'bi-file-earmark-pdf-fill text-danger',
            'doc', 'docx' => 'bi-file-earmark-word-fill text-primary',
            'xls', 'xlsx' => 'bi-file-earmark-excel-fill text-success',
            'ppt', 'pptx' => 'bi-file-earmark-ppt-fill text-warning',
            'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image-fill text-info',
            default => 'bi-file-earmark-fill text-secondary',
        };
    }
}
