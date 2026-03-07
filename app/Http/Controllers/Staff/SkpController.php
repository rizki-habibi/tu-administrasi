<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Skp;
use Illuminate\Http\Request;

class SkpController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = Skp::where('pengguna_id', auth()->id());

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $skps = $query->latest()->paginate(15);
        $tahunList = Skp::where('pengguna_id', auth()->id())->distinct()->pluck('tahun')->sort()->reverse();

        return view('staf.skp.index', compact('skps', 'tahunList'));
    }

    public function create()
    {
        return view('staf.skp.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|in:januari_juni,juli_desember',
            'tahun' => 'required|integer|min:2020|max:2035',
            'sasaran_kinerja' => 'required|string|max:500',
            'indikator_kinerja' => 'required|string|max:500',
            'target_kuantitas' => 'nullable|numeric|min:0',
            'target_kualitas' => 'nullable|numeric|min:0|max:100',
            'target_waktu' => 'nullable|numeric|min:0',
            'realisasi_kuantitas' => 'nullable|numeric|min:0',
            'realisasi_kualitas' => 'nullable|numeric|min:0|max:100',
            'realisasi_waktu' => 'nullable|numeric|min:0',
        ]);

        $skp = Skp::create([
            'pengguna_id' => auth()->id(),
            ...$request->only([
                'periode', 'tahun', 'sasaran_kinerja', 'indikator_kinerja',
                'target_kuantitas', 'target_kualitas', 'target_waktu',
                'realisasi_kuantitas', 'realisasi_kualitas', 'realisasi_waktu',
            ]),
            'status' => 'draft',
        ]);

        $skp->hitungNilaiCapaian();

        return redirect()->route('staf.skp.index')->with('success', 'SKP berhasil dibuat.');
    }

    public function show(Skp $skp)
    {
        if ($skp->pengguna_id !== auth()->id()) {
            abort(403);
        }
        return view('staf.skp.show', compact('skp'));
    }

    public function edit(Skp $skp)
    {
        if ($skp->pengguna_id !== auth()->id()) {
            abort(403);
        }
        if (!in_array($skp->status, ['draft', 'revisi'])) {
            return redirect()->route('staf.skp.show', $skp)->with('error', 'SKP tidak bisa diedit.');
        }
        return view('staf.skp.edit', compact('skp'));
    }

    public function update(Request $request, Skp $skp)
    {
        if ($skp->pengguna_id !== auth()->id()) {
            abort(403);
        }
        if (!in_array($skp->status, ['draft', 'revisi'])) {
            return redirect()->route('staf.skp.show', $skp)->with('error', 'SKP tidak bisa diedit.');
        }

        $request->validate([
            'periode' => 'required|in:januari_juni,juli_desember',
            'tahun' => 'required|integer|min:2020|max:2035',
            'sasaran_kinerja' => 'required|string|max:500',
            'indikator_kinerja' => 'required|string|max:500',
            'target_kuantitas' => 'nullable|numeric|min:0',
            'target_kualitas' => 'nullable|numeric|min:0|max:100',
            'target_waktu' => 'nullable|numeric|min:0',
            'realisasi_kuantitas' => 'nullable|numeric|min:0',
            'realisasi_kualitas' => 'nullable|numeric|min:0|max:100',
            'realisasi_waktu' => 'nullable|numeric|min:0',
        ]);

        $status = $request->input('submit_action') === 'ajukan' ? 'diajukan' : 'draft';

        $skp->update([
            ...$request->only([
                'periode', 'tahun', 'sasaran_kinerja', 'indikator_kinerja',
                'target_kuantitas', 'target_kualitas', 'target_waktu',
                'realisasi_kuantitas', 'realisasi_kualitas', 'realisasi_waktu',
            ]),
            'status' => $status,
        ]);

        $skp->hitungNilaiCapaian();

        $msg = $status === 'diajukan' ? 'SKP berhasil diajukan untuk penilaian.' : 'SKP berhasil diperbarui.';
        return redirect()->route('staf.skp.show', $skp)->with('success', $msg);
    }

    public function destroy(Skp $skp)
    {
        if ($skp->pengguna_id !== auth()->id()) {
            abort(403);
        }
        if ($skp->status !== 'draft') {
            return redirect()->route('staf.skp.index')->with('error', 'Hanya SKP draft yang bisa dihapus.');
        }

        $skp->delete();
        return redirect()->route('staf.skp.index')->with('success', 'SKP berhasil dihapus.');
    }

    public function export()
    {
        $rows = Skp::where('pengguna_id', auth()->id())->latest()->get()->map(function ($s, $i) {
            return [
                $i + 1,
                ucfirst(str_replace('_', ' ', $s->periode)),
                $s->tahun,
                $s->sasaran_kinerja,
                $s->indikator_kinerja,
                $s->target_kuantitas ?? '-',
                $s->realisasi_kuantitas ?? '-',
                $s->nilai_capaian ?? '-',
                ucfirst($s->status),
            ];
        });

        return $this->eksporCsv(
            'skp_saya_' . now()->format('Ymd') . '.csv',
            ['No', 'Periode', 'Tahun', 'Sasaran Kinerja', 'Indikator', 'Target', 'Realisasi', 'Nilai Capaian', 'Status'],
            $rows
        );
    }
}
