<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanFasilitas extends Model
{
    protected $table = 'peminjaman_fasilitas';

    protected $fillable = [
        'nama_fasilitas', 'jenis', 'peminjam_id', 'peminjam_nama',
        'keperluan', 'tanggal', 'jam_mulai', 'jam_selesai',
        'penanggung_jawab', 'catatan', 'status', 'alasan_tolak',
        'disetujui_oleh', 'disetujui_pada',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    const JENIS = [
        'ruangan' => 'Ruangan',
        'lapangan' => 'Lapangan',
        'peralatan' => 'Peralatan',
    ];

    const FASILITAS = [
        'Aula' => 'Aula',
        'Lapangan Basket' => 'Lapangan Basket',
        'Lapangan Futsal' => 'Lapangan Futsal',
        'Lapangan Voli' => 'Lapangan Voli',
        'Lab Komputer' => 'Lab Komputer',
        'Lab IPA' => 'Lab IPA',
        'Ruang Rapat' => 'Ruang Rapat',
        'Perpustakaan' => 'Perpustakaan',
        'Mushola' => 'Mushola',
        'Kantin' => 'Kantin',
    ];

    public function peminjam()
    {
        return $this->belongsTo(Pengguna::class, 'peminjam_id');
    }

    public function approver()
    {
        return $this->belongsTo(Pengguna::class, 'disetujui_oleh');
    }

    public function scopeOverlapping($query, $fasilitas, $tanggal, $jamMulai, $jamSelesai, $excludeId = null)
    {
        return $query->where('nama_fasilitas', $fasilitas)
            ->where('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where(function ($q2) use ($jamMulai, $jamSelesai) {
                    $q2->where('jam_mulai', '<', $jamSelesai)
                        ->where('jam_selesai', '>', $jamMulai);
                });
            })
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'disetujui' => 'success',
            'ditolak' => 'danger',
            'selesai' => 'info',
            default => 'secondary',
        };
    }
}
