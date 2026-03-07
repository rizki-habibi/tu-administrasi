<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DisposisiSurat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $query = DisposisiSurat::with(['surat', 'dariPengguna'])
            ->where('kepada_pengguna_id', auth()->id())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $disposisi = $query->paginate(15);

        $belumDibaca = DisposisiSurat::where('kepada_pengguna_id', auth()->id())
            ->where('status', 'belum_dibaca')->count();

        return view('staf.disposisi.index', compact('disposisi', 'belumDibaca'));
    }

    public function show(DisposisiSurat $disposisi)
    {
        abort_if($disposisi->kepada_pengguna_id !== auth()->id(), 403);

        $disposisi->load(['surat', 'dariPengguna']);

        // Mark as read
        if ($disposisi->status === 'belum_dibaca') {
            $disposisi->update([
                'status' => 'dibaca',
                'dibaca_pada' => now(),
            ]);
        }

        return view('staf.disposisi.show', compact('disposisi'));
    }

    public function proses(Request $request, DisposisiSurat $disposisi)
    {
        abort_if($disposisi->kepada_pengguna_id !== auth()->id(), 403);

        $validated = $request->validate([
            'catatan_tindakan' => 'required|string|max:2000',
        ]);

        $disposisi->update([
            'status' => 'diproses',
            'catatan_tindakan' => $validated['catatan_tindakan'],
        ]);

        LogAktivitas::catat('update', 'disposisi', 'Memproses disposisi surat #' . $disposisi->id, $disposisi);

        return redirect()->route('staf.disposisi.show', $disposisi)
            ->with('success', 'Disposisi sedang diproses.');
    }

    public function selesai(Request $request, DisposisiSurat $disposisi)
    {
        abort_if($disposisi->kepada_pengguna_id !== auth()->id(), 403);

        $validated = $request->validate([
            'catatan_tindakan' => 'required|string|max:2000',
        ]);

        $disposisi->update([
            'status' => 'selesai',
            'catatan_tindakan' => $validated['catatan_tindakan'],
            'selesai_pada' => now(),
        ]);

        LogAktivitas::catat('update', 'disposisi', 'Menyelesaikan disposisi surat #' . $disposisi->id, $disposisi);

        return redirect()->route('staf.disposisi.show', $disposisi)
            ->with('success', 'Disposisi telah selesai.');
    }
}
