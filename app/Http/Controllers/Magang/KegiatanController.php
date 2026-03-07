<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\KegiatanMagang;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = KegiatanMagang::where('pengguna_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kegiatan = $query->latest()->paginate(15);

        $stats = [
            'belum_mulai' => KegiatanMagang::where('pengguna_id', auth()->id())->where('status', 'belum_mulai')->count(),
            'berlangsung' => KegiatanMagang::where('pengguna_id', auth()->id())->where('status', 'berlangsung')->count(),
            'selesai' => KegiatanMagang::where('pengguna_id', auth()->id())->where('status', 'selesai')->count(),
        ];

        return view('magang.kegiatan.index', compact('kegiatan', 'stats'));
    }

    public function create()
    {
        return view('magang.kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:5000',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $validated['pengguna_id'] = auth()->id();
        $validated['status'] = 'belum_mulai';

        KegiatanMagang::create($validated);

        return redirect()->route('magang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(KegiatanMagang $kegiatan)
    {
        abort_if($kegiatan->pengguna_id !== auth()->id(), 403);
        return view('magang.kegiatan.show', compact('kegiatan'));
    }

    public function edit(KegiatanMagang $kegiatan)
    {
        abort_if($kegiatan->pengguna_id !== auth()->id(), 403);
        return view('magang.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, KegiatanMagang $kegiatan)
    {
        abort_if($kegiatan->pengguna_id !== auth()->id(), 403);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:5000',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:belum_mulai,berlangsung,selesai',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $kegiatan->update($validated);

        return redirect()->route('magang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(KegiatanMagang $kegiatan)
    {
        abort_if($kegiatan->pengguna_id !== auth()->id(), 403);

        $kegiatan->delete();

        return redirect()->route('magang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
