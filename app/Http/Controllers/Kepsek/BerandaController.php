<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Kehadiran;
use App\Models\PengajuanIzin;
use App\Models\Skp;
use App\Models\Acara;
use App\Models\Notifikasi;
use App\Models\CatatanKeuangan;
use App\Models\UcapanUlangTahun;
use App\Models\CatatanBeranda;
use App\Services\LayananGeminiAi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $totalStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count();
        $todayPresent = Kehadiran::whereDate('tanggal', today())->whereIn('status', ['hadir', 'terlambat'])->count();
        $pendingLeave = PengajuanIzin::where('status', 'pending')->count();
        $pendingSkp = Skp::where('status', 'diajukan')->count();

        $monthlyAttendance = [
            'hadir' => Kehadiran::where('status', 'hadir')->whereMonth('tanggal', now()->month)->count(),
            'terlambat' => Kehadiran::where('status', 'terlambat')->whereMonth('tanggal', now()->month)->count(),
            'izin' => Kehadiran::where('status', 'izin')->whereMonth('tanggal', now()->month)->count(),
            'sakit' => Kehadiran::where('status', 'sakit')->whereMonth('tanggal', now()->month)->count(),
            'alpha' => Kehadiran::where('status', 'alpha')->whereMonth('tanggal', now()->month)->count(),
        ];

        $upcomingEvents = Acara::where('tanggal_acara', '>=', today())->where('status', 'upcoming')->orderBy('tanggal_acara')->take(5)->get();
        $recentLeaves = PengajuanIzin::with('user')->where('status', 'pending')->latest()->take(5)->get();
        $recentNotifications = Notifikasi::where('pengguna_id', auth()->id())->latest()->take(5)->get();

        // Birthday users today
        $birthdayUsers = Pengguna::whereMonth('tanggal_lahir', now()->month)
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

        return view('kepala-sekolah.beranda', compact(
            'totalStaff', 'todayPresent', 'pendingLeave', 'pendingSkp',
            'monthlyAttendance', 'upcomingEvents', 'recentLeaves', 'recentNotifications',
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

        return view('kepala-sekolah.ulang-tahun', compact('users', 'ucapanDikirim', 'ucapanDiterima'));
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

    public function aiAssistant(Request $request)
    {
        $request->validate(['pertanyaan' => 'required|string|max:1000']);

        $gemini = new LayananGeminiAi();

        if (!$gemini->isConfigured()) {
            return response()->json(['success' => false, 'jawaban' => 'API Key Gemini AI belum dikonfigurasi.']);
        }

        $jawaban = $gemini->assistantChat($request->pertanyaan);

        return response()->json([
            'success' => (bool) $jawaban,
            'jawaban' => $jawaban ?? 'Maaf, AI tidak dapat memproses saat ini.',
        ]);
    }

    public function aiRingkasan()
    {
        $gemini = new LayananGeminiAi();

        if (!$gemini->isConfigured()) {
            return response()->json(['success' => false, 'ringkasan' => null]);
        }

        return response()->json([
            'success' => true,
            'ringkasan' => $gemini->generateDashboardSummary(),
        ]);
    }
}
