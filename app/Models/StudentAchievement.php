<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'title', 'level', 'type', 'date', 'organizer', 'result', 'file_path',
    ];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }
}
