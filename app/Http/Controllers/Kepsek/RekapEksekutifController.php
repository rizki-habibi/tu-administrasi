<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Anggaran;
use App\Models\CatatanKeuangan;
use App\Models\Kehadiran;
use App\Models\Laporan;
use App\Models\PengajuanIzin;
use App\Models\Pengguna;
use App\Models\Resolusi;
use App\Models\Skp;
use App\Services\LayananGeminiAi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapEksekutifController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // === KEPEGAWAIAN ===
        $totalPegawai = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->count();
        $pegawaiAktif = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count();

        // === KEHADIRAN BULAN INI ===
        $kehadiran = Kehadiran::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $rekapKehadiran = [
            'hadir' => $kehadiran->where('status', 'hadir')->count(),
            'terlambat' => $kehadiran->where('status', 'terlambat')->count(),
            'izin' => $kehadiran->where('status', 'izin')->count(),
            'sakit' => $kehadiran->where('status', 'sakit')->count(),
            'alpha' => $kehadiran->where('status', 'alpha')->count(),
            'total' => $kehadiran->count(),
        ];
        $persentaseKehadiran = $rekapKehadiran['total'] > 0
            ? round(($rekapKehadiran['hadir'] + $rekapKehadiran['terlambat']) / $rekapKehadiran['total'] * 100, 1)
            : 0;

        // === IZIN ===
        $izinBulanIni = PengajuanIzin::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->get();
        $rekapIzin = [
            'total' => $izinBulanIni->count(),
            'pending' => $izinBulanIni->where('status', 'pending')->count(),
            'disetujui' => $izinBulanIni->where('status', 'disetujui')->count(),
            'ditolak' => $izinBulanIni->where('status', 'ditolak')->count(),
        ];

        // === KEUANGAN ===
        $keuanganBulan = CatatanKeuangan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->where('status', 'verified')->get();
        $totalPemasukan = $keuanganBulan->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $keuanganBulan->where('jenis', 'pengeluaran')->sum('jumlah');
        $anggaranTahun = Anggaran::where('tahun_anggaran', $tahun)->where('status', 'active')->first();

        // === SKP ===
        $skpStats = [
            'diajukan' => Skp::where('status', 'diajukan')->count(),
            'dinilai' => Skp::whereYear('created_at', $tahun)->where('status', 'dinilai')->count(),
        ];

        // === RESOLUSI ===
        $resolusiBerlaku = Resolusi::where('status', 'berlaku')->count();

        // === LAPORAN ===
        $laporanBulan = Laporan::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count();

        // Chart: Tren 6 bulan kehadiran
        $trenLabels = [];
        $trenHadir = [];
        $trenAbsen = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = Carbon::create($tahun, $bulan)->subMonths($i);
            $trenLabels[] = $d->translatedFormat('M Y');
            $mk = Kehadiran::whereMonth('tanggal', $d->month)->whereYear('tanggal', $d->year)->get();
            $trenHadir[] = $mk->whereIn('status', ['hadir', 'terlambat'])->count();
            $trenAbsen[] = $mk->whereIn('status', ['alpha', 'izin', 'sakit'])->count();
        }

        return view('kepala-sekolah.rekap-eksekutif.index', compact(
            'bulan', 'tahun', 'totalPegawai', 'pegawaiAktif',
            'rekapKehadiran', 'persentaseKehadiran', 'rekapIzin',
            'totalPemasukan', 'totalPengeluaran', 'anggaranTahun',
            'skpStats', 'resolusiBerlaku', 'laporanBulan',
            'trenLabels', 'trenHadir', 'trenAbsen'
        ));
    }

    public function aiAnalisis(Request $request)
    {
        $ai = new LayananGeminiAi();
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $data = $this->gatherRekapData($bulan, $tahun);

        $prompt = "Sebagai konsultan pendidikan, analisis data rekap eksekutif SMA Negeri 2 Jember bulan {$bulan}/{$tahun}:\n" . json_encode($data, JSON_PRETTY_PRINT) .
            "\n\nBerikan: 1) Ringkasan eksekutif, 2) Poin positif, 3) Area perlu perhatian, 4) Rekomendasi strategis. Format HTML singkat dengan <strong> dan <ul>.";

        $jawaban = $ai->assistantChat($prompt);

        return response()->json(['success' => true, 'analisis' => $jawaban]);
    }

    private function gatherRekapData(int $bulan, int $tahun): array
    {
        $kehadiran = Kehadiran::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $keuangan = CatatanKeuangan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'verified')->get();

        return [
            'pegawai_aktif' => Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count(),
            'kehadiran_hadir' => $kehadiran->whereIn('status', ['hadir', 'terlambat'])->count(),
            'kehadiran_absen' => $kehadiran->whereIn('status', ['alpha', 'izin', 'sakit'])->count(),
            'izin_pending' => PengajuanIzin::where('status', 'pending')->count(),
            'pemasukan' => $keuangan->where('jenis', 'pemasukan')->sum('jumlah'),
            'pengeluaran' => $keuangan->where('jenis', 'pengeluaran')->sum('jumlah'),
            'skp_pending' => Skp::where('status', 'diajukan')->count(),
            'laporan_bulan_ini' => Laporan::whereMonth('created_at', $bulan)->count(),
        ];
    }
}
