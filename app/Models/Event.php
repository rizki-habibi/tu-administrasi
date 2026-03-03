<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by', 'title', 'description', 'event_date',
        'start_time', 'end_time', 'location', 'type', 'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'rapat' => 'Rapat',
            'kegiatan' => 'Kegiatan',
            'upacara' => 'Upacara',
            'pelatihan' => 'Pelatihan',
            'lainnya' => 'Lainnya',
            default => $this->type,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'upcoming' => 'primary',
            'ongoing' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'light',
        };
    }
}
