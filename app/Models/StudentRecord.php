<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis', 'nisn', 'name', 'class', 'academic_year', 'gender',
        'place_of_birth', 'date_of_birth', 'religion', 'address',
        'parent_name', 'parent_phone', 'photo', 'status',
        'entry_date', 'exit_date', 'notes', 'created_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'entry_date' => 'date',
        'exit_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function achievements()
    {
        return $this->hasMany(StudentAchievement::class, 'student_id');
    }

    public function violations()
    {
        return $this->hasMany(StudentViolation::class, 'student_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'Aktif',
            'mutasi_masuk' => 'Mutasi Masuk',
            'mutasi_keluar' => 'Mutasi Keluar',
            'lulus' => 'Lulus',
            'do' => 'Drop Out',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'success',
            'mutasi_masuk' => 'info',
            'mutasi_keluar' => 'warning',
            'lulus' => 'primary',
            'do' => 'danger',
            default => 'secondary',
        };
    }
}
