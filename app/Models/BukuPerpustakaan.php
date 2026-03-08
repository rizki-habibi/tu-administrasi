<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuPerpustakaan extends Model
{
    protected $table = 'buku_perpustakaan';

    protected $fillable = [
        'kode_buku', 'judul', 'pengarang', 'penerbit', 'tahun_terbit',
        'isbn', 'kategori', 'lokasi_rak', 'jumlah_total', 'jumlah_tersedia',
        'harga', 'sumber_dana', 'kondisi', 'keterangan', 'dibuat_oleh',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    const KATEGORI = [
        'fiksi' => 'Fiksi',
        'non_fiksi' => 'Non Fiksi',
        'referensi' => 'Referensi',
        'pelajaran' => 'Buku Pelajaran',
        'ensiklopedia' => 'Ensiklopedia',
        'majalah' => 'Majalah/Jurnal',
        'umum' => 'Umum',
    ];

    const SUMBER_DANA = [
        'bos' => 'Dana BOS',
        'apbd' => 'APBD',
        'sumbangan' => 'Sumbangan/Donasi',
        'swadaya' => 'Swadaya Sekolah',
        'lainnya' => 'Lainnya',
    ];

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanBuku::class, 'buku_id');
    }

    public static function generateKode(): string
    {
        $count = static::count();
        return 'BK-' . now()->format('Ym') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
