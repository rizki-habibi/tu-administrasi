<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::where('pengguna_id', auth()->id())
            ->where('kategori', 'pemeliharaan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        $laporan = $query->latest()->paginate(20)->withQueryString();

        $totalSelesai = Laporan::where('pengguna_id', auth()->id())
            ->where('kategori', 'pemeliharaan')
            ->where('status', 'completed')->count();
        $totalProses = Laporan::where('pengguna_id', auth()->id())
            ->where('kategori', 'pemeliharaan')
            ->whereIn('status', ['submitted', 'reviewed'])->count();

        return view('staf.pramubakti.pemeliharaan-index', compact('laporan', 'totalSelesai', 'totalProses'));
    }

    public function create()
    {
        return view('staf.pramubakti.pemeliharaan-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'prioritas']);
        $data['pengguna_id'] = auth()->id();
        $data['kategori'] = 'pemeliharaan';
        $data['status'] = 'submitted';

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pemeliharaan', 'public');
        }

        Laporan::create($data);

        return redirect()->route('staf.pemeliharaan.index')->with('success', 'Laporan pemeliharaan berhasil dibuat.');
    }

    public function show(Laporan $laporan)
    {
        abort_if($laporan->pengguna_id !== auth()->id() || $laporan->kategori !== 'pemeliharaan', 403);
        return view('staf.pramubakti.pemeliharaan-show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        abort_if($laporan->pengguna_id !== auth()->id() || $laporan->kategori !== 'pemeliharaan', 403);
        return view('staf.pramubakti.pemeliharaan-edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        abort_if($laporan->pengguna_id !== auth()->id() || $laporan->kategori !== 'pemeliharaan', 403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'prioritas']);

        if ($request->hasFile('lampiran')) {
            if ($laporan->lampiran) {
                Storage::disk('public')->delete($laporan->lampiran);
            }
            $data['lampiran'] = $request->file('lampiran')->store('pemeliharaan', 'public');
        }

        $laporan->update($data);

        return redirect()->route('staf.pemeliharaan.index')->with('success', 'Laporan pemeliharaan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        abort_if($laporan->pengguna_id !== auth()->id() || $laporan->kategori !== 'pemeliharaan', 403);

        if ($laporan->lampiran) {
            Storage::disk('public')->delete($laporan->lampiran);
        }

        $laporan->delete();
        return redirect()->route('staf.pemeliharaan.index')->with('success', 'Laporan pemeliharaan berhasil dihapus.');
    }
}
