<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogbookMagang extends Model
{
    protected $table = 'logbook_magang';

    protected $fillable = [
        'pengguna_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'kegiatan', 'hasil', 'kendala', 'rencana_besok',
        'status', 'catatan_pembimbing',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
