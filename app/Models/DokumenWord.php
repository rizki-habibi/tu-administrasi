<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenWord extends Model
{
    use HasFactory;

    protected $table = 'dokumen_word';

    protected $fillable = [
        'pengguna_id',
        'judul',
        'kategori',
        'konten',
        'prompt_ai',
        'templat',
        'path_file',
        'status',
        'dibagikan',
    ];

    protected function casts(): array
    {
        return [
            'dibagikan' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function scopeOwned($query, $userId)
    {
        return $query->where('pengguna_id', $userId);
    }

    public function scopeShared($query)
    {
        return $query->where('dibagikan', true);
    }

    public function scopeAccessible($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('pengguna_id', $userId)->orWhere('dibagikan', true);
        });
    }

    public static function categories(): array
    {
        return [
            'umum' => 'Umum',
            'surat' => 'Surat / Memo',
            'laporan' => 'Laporan',
            'sk' => 'SK / Keputusan',
            'notulen' => 'Notulen Rapat',
            'proposal' => 'Proposal',
            'keuangan' => 'Keuangan',
        ];
    }

    public static function templates(): array
    {
        return [
            'surat_resmi' => [
                'nama' => 'Surat Resmi Sekolah',
                'kategori' => 'surat',
                'deskripsi' => 'Template surat resmi dengan kop SMA Negeri 2 Jember',
            ],
            'surat_keterangan' => [
                'nama' => 'Surat Keterangan',
                'kategori' => 'surat',
                'deskripsi' => 'Template surat keterangan untuk berbagai keperluan',
            ],
            'notulen_rapat' => [
                'nama' => 'Notulen Rapat',
                'kategori' => 'notulen',
                'deskripsi' => 'Template notulen/berita acara rapat resmi',
            ],
            'laporan_bulanan' => [
                'nama' => 'Laporan Bulanan TU',
                'kategori' => 'laporan',
                'deskripsi' => 'Template laporan aktivitas bulanan TU',
            ],
            'laporan_kehadiran' => [
                'nama' => 'Laporan Rekapitulasi Kehadiran',
                'kategori' => 'laporan',
                'deskripsi' => 'Template rekap kehadiran staf per bulan',
            ],
            'sk_kepala_sekolah' => [
                'nama' => 'SK Kepala Sekolah',
                'kategori' => 'sk',
                'deskripsi' => 'Template surat keputusan kepala sekolah',
            ],
            'proposal_kegiatan' => [
                'nama' => 'Proposal Kegiatan',
                'kategori' => 'proposal',
                'deskripsi' => 'Template proposal kegiatan sekolah',
            ],
            'laporan_keuangan' => [
                'nama' => 'Laporan Keuangan',
                'kategori' => 'keuangan',
                'deskripsi' => 'Template laporan keuangan sekolah',
            ],
            'kosong' => [
                'nama' => 'Dokumen Kosong',
                'kategori' => 'umum',
                'deskripsi' => 'Mulai dari dokumen kosong',
            ],
        ];
    }
}
