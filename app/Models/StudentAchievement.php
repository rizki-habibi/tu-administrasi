<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    use HasFactory;

    protected $table = 'prestasi_siswa';

    protected $fillable = [
        'siswa_id', 'judul', 'tingkat', 'jenis', 'tanggal', 'penyelenggara', 'hasil', 'path_file',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'siswa_id');
    }
}
