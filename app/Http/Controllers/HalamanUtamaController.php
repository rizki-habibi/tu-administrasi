<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use App\Models\Pengguna;
use App\Models\Surat;
use App\Models\Kehadiran;
use App\Models\Inventaris;
use App\Models\DataSiswa;
use App\Models\Acara;
use App\Models\KontenPublik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HalamanUtamaController extends Controller
{
    public function index(Request $request)
    {
        // Catat kunjungan
        if (Schema::hasTable('pengunjung')) {
            Pengunjung::catat($request, '/');
        }

        $pengunjungTersedia = Schema::hasTable('pengunjung');

        // Statistik pengunjung
        $statistikPengunjung = [
            'hari_ini'        => $pengunjungTersedia ? Pengunjung::hariIni() : 0,
            'bulan_ini'       => $pengunjungTersedia ? Pengunjung::bulanIni() : 0,
            'total_unik'      => $pengunjungTersedia ? Pengunjung::totalUnik() : 0,
            'total_kunjungan' => $pengunjungTersedia ? Pengunjung::totalKunjungan() : 0,
        ];

        // Statistik layanan sekolah (publik — hanya angka umum)
        $statistikLayanan = [
            'total_pegawai' => Schema::hasTable('pengguna') ? Pengguna::where('aktif', true)->count() : 0,
            'total_siswa'   => Schema::hasTable('data_siswa') ? DataSiswa::where('status', 'aktif')->count() : 0,
            'total_surat'   => Schema::hasTable('surat') ? Surat::count() : 0,
            'total_inventaris' => Schema::hasTable('inventaris') ? Inventaris::count() : 0,
            'total_acara'   => Schema::hasTable('acara') ? Acara::where('status', 'upcoming')->count() : 0,
        ];

        // Data pengunjung terakhir (untuk peta — hanya yang punya koordinat)
        $lokasiPengunjung = $pengunjungTersedia
            ? Pengunjung::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->latest()
                ->take(50)
                ->get(['latitude', 'longitude', 'kota', 'created_at'])
            : collect();

        // Berita terbaru untuk halaman utama
        $beritaTerbaru = Schema::hasTable('konten_publik')
            ? KontenPublik::aktif()
                ->where('kategori', 'berita')
                ->bagian('halaman_utama')
                ->orderByDesc('created_at')
                ->take(6)
                ->get()
            : collect();

        return view('halaman-utama', compact(
            'statistikPengunjung',
            'statistikLayanan',
            'lokasiPengunjung',
            'beritaTerbaru'
        ));
    }
}
