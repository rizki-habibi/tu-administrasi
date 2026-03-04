<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarAnalysis extends Model
{
    use HasFactory;

    protected $table = 'analisis_star';

    protected $fillable = [
        'judul', 'kategori', 'situasi', 'tugas', 'aksi', 'hasil',
        'refleksi', 'tindak_lanjut', 'path_file', 'dibuat_oleh',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
