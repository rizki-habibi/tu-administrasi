<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\CatatanHarian;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class CatatanHarianController extends Controller
{
    public function index(Request $request)
    {
        $query = CatatanHarian::where('pengguna_id', auth()->id())
            ->latest('tanggal');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $catatan = $query->paginate(15);

        // Statistik
        $bulanIni = CatatanHarian::where('pengguna_id', auth()->id())
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $hariKerja = now()->day; // simplified
        $catatanHariIni = CatatanHarian::where('pengguna_id', auth()->id())
            ->whereDate('tanggal', today())
            ->first();

        return view('staf.catatan-harian.index', compact('catatan', 'bulanIni', 'hariKerja', 'catatanHariIni'));
    }

    public function create()
    {
        $existing = CatatanHarian::where('pengguna_id', auth()->id())
            ->whereDate('tanggal', today())
            ->first();

        if ($existing) {
            return redirect()->route('staf.catatan-harian.edit', $existing)
                ->with('info', 'Catatan hari ini sudah ada, silakan edit.');
        }

        return view('staf.catatan-harian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:5000',
            'hasil' => 'nullable|string|max:2000',
            'kendala' => 'nullable|string|max:2000',
            'rencana_besok' => 'nullable|string|max:2000',
            'status' => 'required|in:draft,final',
        ]);

        $validated['pengguna_id'] = auth()->id();
        $validated['tanggal'] = today();

        $catatan = CatatanHarian::create($validated);
        LogAktivitas::catat('create', 'catatan_harian', 'Membuat catatan harian tanggal ' . today()->format('d/m/Y'), $catatan);

        return redirect()->route('staf.catatan-harian.index')
            ->with('success', 'Catatan harian berhasil disimpan.');
    }

    public function show(CatatanHarian $catatanHarian)
    {
        abort_if($catatanHarian->pengguna_id !== auth()->id(), 403);
        return view('staf.catatan-harian.show', compact('catatanHarian'));
    }

    public function edit(CatatanHarian $catatanHarian)
    {
        abort_if($catatanHarian->pengguna_id !== auth()->id(), 403);
        return view('staf.catatan-harian.edit', compact('catatanHarian'));
    }

    public function update(Request $request, CatatanHarian $catatanHarian)
    {
        abort_if($catatanHarian->pengguna_id !== auth()->id(), 403);

        $validated = $request->validate([
            'kegiatan' => 'required|string|max:5000',
            'hasil' => 'nullable|string|max:2000',
            'kendala' => 'nullable|string|max:2000',
            'rencana_besok' => 'nullable|string|max:2000',
            'status' => 'required|in:draft,final',
        ]);

        $catatanHarian->update($validated);

        return redirect()->route('staf.catatan-harian.index')
            ->with('success', 'Catatan harian berhasil diperbarui.');
    }

    public function destroy(CatatanHarian $catatanHarian)
    {
        abort_if($catatanHarian->pengguna_id !== auth()->id(), 403);
        $catatanHarian->delete();

        return redirect()->route('staf.catatan-harian.index')
            ->with('success', 'Catatan harian berhasil dihapus.');
    }
}
