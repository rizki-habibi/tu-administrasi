<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanHarian;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotuleController extends Controller
{
    /**
     * Ambil notule/catatan kegiatan untuk tanggal tertentu (JSON).
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $catatan = CatatanHarian::with('pengguna:id,nama,jabatan')
            ->whereDate('tanggal', $tanggal)
            ->latest()
            ->get();

        return response()->json([
            'tanggal' => $tanggal,
            'catatan' => $catatan,
            'total'   => $catatan->count(),
        ]);
    }

    /**
     * Simpan notule kegiatan baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'       => 'required|date',
            'kegiatan'      => 'required|string|max:1000',
            'hasil'         => 'nullable|string|max:1000',
            'kendala'       => 'nullable|string|max:1000',
            'rencana_besok' => 'nullable|string|max:1000',
            'status'        => 'in:draft,final',
        ]);

        $data['pengguna_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'final';

        $catatan = CatatanHarian::updateOrCreate(
            ['pengguna_id' => auth()->id(), 'tanggal' => $data['tanggal']],
            $data
        );

        // Buat notifikasi jika status final
        if ($catatan->status === 'final') {
            Notifikasi::create([
                'pengguna_id' => auth()->id(),
                'judul'       => 'Notule Kegiatan ' . \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y'),
                'pesan'       => 'Catatan kegiatan: ' . \Illuminate\Support\Str::limit($data['kegiatan'], 80),
                'jenis'       => 'event',
                'tautan'      => route('admin.beranda'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notule kegiatan berhasil disimpan',
            'catatan' => $catatan->load('pengguna:id,nama,jabatan'),
        ]);
    }

    /**
     * Hapus notule kegiatan.
     */
    public function destroy(CatatanHarian $notule)
    {
        $notule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notule berhasil dihapus',
        ]);
    }
}
