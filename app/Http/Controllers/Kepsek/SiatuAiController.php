<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SiatuAiController extends Controller
{
    public function index()
    {
        return view('kepala-sekolah.siatu-ai.index');
    }

    public function kirim(Request $request)
    {
        $request->validate(['pesan' => 'required|string|max:2000']);

        $apiKey = config('services.gemini.api_key', '');
        if (empty($apiKey)) {
            return response()->json(['success' => false, 'pesan' => 'API AI belum dikonfigurasi.']);
        }

        $user = Auth::user();
        $systemPrompt = "Kamu adalah SIMPEG-AI, asisten AI cerdas milik SMA Negeri 2 Jember untuk Sistem Informasi Administrasi Tata Usaha (SIMPEG-SMART). "
            . "Kamu membantu {$user->nama} (peran: Kepala Sekolah) dengan pengambilan keputusan dan monitoring. "
            . "Jawab dalam Bahasa Indonesia yang sopan, profesional, dan strategic. "
            . "Kamu ahli dalam: manajemen sekolah, kebijakan pendidikan, evaluasi kinerja, perencanaan strategis, leadership, dan supervisi. "
            . "Berikan analisis dan rekomendasi yang actionable. "
            . "Format jawaban dengan HTML sederhana (paragraf, list, bold) agar mudah dibaca.";

        $fullPrompt = $systemPrompt . "\n\nPertanyaan/Permintaan: " . $request->pesan;

        try {
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
            Log::error('SIMPEG-AI Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'pesan' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
}
