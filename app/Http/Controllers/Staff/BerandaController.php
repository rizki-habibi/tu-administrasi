<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Kehadiran;
use App\Models\PengajuanIzin;
use App\Models\Acara;
use App\Models\Notifikasi;
use App\Models\UcapanUlangTahun;
use App\Models\CatatanBeranda;
use Carbon\Carbon;
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
            'sakit' => Kehadiran::where('pengguna_id', $user->id)->where('status', 'sakit')->whereMonth('tanggal', now()->month)->count(),
            'alpha' => Kehadiran::where('pengguna_id', $user->id)->where('status', 'alpha')->whereMonth('tanggal', now()->month)->count(),
        ];

        $pendingLeaves = PengajuanIzin::where('pengguna_id', $user->id)->where('status', 'pending')->count();
        $upcomingEvents = Acara::where('tanggal_acara', '>=', today())->where('status', 'upcoming')->orderBy('tanggal_acara')->take(5)->get();
        $unreadNotifications = Notifikasi::where('pengguna_id', $user->id)->where('sudah_dibaca', false)->count();
        $recentNotifications = Notifikasi::where('pengguna_id', $user->id)->latest()->take(5)->get();

        // Birthday users today
        $birthdayUsers = Pengguna::whereMonth('tanggal_lahir', now()->month)
            ->whereDay('tanggal_lahir', now()->day)
            ->where('aktif', true)
            ->get();

        // Catatan beranda for current user
        $catatanList = CatatanBeranda::where('pengguna_id', $user->id)
            ->orderByDesc('disematkan')
            ->orderByDesc('created_at')
            ->get();

        // Ucapan belum dibaca count
        $ucapanBelumDibaca = UcapanUlangTahun::where('penerima_id', $user->id)
            ->where('sudah_dibaca', false)
            ->count();

        return view('staf.beranda', compact(
            'todayAttendance', 'monthlyStats', 'pendingLeaves',
            'upcomingEvents', 'unreadNotifications', 'recentNotifications',
            'birthdayUsers', 'catatanList', 'ucapanBelumDibaca'
        ));
    }

    public function birthdayList()
    {
        $today = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        $users = Pengguna::whereNotNull('tanggal_lahir')
            ->where('aktif', true)
            ->get()
            ->filter(function ($user) use ($today, $endDate) {
                $birthday = Carbon::parse($user->tanggal_lahir)->setYear($today->year);
                if ($birthday->lt($today)) {
                    $birthday->addYear();
                }
                return $birthday->between($today, $endDate);
            })
            ->map(function ($user) use ($today) {
                $birthday = Carbon::parse($user->tanggal_lahir)->setYear($today->year);
                if ($birthday->lt($today)) {
                    $birthday->addYear();
                }
                $user->upcoming_birthday = $birthday->format('Y-m-d');
                return $user;
            })
            ->sortBy('upcoming_birthday')
            ->groupBy('upcoming_birthday');

        $currentYear = now()->year;
        $ucapanDikirim = UcapanUlangTahun::with('penerima')
            ->where('pengirim_id', auth()->id())
            ->where('tahun', $currentYear)
            ->latest()
            ->get();

        $ucapanDiterima = UcapanUlangTahun::with('pengirim')
            ->where('penerima_id', auth()->id())
            ->where('tahun', $currentYear)
            ->latest()
            ->get();

        return view('staf.ulang-tahun', compact('users', 'ucapanDikirim', 'ucapanDiterima'));
    }

    public function sendBirthdayGreeting(Request $request)
    {
        $request->validate([
            'penerima_id' => 'required|exists:pengguna,id',
            'pesan' => 'required|string|max:1000',
        ]);

        $ucapan = UcapanUlangTahun::create([
            'pengirim_id' => auth()->id(),
            'penerima_id' => $request->penerima_id,
            'pesan' => $request->pesan,
            'tahun' => now()->year,
            'sudah_dibaca' => false,
        ]);

        return response()->json([
            'success' => true,
            'pesan' => 'Ucapan selamat ulang tahun berhasil dikirim!',
            'data' => $ucapan,
        ]);
    }

    public function storeCatatan(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'warna' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
        ]);

        $catatan = CatatanBeranda::create([
            'pengguna_id' => auth()->id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'warna' => $request->warna ?? 'yellow',
            'tanggal' => $request->tanggal,
            'disematkan' => false,
        ]);

        return response()->json([
            'success' => true,
            'pesan' => 'Catatan berhasil ditambahkan!',
            'data' => $catatan,
        ]);
    }

    public function updateCatatan(Request $request, CatatanBeranda $catatan)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'warna' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'disematkan' => 'nullable|boolean',
        ]);

        $catatan->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'warna' => $request->warna ?? $catatan->warna,
            'tanggal' => $request->tanggal,
            'disematkan' => $request->has('disematkan') ? $request->disematkan : $catatan->disematkan,
        ]);

        return response()->json([
            'success' => true,
            'pesan' => 'Catatan berhasil diperbarui!',
            'data' => $catatan,
        ]);
    }

    public function destroyCatatan(CatatanBeranda $catatan)
    {
        $catatan->delete();

        return response()->json([
            'success' => true,
            'pesan' => 'Catatan berhasil dihapus!',
        ]);
    }
}
