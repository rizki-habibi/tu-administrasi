<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
    protected $table = 'disposisi_surat';

    protected $fillable = [
        'surat_id', 'dari_pengguna_id', 'kepada_pengguna_id',
        'instruksi', 'prioritas', 'tenggat', 'status',
        'catatan_tindakan', 'dibaca_pada', 'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'tenggat' => 'date',
            'dibaca_pada' => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function dariPengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dari_pengguna_id');
    }

    public function kepadaPengguna()
    {
        return $this->belongsTo(Pengguna::class, 'kepada_pengguna_id');
    }
}
