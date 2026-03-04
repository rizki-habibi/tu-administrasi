<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\LeaveRequest;
use App\Models\Report;
use App\Models\Event;
use App\Models\UcapanUlangTahun;
use App\Models\CatatanBeranda;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStaff = User::whereIn('peran', User::STAFF_ROLES)->count();
        $activeStaff = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->count();
        $todayPresent = Attendance::whereDate('tanggal', today())->whereIn('status', ['hadir', 'terlambat'])->count();
        $todayLate = Attendance::whereDate('tanggal', today())->where('status', 'terlambat')->count();
        $pendingLeave = LeaveRequest::where('status', 'pending')->count();
        $monthReports = Report::whereMonth('created_at', now()->month)->count();
        $totalDocs = Document::count();
        $upcomingEvents = Event::where('tanggal_acara', '>=', today())->where('status', 'upcoming')->take(5)->get();

        $recentAttendances = Attendance::with('user')->whereDate('tanggal', today())->latest()->take(8)->get();
        $recentLeaves = LeaveRequest::with('user')->where('status', 'pending')->latest()->take(5)->get();

        // Birthday users today
        $birthdayUsers = User::whereMonth('tanggal_lahir', now()->month)
            ->whereDay('tanggal_lahir', now()->day)
            ->where('aktif', true)
            ->get();

        // Catatan beranda for current user
        $catatanList = CatatanBeranda::where('pengguna_id', auth()->id())
            ->orderByDesc('disematkan')
            ->orderByDesc('created_at')
            ->get();

        // Ucapan belum dibaca count
        $ucapanBelumDibaca = UcapanUlangTahun::where('penerima_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->count();

        // Chart data: Kehadiran 7 hari terakhir
        $weeklyLabels = [];
        $weeklyHadir = [];
        $weeklyTerlambat = [];
        $weeklyAlpha = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D, d/m');
            $dayAttendances = Attendance::whereDate('tanggal', $date->toDateString())->get();
            $weeklyHadir[] = $dayAttendances->where('status', 'hadir')->count();
            $weeklyTerlambat[] = $dayAttendances->where('status', 'terlambat')->count();
            $weeklyAlpha[] = $dayAttendances->where('status', 'alpha')->count();
        }

        // Chart data: Distribusi status kehadiran bulan ini
        $monthAttendances = Attendance::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->get();
        $statusDistribution = [
            'Hadir' => $monthAttendances->where('status', 'hadir')->count(),
            'Terlambat' => $monthAttendances->where('status', 'terlambat')->count(),
            'Izin' => $monthAttendances->where('status', 'izin')->count(),
            'Sakit' => $monthAttendances->where('status', 'sakit')->count(),
            'Alpha' => $monthAttendances->where('status', 'alpha')->count(),
        ];

        return view('admin.beranda', compact(
            'totalStaff', 'activeStaff', 'todayPresent', 'todayLate',
            'pendingLeave', 'monthReports', 'totalDocs', 'upcomingEvents',
            'recentAttendances', 'recentLeaves',
            'weeklyLabels', 'weeklyHadir', 'weeklyTerlambat', 'weeklyAlpha',
            'statusDistribution',
            'birthdayUsers', 'catatanList', 'ucapanBelumDibaca'
        ));
    }

    public function birthdayList()
    {
        $today = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        $users = User::whereNotNull('tanggal_lahir')
            ->where('aktif', true)
            ->get()
            ->filter(function ($user) use ($today, $endDate) {
                $birthday = Carbon::parse($user->tanggal_lahir)->setYear($today->year);
                // If birthday already passed this year but is within next 7 days when wrapping around year end
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

        return view('admin.ulang-tahun', compact('users', 'ucapanDikirim', 'ucapanDiterima'));
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
