<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanBuku extends Model
{
    protected $table = 'peminjaman_buku';

    protected $fillable = [
        'buku_id', 'nama_peminjam', 'kelas', 'tanggal_pinjam',
        'tanggal_kembali_rencana', 'tanggal_kembali_aktual',
        'status', 'catatan', 'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    public function buku()
    {
        return $this->belongsTo(BukuPerpustakaan::class, 'buku_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat' => 'Terlambat',
            default => ucfirst($this->status),
        };
    }
}
