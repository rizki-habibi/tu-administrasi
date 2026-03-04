<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';

    protected $fillable = [
        'nomor_surat', 'jenis', 'kategori', 'perihal', 'isi',
        'tujuan', 'asal', 'tanggal_surat', 'tanggal_terima',
        'status', 'sifat', 'path_file', 'nama_file',
        'dibuat_oleh', 'disetujui_oleh', 'catatan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    // ─── Relationships ────────────────────────
    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    // ─── Auto Number Generator ────────────────
    public static function generateNomor(string $jenis = 'keluar', string $kategori = 'dinas'): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');

        $prefix = $jenis === 'masuk' ? 'SM' : 'SK';
        $kodeKategori = match ($kategori) {
            'dinas'         => 'DN',
            'undangan'      => 'UND',
            'keterangan'    => 'KET',
            'keputusan'     => 'KEP',
            'edaran'        => 'ED',
            'tugas'         => 'ST',
            'pemberitahuan' => 'PBR',
            default         => 'UM',
        };

        $lastSurat = static::where('jenis', $jenis)
            ->whereYear('tanggal_surat', $year)
            ->orderByDesc('id')
            ->first();

        $nextNum = $lastSurat ? ((int) explode('/', $lastSurat->nomor_surat)[0]) + 1 : 1;

        return sprintf('%03d/%s/%s/TU-SMA2/%s/%s', $nextNum, $prefix, $kodeKategori, $month, $year);
    }

    // ─── Scopes ───────────────────────────────
    public function scopeMasuk($query)
    {
        return $query->where('jenis', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis', 'keluar');
    }

    // ─── Helpers ──────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft'      => '<span class="badge bg-secondary">Draft</span>',
            'diproses'   => '<span class="badge bg-info">Diproses</span>',
            'dikirim'    => '<span class="badge bg-primary">Dikirim</span>',
            'diterima'   => '<span class="badge bg-success">Diterima</span>',
            'diarsipkan' => '<span class="badge bg-dark">Diarsipkan</span>',
            default      => '<span class="badge bg-light text-dark">' . ucfirst($this->status) . '</span>',
        };
    }

    public function getSifatBadgeAttribute(): string
    {
        return match ($this->sifat) {
            'biasa'   => '<span class="badge bg-secondary bg-opacity-25 text-secondary">Biasa</span>',
            'penting' => '<span class="badge bg-warning bg-opacity-25 text-warning">Penting</span>',
            'segera'  => '<span class="badge bg-danger bg-opacity-25 text-danger">Segera</span>',
            'rahasia' => '<span class="badge bg-dark bg-opacity-25 text-dark">Rahasia</span>',
            default   => '<span class="badge bg-light text-dark">' . ucfirst($this->sifat) . '</span>',
        };
    }
}
