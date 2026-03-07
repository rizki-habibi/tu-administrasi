<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenKepegawaian extends Model
{
    protected $table = 'dokumen_kepegawaian';

    protected $fillable = [
        'pengguna_id', 'judul', 'kategori', 'nomor_dokumen',
        'tanggal_dokumen', 'file_path', 'file_type', 'file_size', 'keterangan',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
    ];

    const KATEGORI = [
        'sk_cpns'             => 'SK CPNS',
        'sk_pns'              => 'SK PNS',
        'sk_kenaikan_pangkat' => 'SK Kenaikan Pangkat',
        'sk_jabatan'          => 'SK Jabatan',
        'sk_mutasi'           => 'SK Mutasi',
        'ijazah'              => 'Ijazah',
        'sertifikat'          => 'Sertifikat',
        'piagam'              => 'Piagam',
        'kgb'                 => 'KGB (Kenaikan Gaji Berkala)',
        'dp3_ppk'             => 'DP3/PPK',
        'skp'                 => 'SKP',
        'sttpl'               => 'STTPL (Surat Tanda Tamat Pendidikan & Latihan)',
        'lainnya'             => 'Lainnya',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
