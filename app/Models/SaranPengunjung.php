<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaranPengunjung extends Model
{
    protected $table = 'saran_pengunjung';

    protected $fillable = [
        'nama', 'email', 'subjek', 'pesan',
        'status', 'tanggapan', 'ditanggapi_oleh', 'ditanggapi_pada',
    ];

    protected function casts(): array
    {
        return [
            'ditanggapi_pada' => 'datetime',
        ];
    }

    public function penanggap()
    {
        return $this->belongsTo(Pengguna::class, 'ditanggapi_oleh');
    }

    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }
}
