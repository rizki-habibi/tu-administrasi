<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran_siswa';

    protected $fillable = [
        'siswa_id', 'tanggal', 'jenis', 'deskripsi', 'tindakan', 'dilaporkan_oleh',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'siswa_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }
}
