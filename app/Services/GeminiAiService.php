<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeminiAiService
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
     * Generate konten dokumen berdasarkan prompt
     */
    public function generateDocument(string $prompt, ?string $template = null): ?string
    {
        if (!$this->isConfigured()) {
            Log::warning('Gemini AI: API key belum dikonfigurasi');
            return null;
        }

        $systemPrompt = $this->buildSystemPrompt($template);

        try {
            $response = Http::timeout(30)->post(
                "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $systemPrompt . "\n\nPermintaan pengguna: " . $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 4096,
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
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text) {
                    return $this->cleanResponse($text);
                }
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
    protected function cleanResponse(string $text): string
    {
        // Hapus ```html ... ``` wrapper
        $text = preg_replace('/^```html\s*/i', '', $text);
        $text = preg_replace('/```\s*$/', '', $text);

        // Hapus ``` wrapper polos
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/```\s*$/', '', $text);

        return trim($text);
    }
}
