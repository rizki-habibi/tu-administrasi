<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resolusi extends Model
{
    protected $table = 'resolusi';

    protected $fillable = [
        'nomor_resolusi', 'judul', 'latar_belakang', 'isi_keputusan',
        'tindak_lanjut', 'kategori', 'status', 'tanggal_berlaku',
        'tanggal_berakhir', 'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berlaku' => 'date',
            'tanggal_berakhir' => 'date',
        ];
    }

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public static function generateNomor(): string
    {
        $tahun = now()->year;
        $bulan = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
        $count = static::whereYear('created_at', $tahun)->count() + 1;
        $urut = str_pad($count, 3, '0', STR_PAD_LEFT);
        return "{$urut}/SK/KS/{$bulan}/{$tahun}";
    }
}
