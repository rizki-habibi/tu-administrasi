<?php

namespace App\Services;

use App\Models\Kehadiran;
use App\Models\Pengguna;
use App\Models\Acara;
use App\Models\PengajuanIzin;
use App\Models\Laporan;
use App\Models\Dokumen;
use App\Models\Surat;
use App\Models\CatatanKeuangan;
use App\Models\Anggaran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LayananGeminiAi
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    }

    /**
     * Cek apakah API key sudah dikonfigurasi
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Panggil Gemini API
     */
    protected function callApi(string $prompt, float $temperature = 0.7, int $maxTokens = 4096): ?string
    {
        if (!$this->isConfigured()) {
            Log::warning('Gemini AI: API key belum dikonfigurasi');
            return null;
        }

        try {
            $response = Http::timeout(60)->post(
                "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $prompt]],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => $maxTokens,
                        'topP' => 0.9,
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                    ],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error('Gemini AI: Response error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini AI: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate konten dokumen berdasarkan prompt
     */
    public function generateDocument(string $prompt, ?string $template = null): ?string
    {
        $systemPrompt = $this->buildSystemPrompt($template);
        $fullPrompt = $systemPrompt . "\n\nPermintaan pengguna: " . $prompt;

        $text = $this->callApi($fullPrompt);
        return $text ? $this->cleanHtmlResponse($text) : null;
    }

    /**
     * AI Assistant — Jawab pertanyaan admin tentang data sekolah
     */
    public function assistantChat(string $question, ?string $context = null): ?string
    {
        $dataContext = $context ?? $this->gatherSchoolData();

        $prompt = "Kamu adalah Asisten AI Administrasi SMA Negeri 2 Jember. "
            . "Jawab pertanyaan pengguna berdasarkan data sekolah yang tersedia. "
            . "Gunakan Bahasa Indonesia yang sopan dan profesional. "
            . "Jika data tidak tersedia, sampaikan dengan jujur. "
            . "Berikan jawaban yang ringkas, informatif, dan langsung ke intinya. "
            . "Format menggunakan HTML sederhana (gunakan <strong>, <ul>, <li>, <p>, <br>). "
            . "JANGAN gunakan markdown. Langsung tulis HTML saja.\n\n"
            . "DATA SEKOLAH SAAT INI:\n" . $dataContext . "\n\n"
            . "PERTANYAAN: " . $question;

        return $this->callApi($prompt, 0.5, 2048);
    }

    /**
     * Ringkasan dashboard otomatis
     */
    public function generateDashboardSummary(): ?string
    {
        $cacheKey = 'ai_dashboard_summary_' . now()->format('Y-m-d-H');

        return Cache::remember($cacheKey, 3600, function () {
            $data = $this->gatherSchoolData();

            $prompt = "Kamu adalah Asisten AI Administrasi SMA Negeri 2 Jember. "
                . "Berdasarkan data berikut, buat RINGKASAN SINGKAT (maksimal 3-4 kalimat) "
                . "tentang kondisi administrasi sekolah hari ini. "
                . "Soroti hal-hal penting yang perlu diperhatikan (kehadiran rendah, izin pending, agenda hari ini, dll). "
                . "Gunakan bahasa Indonesia yang ringkas dan langsung ke inti. "
                . "JANGAN gunakan markdown, langsung teks biasa.\n\n"
                . "DATA:\n" . $data;

            return $this->callApi($prompt, 0.3, 512);
        });
    }

    /**
     * Analisis kehadiran dengan AI
     */
    public function analyzeAttendance(string $period = 'bulan_ini'): ?string
    {
        $data = $this->getAttendanceData($period);

        $prompt = "Kamu adalah analis data kehadiran SMA Negeri 2 Jember. "
            . "Analisis data kehadiran berikut dan berikan insight dalam Bahasa Indonesia. "
            . "Format dalam HTML sederhana dengan heading dan list. "
            . "Sertakan: 1) Ringkasan, 2) Tren, 3) Masalah, 4) Rekomendasi.\n\n"
            . "DATA KEHADIRAN:\n" . $data;

        return $this->callApi($prompt, 0.4, 2048);
    }

    /**
     * Kumpulkan data statistik sekolah untuk konteks AI
     */
    public function gatherSchoolData(): string
    {
        $today = now()->translatedFormat('l, d F Y');

        // Statistik staf
        $totalStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->count();
        $staffAktif = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count();

        // Kehadiran hari ini
        $hadirHariIni = Kehadiran::whereDate('tanggal', today())->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat = Kehadiran::whereDate('tanggal', today())->where('status', 'terlambat')->count();
        $alpha = Kehadiran::whereDate('tanggal', today())->where('status', 'alpha')->count();
        $sakit = Kehadiran::whereDate('tanggal', today())->where('status', 'sakit')->count();
        $izinHariIni = Kehadiran::whereDate('tanggal', today())->where('status', 'izin')->count();

        // Izin pending
        $izinPending = PengajuanIzin::where('status', 'pending')->count();

        // Agenda
        $agendaHariIni = Acara::whereDate('tanggal_acara', today())->pluck('judul')->implode(', ') ?: 'Tidak ada';
        $agendaMendatang = Acara::where('tanggal_acara', '>', today())
            ->where('tanggal_acara', '<=', now()->addDays(7))
            ->get()
            ->map(fn ($e) => $e->judul . ' (' . $e->tanggal_acara->translatedFormat('d M') . ')')
            ->implode(', ') ?: 'Tidak ada';

        // Dokumen & laporan bulan ini
        $laporanBulanIni = Laporan::whereMonth('created_at', now()->month)->count();
        $totalDokumen = Dokumen::count();
        $suratBulanIni = Surat::whereMonth('created_at', now()->month)->count();

        // Keuangan
        $totalAnggaran = Anggaran::where('tahun_ajaran', now()->year . '/' . (now()->year + 1))->sum('jumlah');
        $totalPengeluaran = CatatanKeuangan::where('tipe', 'pengeluaran')->whereMonth('tanggal', now()->month)->sum('jumlah');

        // Ulang tahun hari ini
        $ulangTahun = Pengguna::whereMonth('tanggal_lahir', now()->month)
            ->whereDay('tanggal_lahir', now()->day)
            ->where('aktif', true)
            ->pluck('nama')
            ->implode(', ') ?: 'Tidak ada';

        $persenHadir = $staffAktif > 0 ? round(($hadirHariIni / $staffAktif) * 100, 1) : 0;

        return "Tanggal: {$today}\n"
            . "Sekolah: SMA Negeri 2 Jember\n"
            . "Total Staf: {$totalStaff} ({$staffAktif} aktif)\n"
            . "Kehadiran Hari Ini: {$hadirHariIni}/{$staffAktif} ({$persenHadir}%) — Terlambat: {$terlambat}, Sakit: {$sakit}, Izin: {$izinHariIni}, Alpha: {$alpha}\n"
            . "Pengajuan Izin Pending: {$izinPending}\n"
            . "Agenda Hari Ini: {$agendaHariIni}\n"
            . "Agenda 7 Hari: {$agendaMendatang}\n"
            . "Laporan Bulan Ini: {$laporanBulanIni}\n"
            . "Surat Bulan Ini: {$suratBulanIni}\n"
            . "Total Dokumen Arsip: {$totalDokumen}\n"
            . "Anggaran Tahun Ini: Rp " . number_format($totalAnggaran, 0, ',', '.') . "\n"
            . "Pengeluaran Bulan Ini: Rp " . number_format($totalPengeluaran, 0, ',', '.') . "\n"
            . "Ulang Tahun Hari Ini: {$ulangTahun}";
    }

    /**
     * Data kehadiran untuk analisis
     */
    protected function getAttendanceData(string $period): string
    {
        $query = Kehadiran::query();

        if ($period === 'bulan_ini') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        } elseif ($period === 'minggu_ini') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $data = $query->get();
        $totalStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count();

        $stats = [
            'hadir' => $data->where('status', 'hadir')->count(),
            'terlambat' => $data->where('status', 'terlambat')->count(),
            'izin' => $data->where('status', 'izin')->count(),
            'sakit' => $data->where('status', 'sakit')->count(),
            'alpha' => $data->where('status', 'alpha')->count(),
        ];
        $totalRecords = array_sum($stats);

        // Per hari
        $perHari = $data->groupBy(fn ($k) => $k->tanggal->format('Y-m-d'))
            ->map(fn ($group) => [
                'hadir' => $group->whereIn('status', ['hadir', 'terlambat'])->count(),
                'absen' => $group->whereIn('status', ['alpha', 'sakit', 'izin'])->count(),
            ]);

        $hariStr = $perHari->map(fn ($v, $k) => "{$k}: Hadir={$v['hadir']}, Absen={$v['absen']}")->implode("\n");

        return "Periode: {$period}\n"
            . "Total Staf Aktif: {$totalStaff}\n"
            . "Total Record: {$totalRecords}\n"
            . "Hadir: {$stats['hadir']}, Terlambat: {$stats['terlambat']}, Izin: {$stats['izin']}, Sakit: {$stats['sakit']}, Alpha: {$stats['alpha']}\n\n"
            . "Per Hari:\n{$hariStr}";
    }

    /**
     * Bangun system prompt berdasarkan template dokumen
     */
    protected function buildSystemPrompt(?string $template): string
    {
        $base = "Kamu adalah asisten AI ahli di bidang administrasi sekolah untuk SMA Negeri 2 Jember. "
            . "Tugas kamu adalah membuat dokumen administrasi dalam Bahasa Indonesia yang formal dan profesional. "
            . "Hasilkan dokumen dalam format HTML yang rapi (gunakan tag <h2>, <h3>, <p>, <table>, <ol>, <ul>, <strong>, <em>). "
            . "JANGAN gunakan markdown. JANGAN bungkus dalam ```html```. Langsung tulis HTML-nya saja. "
            . "Gunakan data placeholder yang realistis. "
            . "Nama sekolah: SMA Negeri 2 Jember. "
            . "Alamat: Jl. Gajah Mada No. 42, Jember, Jawa Timur. "
            . "Tanggal hari ini: " . now()->translatedFormat('d F Y') . ".";

        $templates = [
            'surat_resmi' => "\nBuat surat resmi dengan kop surat, nomor surat, lampiran, perihal, isi surat, dan tanda tangan kepala sekolah.",
            'surat_keterangan' => "\nBuat surat keterangan resmi dengan kop surat, format yang benar, dan ditandatangani kepala sekolah.",
            'notulen_rapat' => "\nBuat notulen rapat dengan format: hari/tanggal, waktu, tempat, peserta, agenda, pembahasan, keputusan, penutup.",
            'laporan_bulanan' => "\nBuat laporan bulanan administrasi dengan pendahuluan, isi laporan (data kehadiran, dokumen, kegiatan), tabel ringkasan, dan kesimpulan.",
            'laporan_kehadiran' => "\nBuat laporan kehadiran pegawai dengan tabel data, persentase, grafik tren, dan rekomendasi.",
            'sk_kepala_sekolah' => "\nBuat Surat Keputusan (SK) Kepala Sekolah dengan format resmi: menimbang, mengingat, memutuskan, menetapkan.",
            'proposal_kegiatan' => "\nBuat proposal kegiatan dengan latar belakang, tujuan, sasaran, waktu & tempat, susunan panitia, anggaran, dan penutup.",
            'laporan_keuangan' => "\nBuat laporan keuangan dengan tabel pemasukan/pengeluaran, saldo, dan catatan.",
        ];

        return $base . ($templates[$template] ?? "\nBuat dokumen administrasi sesuai permintaan pengguna.");
    }

    /**
     * Bersihkan respons AI — hapus markdown wrapper jika ada
     */
    protected function cleanHtmlResponse(string $text): string
    {
        $text = preg_replace('/^```html\s*/i', '', $text);
        $text = preg_replace('/```\s*$/', '', $text);
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/```\s*$/', '', $text);

        return trim($text);
    }
}
