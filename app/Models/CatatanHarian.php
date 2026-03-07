<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanHarian extends Model
{
    protected $table = 'catatan_harian';

    protected $fillable = [
        'pengguna_id', 'tanggal', 'kegiatan', 'hasil',
        'kendala', 'rencana_besok', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
