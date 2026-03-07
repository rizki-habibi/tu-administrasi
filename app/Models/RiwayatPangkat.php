<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPangkat extends Model
{
    protected $table = 'riwayat_pangkat';

    protected $fillable = [
        'pengguna_id', 'pangkat', 'golongan',
        'tmt_pangkat', 'nomor_sk', 'tanggal_sk',
        'pejabat_penetap', 'jenis_kenaikan', 'file_sk', 'keterangan',
    ];

    protected $casts = [
        'tmt_pangkat' => 'date',
        'tanggal_sk' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
