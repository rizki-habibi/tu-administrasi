<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'is_read', 'link',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'kehadiran' => 'success',
            'izin' => 'info',
            'event' => 'primary',
            'laporan' => 'warning',
            'sistem' => 'danger',
            'pengumuman' => 'dark',
            default => 'light',
        };
    }
}
