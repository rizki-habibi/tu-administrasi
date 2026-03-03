<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'category',
        'priority', 'status', 'attachment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'submitted' => 'primary',
            'reviewed' => 'warning',
            'completed' => 'success',
            default => 'light',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'inventaris' => 'Inventaris',
            'keuangan' => 'Keuangan',
            'kegiatan' => 'Kegiatan',
            'lainnya' => 'Lainnya',
            default => $this->category,
        };
    }
}
