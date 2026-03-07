<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanAi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PengaturanAiController extends Controller
{
    public function index()
    {
        $configs = PengaturanAi::latest()->get();
        $providers = PengaturanAi::providers();
        $active = PengaturanAi::getActive();

        return view('admin.pengaturan-ai.index', compact('configs', 'providers', 'active'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|in:gemini,openai,anthropic,custom',
            'api_key' => 'required|string|min:10',
            'model' => 'required|string|max:100',
            'base_url' => 'nullable|url|max:500',
            'nama_tampilan' => 'nullable|string|max:100',
        ]);

        $providers = PengaturanAi::providers();
        $providerData = $providers[$request->provider] ?? null;

        // If activating new config, deactivate others
        PengaturanAi::where('aktif', true)->update(['aktif' => false]);

        PengaturanAi::create([
            'provider' => $request->provider,
            'nama_tampilan' => $request->nama_tampilan ?: ($providerData['nama'] ?? $request->provider),
            'api_key' => $request->api_key,
            'model' => $request->model,
            'base_url' => $request->provider === 'custom' ? $request->base_url : ($providerData['base_url'] ?? null),
            'aktif' => true,
            'opsi' => [
                'temperature' => 0.7,
                'max_tokens' => 4096,
            ],
            'diperbarui_oleh' => Auth::id(),
        ]);

        return redirect()->route('admin.pengaturan-ai.index')->with('success', 'Konfigurasi AI berhasil disimpan dan diaktifkan.');
    }

    public function update(Request $request, PengaturanAi $pengaturanAi)
    {
        $request->validate([
            'provider' => 'required|string|in:gemini,openai,anthropic,custom',
            'api_key' => 'nullable|string|min:10',
            'model' => 'required|string|max:100',
            'base_url' => 'nullable|url|max:500',
            'nama_tampilan' => 'nullable|string|max:100',
        ]);

        $providers = PengaturanAi::providers();
        $providerData = $providers[$request->provider] ?? null;

        $data = [
            'provider' => $request->provider,
            'nama_tampilan' => $request->nama_tampilan ?: ($providerData['nama'] ?? $request->provider),
            'model' => $request->model,
            'base_url' => $request->provider === 'custom' ? $request->base_url : ($providerData['base_url'] ?? null),
            'diperbarui_oleh' => Auth::id(),
        ];

        // Only update API key if provided
        if ($request->filled('api_key')) {
            $data['api_key'] = $request->api_key;
        }

        $pengaturanAi->update($data);

        return redirect()->route('admin.pengaturan-ai.index')->with('success', 'Konfigurasi AI berhasil diperbarui.');
    }

    public function activate(PengaturanAi $pengaturanAi)
    {
        PengaturanAi::where('aktif', true)->update(['aktif' => false]);
        $pengaturanAi->update(['aktif' => true, 'diperbarui_oleh' => Auth::id()]);

        return redirect()->route('admin.pengaturan-ai.index')->with('success', 'Konfigurasi "' . $pengaturanAi->nama_tampilan . '" diaktifkan.');
    }

    public function destroy(PengaturanAi $pengaturanAi)
    {
        $nama = $pengaturanAi->nama_tampilan;
        $pengaturanAi->delete();

        return redirect()->route('admin.pengaturan-ai.index')->with('success', 'Konfigurasi "' . $nama . '" berhasil dihapus.');
    }

    /**
     * Test API connection
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'api_key' => 'required|string',
            'model' => 'required|string',
            'base_url' => 'nullable|string',
        ]);

        try {
            $provider = $request->provider;
            $apiKey = $request->api_key;
            $model = $request->model;

            if ($provider === 'gemini') {
                $response = Http::timeout(15)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                    [
                        'contents' => [['role' => 'user', 'parts' => [['text' => 'Halo, ini test koneksi. Jawab singkat: OK']]]],
                        'generationConfig' => ['temperature' => 0.1, 'maxOutputTokens' => 50],
                    ]
                );
            } elseif ($provider === 'openai') {
                $response = Http::timeout(15)->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [['role' => 'user', 'content' => 'Halo, ini test. Jawab: OK']],
                    'max_tokens' => 50,
                ]);
            } elseif ($provider === 'anthropic') {
                $response = Http::timeout(15)->withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'Content-Type' => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model' => $model,
                    'max_tokens' => 50,
                    'messages' => [['role' => 'user', 'content' => 'Halo, ini test. Jawab: OK']],
                ]);
            } elseif ($provider === 'custom') {
                $baseUrl = $request->base_url;
                if (!$baseUrl) {
                    return response()->json(['success' => false, 'message' => 'Base URL diperlukan untuk provider custom.']);
                }
                $response = Http::timeout(15)->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post(rtrim($baseUrl, '/') . '/chat/completions', [
                    'model' => $model,
                    'messages' => [['role' => 'user', 'content' => 'test']],
                    'max_tokens' => 50,
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Provider tidak dikenal.']);
            }

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Koneksi berhasil! API key valid.']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . ($response->json('error.message') ?? 'Status ' . $response->status()),
            ]);
        } catch (\Exception $e) {
            Log::error('AI Test Connection: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
