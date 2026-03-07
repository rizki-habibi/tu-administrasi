<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\PengaturanPengguna;
use App\Services\LayananNotifikasi;
use Illuminate\Http\Request;

class ApiNotifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ambil notifikasi penting yang belum dibaca (untuk popup)
     */
    public function notifikasiPenting()
    {
        $user = auth()->user();
        $notifikasi = LayananNotifikasi::ambilNotifikasiPenting($user->id);
        $total = LayananNotifikasi::hitungBelumDibaca($user->id);

        return response()->json([
            'notifikasi' => $notifikasi,
            'total_belum_dibaca' => $total,
        ]);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function tandaiDibaca(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $notifikasi = Notifikasi::where('id', $request->id)
            ->where('pengguna_id', auth()->id())
            ->first();

        if ($notifikasi) {
            $notifikasi->update(['sudah_dibaca' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Simpan push subscription
     */
    public function simpanPushSubscription(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $user = auth()->user();
        PengaturanPengguna::atur($user->id, 'push_endpoint', $request->endpoint);
        PengaturanPengguna::atur($user->id, 'push_p256dh', $request->keys['p256dh']);
        PengaturanPengguna::atur($user->id, 'push_auth', $request->keys['auth']);
        PengaturanPengguna::atur($user->id, 'notifikasi_push', 'true');

        return response()->json(['success' => true]);
    }

    /**
     * Hapus push subscription
     */
    public function hapusPushSubscription()
    {
        $user = auth()->user();
        PengaturanPengguna::atur($user->id, 'notifikasi_push', 'false');
        PengaturanPengguna::atur($user->id, 'push_endpoint', '');

        return response()->json(['success' => true]);
    }

    /**
     * Update pengaturan pemberitahuan
     */
    public function updatePengaturanNotifikasi(Request $request)
    {
        $user = auth()->user();

        $keys = ['notifikasi_email', 'notifikasi_push', 'notifikasi_popup', 'notifikasi_popup_delay', 'notifikasi_suara'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                PengaturanPengguna::atur($user->id, $key, $request->input($key));
            }
        }

        return response()->json(['success' => true, 'pesan' => 'Pengaturan pemberitahuan berhasil disimpan.']);
    }

    /**
     * Cek penggunaan storage
     */
    public function cekStorage()
    {
        $info = LayananNotifikasi::cekPenggunaanStorage();
        return response()->json([
            'persentase'       => $info['persentase'],
            'digunakan_format' => $info['terpakai_format'],
            'limit_format'     => $info['limit_format'],
            'hampir_penuh'     => $info['hampir_penuh'],
            'penuh'            => $info['penuh'],
        ]);
    }
}
