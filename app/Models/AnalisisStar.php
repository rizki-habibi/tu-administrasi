<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalisisStar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'analisis_star';

    protected $fillable = [
        'judul', 'kategori', 'situasi', 'tugas', 'aksi', 'hasil',
        'refleksi', 'tindak_lanjut', 'path_file', 'dibuat_oleh',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }
}
