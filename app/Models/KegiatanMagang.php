<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanMagang extends Model
{
    protected $table = 'kegiatan_magang';

    protected $fillable = [
        'pengguna_id', 'judul', 'deskripsi',
        'tanggal_mulai', 'tanggal_selesai',
        'status', 'prioritas', 'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
