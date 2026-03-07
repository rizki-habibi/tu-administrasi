<?php

namespace App\Http\Controllers\Shared;

use App\Models\Percakapan;
use App\Models\Pesan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\LayananNotifikasi;

/**
 * Shared Chat logic — digunakan oleh Admin, Staff, dan Kepala Sekolah ChatController.
 */
trait PesanTrait
{
    /**
     * Daftar percakapan
     */
    public function index()
    {
        $user = auth()->user();
        $percakapanIds = DB::table('anggota_percakapan')
            ->where('pengguna_id', $user->id)
            ->pluck('percakapan_id');

        $percakapan = Percakapan::with(['anggota', 'pesanTerakhir.pengirim'])
            ->whereIn('id', $percakapanIds)
            ->get()
            ->sortByDesc(fn ($p) => optional($p->pesanTerakhir)->created_at)
            ->values();

        // Semua user untuk fitur "Pesan Baru"
        $semuaUser = Pengguna::where('id', '!=', $user->id)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        $prefix = $user->getRoutePrefix();
        return view("{$prefix}.chat.index", compact('percakapan', 'semuaUser'));
    }

    /**
     * Tampilkan percakapan + pesan
     */
    public function show(Percakapan $percakapan)
    {
        $user = auth()->user();

        // Pastikan user adalah anggota
        if (!$percakapan->anggota->contains($user->id)) {
            abort(403);
        }

        // Tandai sudah dibaca
        DB::table('anggota_percakapan')
            ->where('percakapan_id', $percakapan->id)
            ->where('pengguna_id', $user->id)
            ->update(['terakhir_dibaca' => now()]);

        $pesan = $percakapan->pesan()
            ->with(['pengirim', 'balasan.pengirim'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Semua percakapan untuk sidebar
        $percakapanIds = DB::table('anggota_percakapan')
            ->where('pengguna_id', $user->id)
            ->pluck('percakapan_id');

        $semuaPercakapan = Percakapan::with(['anggota', 'pesanTerakhir.pengirim'])
            ->whereIn('id', $percakapanIds)
            ->get()
            ->sortByDesc(fn ($p) => optional($p->pesanTerakhir)->created_at)
            ->values();

        $semuaUser = Pengguna::where('id', '!=', $user->id)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        $prefix = $user->getRoutePrefix();
        return view("{$prefix}.chat.show", compact('percakapan', 'pesan', 'semuaPercakapan', 'semuaUser'));
    }

    /**
     * Kirim pesan
     */
    public function kirimPesan(Request $request, Percakapan $percakapan)
    {
        $user = auth()->user();

        if (!$percakapan->anggota->contains($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'isi' => 'required|string|max:5000',
            'balasan_id' => 'nullable|exists:pesan,id',
        ]);

        $pesan = Pesan::create([
            'percakapan_id' => $percakapan->id,
            'pengirim_id' => $user->id,
            'isi' => $request->isi,
            'tipe' => 'teks',
            'balasan_id' => $request->balasan_id,
        ]);

        // Update terakhir dibaca untuk pengirim
        DB::table('anggota_percakapan')
            ->where('percakapan_id', $percakapan->id)
            ->where('pengguna_id', $user->id)
            ->update(['terakhir_dibaca' => now()]);

        // Kirim notifikasi ke anggota percakapan lain
        $this->kirimNotifikasiPesan($percakapan, $user, $request->isi);

        $pesan->load('pengirim', 'balasan.pengirim');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pesan->id,
                'isi' => $pesan->isi,
                'tipe' => $pesan->tipe,
                'file_path' => $pesan->file_path ? asset('storage/' . $pesan->file_path) : null,
                'pengirim_id' => $pesan->pengirim_id,
                'pengirim_nama' => $pesan->pengirim->nama,
                'pengirim_inisial' => strtoupper(substr($pesan->pengirim->nama, 0, 2)),
                'waktu' => $pesan->created_at->format('H:i'),
                'tanggal' => $pesan->created_at->format('d M Y'),
                'balasan' => $pesan->balasan ? [
                    'pengirim' => $pesan->balasan->pengirim->nama,
                    'isi' => \Str::limit($pesan->balasan->isi, 50),
                ] : null,
            ],
        ]);
    }

    /**
     * Kirim foto dari webcam
     */
    public function kirimGambar(Request $request, Percakapan $percakapan)
    {
        $user = auth()->user();

        if (!$percakapan->anggota->contains($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'gambar' => 'required|string',
        ]);

        // Decode base64 image
        $data = $request->gambar;
        if (!preg_match('/^data:image\/(png|jpeg|jpg|webp);base64,/', $data, $matches)) {
            return response()->json(['error' => 'Format gambar tidak valid'], 422);
        }

        $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $data));
        if ($imageData === false) {
            return response()->json(['error' => 'Gagal decode gambar'], 422);
        }

        $filename = 'chat/' . uniqid('cam_') . '.' . $ext;
        Storage::disk('public')->put($filename, $imageData);

        $pesan = Pesan::create([
            'percakapan_id' => $percakapan->id,
            'pengirim_id' => $user->id,
            'isi' => '📷 Foto',
            'tipe' => 'gambar',
            'file_path' => $filename,
            'file_nama' => basename($filename),
        ]);

        DB::table('anggota_percakapan')
            ->where('percakapan_id', $percakapan->id)
            ->where('pengguna_id', $user->id)
            ->update(['terakhir_dibaca' => now()]);

        // Kirim notifikasi ke anggota percakapan lain
        $this->kirimNotifikasiPesan($percakapan, $user, '📷 Mengirim foto');

        $pesan->load('pengirim');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pesan->id,
                'isi' => $pesan->isi,
                'tipe' => $pesan->tipe,
                'file_path' => asset('storage/' . $pesan->file_path),
                'pengirim_id' => $pesan->pengirim_id,
                'pengirim_nama' => $pesan->pengirim->nama,
                'pengirim_inisial' => strtoupper(substr($pesan->pengirim->nama, 0, 2)),
                'waktu' => $pesan->created_at->format('H:i'),
                'tanggal' => $pesan->created_at->format('d M Y'),
                'balasan' => null,
            ],
        ]);
    }

    /**
     * Polling pesan baru
     */
    public function pesanBaru(Request $request, Percakapan $percakapan)
    {
        $user = auth()->user();

        if (!$percakapan->anggota->contains($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $afterId = $request->input('after_id', 0);

        $pesan = $percakapan->pesan()
            ->with(['pengirim', 'balasan.pengirim'])
            ->where('id', '>', $afterId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'isi' => $p->isi,
                'tipe' => $p->tipe,
                'file_path' => $p->file_path ? asset('storage/' . $p->file_path) : null,
                'pengirim_id' => $p->pengirim_id,
                'pengirim_nama' => $p->pengirim->nama,
                'pengirim_inisial' => strtoupper(substr($p->pengirim->nama, 0, 2)),
                'waktu' => $p->created_at->format('H:i'),
                'tanggal' => $p->created_at->format('d M Y'),
                'balasan' => $p->balasan ? [
                    'pengirim' => $p->balasan->pengirim->nama,
                    'isi' => \Str::limit($p->balasan->isi, 50),
                ] : null,
            ]);

        // Update terakhir dibaca
        if ($pesan->count() > 0) {
            DB::table('anggota_percakapan')
                ->where('percakapan_id', $percakapan->id)
                ->where('pengguna_id', $user->id)
                ->update(['terakhir_dibaca' => now()]);
        }

        return response()->json(['data' => $pesan]);
    }

    /**
     * Buat percakapan baru (pribadi atau grup)
     */
    public function buatPercakapan(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'tipe' => 'required|in:pribadi,grup',
            'anggota_ids' => 'required|array|min:1',
            'anggota_ids.*' => 'exists:pengguna,id',
            'nama' => 'nullable|string|max:255',
        ]);

        // Untuk chat pribadi, cek apakah sudah ada
        if ($request->tipe === 'pribadi' && count($request->anggota_ids) === 1) {
            $targetId = $request->anggota_ids[0];
            $existing = $this->findPrivateChat($user->id, $targetId);
            if ($existing) {
                $prefix = $user->getRoutePrefix();
                return response()->json([
                    'success' => true,
                    'redirect' => route("{$prefix}.chat.show", $existing),
                ]);
            }
        }

        $percakapan = Percakapan::create([
            'nama' => $request->tipe === 'grup' ? ($request->nama ?? 'Grup Baru') : null,
            'tipe' => $request->tipe,
            'dibuat_oleh' => $user->id,
        ]);

        // Tambah pembuat sebagai admin
        $percakapan->anggota()->attach($user->id, ['peran' => 'admin']);

        // Tambah anggota lain
        foreach ($request->anggota_ids as $id) {
            if ($id != $user->id) {
                $percakapan->anggota()->attach($id, ['peran' => 'anggota']);
            }
        }

        // Pesan sistem
        if ($request->tipe === 'grup') {
            Pesan::create([
                'percakapan_id' => $percakapan->id,
                'pengirim_id' => $user->id,
                'isi' => $user->nama . ' membuat grup "' . $percakapan->nama . '"',
                'tipe' => 'sistem',
            ]);
        }

        $prefix = $user->getRoutePrefix();
        return response()->json([
            'success' => true,
            'redirect' => route("{$prefix}.chat.show", $percakapan),
        ]);
    }

    /**
     * Jumlah total pesan belum dibaca (untuk badge sidebar)
     */
    public function jumlahBelumDibaca()
    {
        $user = auth()->user();
        $total = 0;

        $anggota = DB::table('anggota_percakapan')
            ->where('pengguna_id', $user->id)
            ->get();

        foreach ($anggota as $a) {
            $query = Pesan::where('percakapan_id', $a->percakapan_id)
                ->where('pengirim_id', '!=', $user->id);
            if ($a->terakhir_dibaca) {
                $query->where('created_at', '>', $a->terakhir_dibaca);
            }
            $total += $query->count();
        }

        return response()->json(['total' => $total]);
    }

    /**
     * Cari chat pribadi yang sudah ada
     */
    private function findPrivateChat(int $userId1, int $userId2): ?Percakapan
    {
        return Percakapan::where('tipe', 'pribadi')
            ->whereHas('anggota', fn ($q) => $q->where('pengguna_id', $userId1))
            ->whereHas('anggota', fn ($q) => $q->where('pengguna_id', $userId2))
            ->first();
    }

    /**
     * Kirim notifikasi pesan ke semua anggota percakapan (kecuali pengirim)
     */
    private function kirimNotifikasiPesan(Percakapan $percakapan, $pengirim, string $isiPesan): void
    {
        try {
            $anggotaIds = DB::table('anggota_percakapan')
                ->where('percakapan_id', $percakapan->id)
                ->where('pengguna_id', '!=', $pengirim->id)
                ->pluck('pengguna_id')
                ->toArray();

            if (empty($anggotaIds)) return;

            $prefix = $pengirim->getRoutePrefix();
            $judul = 'Pesan baru dari ' . $pengirim->nama;
            $ringkasan = \Str::limit($isiPesan, 80);
            $tautan = route("{$prefix}.chat.show", $percakapan);

            LayananNotifikasi::kirimKeBanyak(
                $anggotaIds,
                $judul,
                $ringkasan,
                'event',
                $tautan
            );
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notifikasi pesan: ' . $e->getMessage());
        }
    }
}
