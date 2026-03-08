<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LayananGeminiAi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        try {
            $ai = new LayananGeminiAi();
            if (!$ai->isConfigured()) {
                return response()->json(['success' => false, 'pesan' => 'API AI belum dikonfigurasi. Silakan atur di menu Pengaturan AI.']);
            }

            $user = Auth::user();
            $systemPrompt = "Kamu adalah SIMPEG-AI, asisten AI cerdas milik SMA Negeri 2 Jember untuk Sistem Informasi Administrasi Tata Usaha (SIMPEG-SMART). "
                . "Kamu membantu {$user->nama} (peran: Admin/Kepala TU) dengan tugas administrasi sekolah. "
                . "Jawab dalam Bahasa Indonesia yang sopan, profesional, dan informatif. "
                . "Kamu ahli dalam: administrasi sekolah, manajemen kepegawaian, persuratan, inventaris, keuangan sekolah, kurikulum, akreditasi, dan evaluasi kinerja. "
                . "Jika ditanya hal di luar konteks administrasi sekolah, tetap jawab dengan sopan tapi arahkan kembali ke topik administrasi. "
                . "Format jawaban dengan HTML sederhana (paragraf, list, bold) agar mudah dibaca.";

            $fullPrompt = $systemPrompt . "\n\nPertanyaan/Permintaan: " . $request->pesan;

            $jawaban = $ai->assistantChat($request->pesan, $systemPrompt);

            if ($jawaban) {
                return response()->json(['success' => true, 'jawaban' => $jawaban]);
            }

            return response()->json(['success' => false, 'pesan' => 'Gagal mendapatkan respons dari AI. Periksa konfigurasi API.']);
        } catch (\Exception $e) {
            Log::error('SIMPEG-AI Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'pesan' => 'Terjadi kesalahan pada server. Silakan coba lagi.']);
        }
    }
}
