<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Resolusi;
use Illuminate\Http\Request;

class ResolusiController extends Controller
{
    public function index(Request $request)
    {
        $query = Resolusi::with('pembuat')->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $resolusi = $query->paginate(15);

        $stats = [
            'total' => Resolusi::count(),
            'berlaku' => Resolusi::where('status', 'berlaku')->count(),
            'draft' => Resolusi::where('status', 'draft')->count(),
        ];

        return view('kepala-sekolah.resolusi.index', compact('resolusi', 'stats'));
    }

    public function create()
    {
        $nomorOtomatis = Resolusi::generateNomor();
        return view('kepala-sekolah.resolusi.create', compact('nomorOtomatis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'latar_belakang' => 'required|string',
            'isi_keputusan' => 'required|string',
            'tindak_lanjut' => 'nullable|string',
            'kategori' => 'required|in:kebijakan,sanksi,penghargaan,mutasi,anggaran,kurikulum,lainnya',
            'status' => 'required|in:draft,berlaku',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_berlaku',
        ]);

        $validated['nomor_resolusi'] = Resolusi::generateNomor();
        $validated['dibuat_oleh'] = auth()->id();

        $resolusi = Resolusi::create($validated);

        LogAktivitas::catat('create', 'resolusi', 'Membuat resolusi: ' . $resolusi->judul, $resolusi);

        return redirect()->route('kepala-sekolah.resolusi.index')
            ->with('success', 'Resolusi/Keputusan berhasil dibuat.');
    }

    public function show(Resolusi $resolusi)
    {
        return view('kepala-sekolah.resolusi.show', compact('resolusi'));
    }

    public function edit(Resolusi $resolusi)
    {
        return view('kepala-sekolah.resolusi.edit', compact('resolusi'));
    }

    public function update(Request $request, Resolusi $resolusi)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'latar_belakang' => 'required|string',
            'isi_keputusan' => 'required|string',
            'tindak_lanjut' => 'nullable|string',
            'kategori' => 'required|in:kebijakan,sanksi,penghargaan,mutasi,anggaran,kurikulum,lainnya',
            'status' => 'required|in:draft,berlaku,dicabut',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_berlaku',
        ]);

        $resolusi->update($validated);
        LogAktivitas::catat('update', 'resolusi', 'Memperbarui resolusi: ' . $resolusi->judul, $resolusi);

        return redirect()->route('kepala-sekolah.resolusi.show', $resolusi)
            ->with('success', 'Resolusi berhasil diperbarui.');
    }

    public function destroy(Resolusi $resolusi)
    {
        LogAktivitas::catat('delete', 'resolusi', 'Menghapus resolusi: ' . $resolusi->judul, $resolusi);
        $resolusi->delete();

        return redirect()->route('kepala-sekolah.resolusi.index')
            ->with('success', 'Resolusi berhasil dihapus.');
    }
}
