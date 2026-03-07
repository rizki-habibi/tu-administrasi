<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Skp;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class SkpController extends Controller
{
    use EksporImporTrait;

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
            'catatan' => 'nullable|string|max:1000',
        ]);

        $skp->update([
            'status' => 'disetujui',
            'predikat' => $request->predikat,
            'catatan' => $request->catatan,
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        // Notify staff
        Notifikasi::create([
            'pengguna_id' => $skp->pengguna_id,
            'judul' => 'SKP Disetujui',
            'pesan' => "SKP periode {$skp->periode} tahun {$skp->tahun} telah disetujui dengan predikat " . ucfirst(str_replace('_', ' ', $request->predikat)) . ".",
            'jenis' => 'success',
        ]);

        return redirect()->route('kepala-sekolah.skp.index')->with('success', 'SKP berhasil disetujui.');
    }

    public function reject(Request $request, Skp $skp)
    {
        $request->validate([
            'catatan_revisi' => 'nullable|string|max:1000',
        ]);

        $skp->update([
            'status' => 'ditolak',
            'catatan_revisi' => $request->catatan_revisi,
            'disetujui_oleh' => auth()->id(),
            'ditolak_pada' => now(),
        ]);

        Notifikasi::create([
            'pengguna_id' => $skp->pengguna_id,
            'judul' => 'SKP Ditolak',
            'pesan' => "SKP periode {$skp->periode} tahun {$skp->tahun} ditolak. " . ($request->catatan_revisi ?? ''),
            'jenis' => 'danger',
        ]);

        return redirect()->route('kepala-sekolah.skp.index')->with('success', 'SKP ditolak.');
    }

    public function revisi(Request $request, Skp $skp)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $skp->update([
            'status' => 'revisi',
            'catatan_revisi' => $request->catatan_revisi,
            'disetujui_oleh' => auth()->id(),
            'direvisi_pada' => now(),
        ]);

        Notifikasi::create([
            'pengguna_id' => $skp->pengguna_id,
            'judul' => 'SKP Perlu Revisi',
            'pesan' => "SKP periode {$skp->periode} tahun {$skp->tahun} perlu direvisi: " . $request->catatan_revisi,
            'jenis' => 'warning',
        ]);

        return redirect()->route('kepala-sekolah.skp.index')->with('success', 'SKP dikembalikan untuk revisi.');
    }

    public function export()
    {
        $rows = Skp::with('user')->latest()->get()->map(function ($s, $i) {
            return [
                $i + 1,
                $s->user->nama ?? '-',
                ucfirst(str_replace('_', ' ', $s->periode)),
                $s->tahun,
                $s->sasaran_kinerja,
                $s->nilai_capaian ?? '-',
                ucfirst(str_replace('_', ' ', $s->predikat ?? '-')),
                ucfirst($s->status),
            ];
        });

        return $this->eksporCsv(
            'skp_semua_' . now()->format('Ymd') . '.csv',
            ['No', 'Nama Staf', 'Periode', 'Tahun', 'Sasaran Kinerja', 'Nilai Capaian', 'Predikat', 'Status'],
            $rows
        );
    }
}
