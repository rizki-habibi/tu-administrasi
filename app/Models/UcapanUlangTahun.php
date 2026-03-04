<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UcapanUlangTahun extends Model
{
    use HasFactory;

    protected $table = 'ucapan_ulang_tahun';

    protected $fillable = [
        'pengirim_id',
        'penerima_id',
        'pesan',
        'tahun',
        'sudah_dibaca',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}
