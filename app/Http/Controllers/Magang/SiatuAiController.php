<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Services\LayananGeminiAi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SiatuAiController extends Controller
{
    public function kirim(Request $request)
    {
        $request->validate(['pesan' => 'required|string|max:2000']);

        try {
            $ai = new LayananGeminiAi();
            if (!$ai->isConfigured()) {
                return response()->json(['success' => false, 'pesan' => 'API AI belum dikonfigurasi. Hubungi Admin untuk mengatur API.']);
            }

            $user = Auth::user();
            $context = "Kamu adalah SIMPEG-AI, asisten AI cerdas milik SMA Negeri 2 Jember untuk Sistem Informasi Administrasi Tata Usaha (SIMPEG-SMART). "
                . "Kamu membantu {$user->nama} (peran: Mahasiswa Magang) dengan tugas magang dan administrasi. "
                . "Jawab dalam Bahasa Indonesia yang sopan, profesional, dan edukatif. "
                . "Kamu ahli dalam: panduan magang, logbook, prosedur administrasi, dan tugas harian. "
                . "Berikan panduan yang jelas dan langkah-langkah konkret. "
                . "Format jawaban dengan HTML sederhana (paragraf, list, bold) agar mudah dibaca.";

            $jawaban = $ai->assistantChat($request->pesan, $context);

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
