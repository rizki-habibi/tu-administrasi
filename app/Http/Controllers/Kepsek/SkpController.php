<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Skp;
use App\Models\Notification;
use Illuminate\Http\Request;

class SkpController extends Controller
{
    public function index(Request $request)
    {
        $query = Skp::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $skps = $query->latest()->paginate(20);
        $pendingCount = Skp::where('status', 'diajukan')->count();

        return view('kepala-sekolah.skp.index', compact('skps', 'pendingCount'));
    }

    public function show(Skp $skp)
    {
        $skp->load('user');
        return view('kepala-sekolah.skp.show', compact('skp'));
    }

    public function approve(Request $request, Skp $skp)
    {
        $request->validate([
            'predikat' => 'required|in:sangat_baik,baik,cukup,kurang,sangat_kurang',
        ]);

        $skp->update([
            'status' => 'dinilai',
            'predikat' => $request->predikat,
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        // Notify staff
        Notification::create([
            'pengguna_id' => $skp->pengguna_id,
            'judul' => 'SKP Telah Dinilai',
            'pesan' => "SKP periode {$skp->periode} tahun {$skp->tahun} telah dinilai dengan predikat " . ucfirst(str_replace('_', ' ', $request->predikat)),
            'jenis' => 'success',
        ]);

        return redirect()->route('kepala-sekolah.skp.index')->with('success', 'SKP berhasil dinilai.');
    }

    public function reject(Request $request, Skp $skp)
    {
        $skp->update([
            'status' => 'revisi',
            'disetujui_oleh' => auth()->id(),
        ]);

        Notification::create([
            'pengguna_id' => $skp->pengguna_id,
            'judul' => 'SKP Perlu Revisi',
            'pesan' => "SKP periode {$skp->periode} tahun {$skp->tahun} perlu direvisi. " . ($request->input('catatan', '')),
            'jenis' => 'warning',
        ]);

        return redirect()->route('kepala-sekolah.skp.index')->with('success', 'SKP dikembalikan untuk revisi.');
    }
}
