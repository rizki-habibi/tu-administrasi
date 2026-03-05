<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Kehadiran;
use App\Models\Dokumen;
use App\Models\PengajuanIzin;
use App\Models\Laporan;
use App\Models\Acara;
use App\Models\UcapanUlangTahun;
use App\Models\CatatanBeranda;
use App\Services\LayananGeminiAi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $totalStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->count();
        $activeStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count();
        $todayPresent = Kehadiran::whereDate('tanggal', today())->whereIn('status', ['hadir', 'terlambat'])->count();
        $todayLate = Kehadiran::whereDate('tanggal', today())->where('status', 'terlambat')->count();
        $pendingLeave = PengajuanIzin::where('status', 'pending')->count();
        $monthReports = Laporan::whereMonth('created_at', now()->month)->count();
        $totalDocs = Dokumen::count();
        $upcomingEvents = Acara::where('tanggal_acara', '>=', today())->where('status', 'upcoming')->take(5)->get();

        $recentAttendances = Kehadiran::with('user')->whereDate('tanggal', today())->latest()->take(8)->get();
        $recentLeaves = PengajuanIzin::with('user')->where('status', 'pending')->latest()->take(5)->get();

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

        // Chart data: Kehadiran 7 hari terakhir
        $weeklyLabels = [];
        $weeklyHadir = [];
        $weeklyTerlambat = [];
        $weeklyAlpha = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D, d/m');
            $dayAttendances = Kehadiran::whereDate('tanggal', $date->toDateString())->get();
            $weeklyHadir[] = $dayAttendances->where('status', 'hadir')->count();
            $weeklyTerlambat[] = $dayAttendances->where('status', 'terlambat')->count();
            $weeklyAlpha[] = $dayAttendances->where('status', 'alpha')->count();
        }

        // Chart data: Distribusi status kehadiran bulan ini
        $monthAttendances = Kehadiran::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->get();
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

        $users = Pengguna::whereNotNull('tanggal_lahir')
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

    /**
     * AI Assistant — jawab pertanyaan admin tentang data sekolah
     */
    public function aiAssistant(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:1000',
        ]);

        $gemini = new LayananGeminiAi();

        if (!$gemini->isConfigured()) {
            return response()->json([
                'success' => false,
                'jawaban' => 'API Key Gemini AI belum dikonfigurasi. Silakan tambahkan GEMINI_API_KEY di file .env',
            ]);
        }

        $jawaban = $gemini->assistantChat($request->pertanyaan);

        if (!$jawaban) {
            return response()->json([
                'success' => false,
                'jawaban' => 'Maaf, AI tidak dapat memproses pertanyaan saat ini. Silakan coba lagi nanti.',
            ]);
        }

        return response()->json([
            'success' => true,
            'jawaban' => $jawaban,
        ]);
    }

    /**
     * AI Ringkasan Dashboard
     */
    public function aiRingkasan()
    {
        $gemini = new LayananGeminiAi();

        if (!$gemini->isConfigured()) {
            return response()->json([
                'success' => false,
                'ringkasan' => null,
            ]);
        }

        $ringkasan = $gemini->generateDashboardSummary();

        return response()->json([
            'success' => true,
            'ringkasan' => $ringkasan,
        ]);
    }

    /**
     * AI Analisis Kehadiran
     */
    public function aiAnalisisKehadiran(Request $request)
    {
        $gemini = new LayananGeminiAi();
        $period = $request->get('periode', 'bulan_ini');

        if (!$gemini->isConfigured()) {
            return response()->json([
                'success' => false,
                'analisis' => 'API Key Gemini AI belum dikonfigurasi.',
            ]);
        }

        $analisis = $gemini->analyzeAttendance($period);

        return response()->json([
            'success' => true,
            'analisis' => $analisis,
        ]);
    }
}
