<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'content',
        'ai_prompt',
        'template',
        'file_path',
        'status',
        'is_shared',
    ];

    protected function casts(): array
    {
        return [
            'is_shared' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwned($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeShared($query)
    {
        return $query->where('is_shared', true);
    }

    public function scopeAccessible($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhere('is_shared', true);
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
                'name' => 'Surat Resmi Sekolah',
                'category' => 'surat',
                'description' => 'Template surat resmi dengan kop SMA Negeri 2 Jember',
            ],
            'surat_keterangan' => [
                'name' => 'Surat Keterangan',
                'category' => 'surat',
                'description' => 'Template surat keterangan untuk berbagai keperluan',
            ],
            'notulen_rapat' => [
                'name' => 'Notulen Rapat',
                'category' => 'notulen',
                'description' => 'Template notulen/berita acara rapat resmi',
            ],
            'laporan_bulanan' => [
                'name' => 'Laporan Bulanan TU',
                'category' => 'laporan',
                'description' => 'Template laporan aktivitas bulanan TU',
            ],
            'laporan_kehadiran' => [
                'name' => 'Laporan Rekapitulasi Kehadiran',
                'category' => 'laporan',
                'description' => 'Template rekap kehadiran staf per bulan',
            ],
            'sk_kepala_sekolah' => [
                'name' => 'SK Kepala Sekolah',
                'category' => 'sk',
                'description' => 'Template surat keputusan kepala sekolah',
            ],
            'proposal_kegiatan' => [
                'name' => 'Proposal Kegiatan',
                'category' => 'proposal',
                'description' => 'Template proposal kegiatan sekolah',
            ],
            'laporan_keuangan' => [
                'name' => 'Laporan Keuangan',
                'category' => 'keuangan',
                'description' => 'Template laporan keuangan sekolah',
            ],
            'kosong' => [
                'name' => 'Dokumen Kosong',
                'category' => 'umum',
                'description' => 'Mulai dari dokumen kosong',
            ],
        ];
    }
}
