<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LayananGeminiAi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SiatuAiController extends Controller
{
    public function index()
    {
        return view('admin.siatu-ai.index');
    }

    public function kirim(Request $request)
    {
        $request->validate(['pesan' => 'required|string|max:2000']);

        $ai = new LayananGeminiAi();
        if (!$ai->isConfigured()) {
            return response()->json(['success' => false, 'pesan' => 'API AI belum dikonfigurasi.']);
        }

        $user = Auth::user();
        $systemPrompt = "Kamu adalah SIATU-AI, asisten AI cerdas milik SMA Negeri 2 Jember untuk Sistem Informasi Administrasi Tata Usaha (SIATU). "
            . "Kamu membantu {$user->nama} (peran: Admin/Kepala TU) dengan tugas administrasi sekolah. "
            . "Jawab dalam Bahasa Indonesia yang sopan, profesional, dan informatif. "
            . "Kamu ahli dalam: administrasi sekolah, manajemen kepegawaian, persuratan, inventaris, keuangan sekolah, kurikulum, akreditasi, dan evaluasi kinerja. "
            . "Jika ditanya hal di luar konteks administrasi sekolah, tetap jawab dengan sopan tapi arahkan kembali ke topik administrasi. "
            . "Format jawaban dengan HTML sederhana (paragraf, list, bold) agar mudah dibaca.";

        $fullPrompt = $systemPrompt . "\n\nPertanyaan/Permintaan: " . $request->pesan;

        try {
            $apiKey = config('services.gemini.api_key', '');
            $model = config('services.gemini.model', 'gemini-2.0-flash');
            $response = Http::timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [['role' => 'user', 'parts' => [['text' => $fullPrompt]]]],
                    'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 4096, 'topP' => 0.9],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, tidak ada respons.';
                return response()->json(['success' => true, 'jawaban' => $text]);
            }

            return response()->json(['success' => false, 'pesan' => 'Gagal mendapatkan respons dari AI.']);
        } catch (\Exception $e) {
            Log::error('SIATU-AI Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'pesan' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
}
