<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanBeranda extends Model
{
    use HasFactory;

    protected $table = 'catatan_beranda';

    protected $fillable = [
        'pengguna_id',
        'judul',
        'isi',
        'warna',
        'tanggal',
        'disematkan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'disematkan' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
