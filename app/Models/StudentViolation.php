<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'date', 'type', 'description', 'action_taken', 'reported_by',
    ];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
