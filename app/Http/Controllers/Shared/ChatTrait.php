<?php

namespace App\Http\Controllers\Shared;

use App\Models\Percakapan;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Shared Chat logic — digunakan oleh Admin, Staff, dan Kepala Sekolah ChatController.
 */
trait ChatTrait
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
        $semuaUser = User::where('id', '!=', $user->id)
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

        $semuaUser = User::where('id', '!=', $user->id)
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

        $pesan->load('pengirim', 'balasan.pengirim');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pesan->id,
                'isi' => $pesan->isi,
                'tipe' => $pesan->tipe,
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
}
