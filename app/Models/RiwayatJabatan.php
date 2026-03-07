<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatJabatan extends Model
{
    protected $table = 'riwayat_jabatan';

    protected $fillable = [
        'pengguna_id', 'nama_jabatan', 'unit_kerja',
        'tmt_jabatan', 'tmt_selesai', 'nomor_sk', 'tanggal_sk',
        'pejabat_penetap', 'file_sk', 'keterangan',
    ];

    protected $casts = [
        'tmt_jabatan' => 'date',
        'tmt_selesai' => 'date',
        'tanggal_sk' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
