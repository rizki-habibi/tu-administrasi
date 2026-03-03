<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'type', 'due_date', 'reminder_time',
        'is_recurring', 'recurring_type', 'user_id', 'created_by',
        'is_completed', 'is_notified',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_recurring' => 'boolean',
        'is_completed' => 'boolean',
        'is_notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'deadline_laporan' => 'Deadline Laporan',
            'bkd' => 'Pengumpulan BKD',
            'evaluasi_semester' => 'Evaluasi Semester',
            'tugas' => 'Tugas',
            'lainnya' => 'Lainnya',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>=', today())->where('is_completed', false);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today())->where('is_completed', false);
    }
}
