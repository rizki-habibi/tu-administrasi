<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesan extends Model
{
    protected $table = 'pesan';

    protected $fillable = [
        'percakapan_id', 'pengirim_id', 'isi', 'tipe', 'file_path', 'file_nama', 'balasan_id',
    ];

    public function percakapan(): BelongsTo
    {
        return $this->belongsTo(Percakapan::class, 'percakapan_id');
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengirim_id');
    }

    public function balasan(): BelongsTo
    {
        return $this->belongsTo(Pesan::class, 'balasan_id');
    }
}
