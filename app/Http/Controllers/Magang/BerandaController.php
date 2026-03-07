<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\LogbookMagang;
use App\Models\KegiatanMagang;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $todayAttendance = Kehadiran::where('pengguna_id', $user->id)->whereDate('tanggal', today())->first();

        $monthlyStats = [
            'hadir' => Kehadiran::where('pengguna_id', $user->id)->where('status', 'hadir')->whereMonth('tanggal', now()->month)->count(),
            'terlambat' => Kehadiran::where('pengguna_id', $user->id)->where('status', 'terlambat')->whereMonth('tanggal', now()->month)->count(),
            'izin' => Kehadiran::where('pengguna_id', $user->id)->where('status', 'izin')->whereMonth('tanggal', now()->month)->count(),
        ];

        $logbookBulanIni = LogbookMagang::where('pengguna_id', $user->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $logbookHariIni = LogbookMagang::where('pengguna_id', $user->id)
            ->whereDate('tanggal', today())
            ->first();

        $kegiatanAktif = KegiatanMagang::where('pengguna_id', $user->id)
            ->where('status', 'berlangsung')
            ->count();

        $kegiatanSelesai = KegiatanMagang::where('pengguna_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        $pendingLeaves = PengajuanIzin::where('pengguna_id', $user->id)->where('status', 'pending')->count();
        $unreadNotifications = Notifikasi::where('pengguna_id', $user->id)->where('sudah_dibaca', false)->count();
        $recentNotifications = Notifikasi::where('pengguna_id', $user->id)->latest()->take(5)->get();

        // Hitung sisa hari magang
        $sisaHari = null;
        if ($user->tanggal_selesai_magang) {
            $sisaHari = max(0, now()->diffInDays($user->tanggal_selesai_magang, false));
        }

        return view('magang.beranda', compact(
            'todayAttendance', 'monthlyStats', 'logbookBulanIni', 'logbookHariIni',
            'kegiatanAktif', 'kegiatanSelesai', 'pendingLeaves',
            'unreadNotifications', 'recentNotifications', 'sisaHari'
        ));
    }
}
