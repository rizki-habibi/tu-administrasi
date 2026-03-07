<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\PengaturanPengguna;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LayananNotifikasi
{
    /**
     * Kirim notifikasi ke satu pengguna (database + email + push)
     */
    public static function kirim(int $penggunaId, string $judul, string $pesan, string $jenis = 'sistem', ?string $tautan = null): Notifikasi
    {
        // 1. Simpan ke database
        $notifikasi = Notifikasi::create([
            'pengguna_id' => $penggunaId,
            'judul'       => $judul,
            'pesan'       => $pesan,
            'jenis'       => $jenis,
            'tautan'      => $tautan,
        ]);

        // 2. Kirim email jika diaktifkan
        $emailAktif = PengaturanPengguna::dapatkan($penggunaId, 'notifikasi_email', 'false');
        if ($emailAktif === 'true') {
            static::kirimEmail($penggunaId, $judul, $pesan, $tautan);
        }

        return $notifikasi;
    }

    /**
     * Kirim notifikasi ke banyak pengguna sekaligus
     */
    public static function kirimKeBanyak(array $penggunaIds, string $judul, string $pesan, string $jenis = 'sistem', ?string $tautan = null): int
    {
        $count = 0;
        foreach ($penggunaIds as $id) {
            static::kirim($id, $judul, $pesan, $jenis, $tautan);
            $count++;
        }
        return $count;
    }

    /**
     * Kirim ke semua staf aktif
     */
    public static function kirimKeSemuaStaf(string $judul, string $pesan, string $jenis = 'pengumuman', ?string $tautan = null): int
    {
        $ids = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)
            ->where('aktif', true)
            ->pluck('id')
            ->toArray();

        return static::kirimKeBanyak($ids, $judul, $pesan, $jenis, $tautan);
    }

    /**
     * Kirim email notifikasi
     */
    protected static function kirimEmail(int $penggunaId, string $judul, string $pesan, ?string $tautan): void
    {
        try {
            $user = Pengguna::find($penggunaId);
            if (!$user || !$user->email) return;

            Mail::send('email.notifikasi', [
                'judul'  => $judul,
                'pesan'  => $pesan,
                'tautan' => $tautan ?: null,
                'nama'   => $user->nama,
            ], function ($mail) use ($user, $judul) {
                $mail->to($user->email, $user->nama)
                     ->subject("[SIMPEG-SMART] {$judul}");
            });
        } catch (\Exception $e) {
            Log::warning("Gagal kirim email notifikasi ke user #{$penggunaId}: " . $e->getMessage());
        }
    }

    /**
     * Ambil notifikasi penting yang belum dibaca (untuk popup)
     */
    public static function ambilNotifikasiPenting(int $penggunaId, int $limit = 5): array
    {
        return Notifikasi::where('pengguna_id', $penggunaId)
            ->where('sudah_dibaca', false)
            ->whereIn('jenis', ['pengumuman', 'sistem', 'event'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn($n) => [
                'id'     => $n->id,
                'judul'  => $n->judul,
                'pesan'  => \Str::limit($n->pesan, 100),
                'jenis'  => $n->jenis,
                'badge'  => $n->type_badge,
                'waktu'  => $n->created_at->diffForHumans(),
                'tautan' => $n->tautan,
            ])
            ->toArray();
    }

    /**
     * Hitung total notifikasi belum dibaca
     */
    public static function hitungBelumDibaca(int $penggunaId): int
    {
        return Notifikasi::where('pengguna_id', $penggunaId)
            ->where('sudah_dibaca', false)
            ->count();
    }

    /**
     * Cek penggunaan storage (local disk) dan return info
     */
    public static function cekPenggunaanStorage(): array
    {
        $storagePath = storage_path('app/public');
        $totalBytes  = 0;

        if (is_dir($storagePath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                $totalBytes += $file->getSize();
            }
        }

        // Batas default 1GB (bisa di-config)
        $limitBytes = (int) config('app.storage_limit_gb', 1) * 1073741824;
        $persentase = $limitBytes > 0 ? round(($totalBytes / $limitBytes) * 100, 1) : 0;

        return [
            'terpakai_bytes'    => $totalBytes,
            'terpakai_format'   => static::formatBytes($totalBytes),
            'limit_bytes'       => $limitBytes,
            'limit_format'      => static::formatBytes($limitBytes),
            'persentase'        => $persentase,
            'hampir_penuh'      => $persentase >= 80,
            'penuh'             => $persentase >= 95,
        ];
    }

    protected static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
