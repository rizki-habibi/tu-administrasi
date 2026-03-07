<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\LogbookMagang;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = LogbookMagang::where('pengguna_id', auth()->id())
            ->latest('tanggal');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $logbook = $query->paginate(15);

        $bulanIni = LogbookMagang::where('pengguna_id', auth()->id())
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $logbookHariIni = LogbookMagang::where('pengguna_id', auth()->id())
            ->whereDate('tanggal', today())
            ->first();

        return view('magang.logbook.index', compact('logbook', 'bulanIni', 'logbookHariIni'));
    }

    public function create()
    {
        $existing = LogbookMagang::where('pengguna_id', auth()->id())
            ->whereDate('tanggal', today())
            ->first();

        if ($existing) {
            return redirect()->route('magang.logbook.edit', $existing)
                ->with('info', 'Logbook hari ini sudah ada, silakan edit.');
        }

        return view('magang.logbook.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:5000',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'hasil' => 'nullable|string|max:2000',
            'kendala' => 'nullable|string|max:2000',
            'rencana_besok' => 'nullable|string|max:2000',
            'status' => 'required|in:draft,final',
        ]);

        $validated['pengguna_id'] = auth()->id();
        $validated['tanggal'] = today();

        LogbookMagang::create($validated);

        return redirect()->route('magang.logbook.index')
            ->with('success', 'Logbook berhasil disimpan.');
    }

    public function show(LogbookMagang $logbook)
    {
        abort_if($logbook->pengguna_id !== auth()->id(), 403);
        return view('magang.logbook.show', compact('logbook'));
    }

    public function edit(LogbookMagang $logbook)
    {
        abort_if($logbook->pengguna_id !== auth()->id(), 403);
        return view('magang.logbook.edit', compact('logbook'));
    }

    public function update(Request $request, LogbookMagang $logbook)
    {
        abort_if($logbook->pengguna_id !== auth()->id(), 403);

        $validated = $request->validate([
            'kegiatan' => 'required|string|max:5000',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'hasil' => 'nullable|string|max:2000',
            'kendala' => 'nullable|string|max:2000',
            'rencana_besok' => 'nullable|string|max:2000',
            'status' => 'required|in:draft,final',
        ]);

        $logbook->update($validated);

        return redirect()->route('magang.logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy(LogbookMagang $logbook)
    {
        abort_if($logbook->pengguna_id !== auth()->id(), 403);
        abort_if($logbook->status === 'final', 403, 'Logbook final tidak dapat dihapus.');

        $logbook->delete();

        return redirect()->route('magang.logbook.index')
            ->with('success', 'Logbook berhasil dihapus.');
    }

    public function export()
    {
        $rows = LogbookMagang::where('pengguna_id', auth()->id())
            ->latest('tanggal')->get()->map(function ($l, $i) {
                return [
                    $i + 1,
                    $l->tanggal?->format('d/m/Y'),
                    $l->jam_mulai ?? '-',
                    $l->jam_selesai ?? '-',
                    $l->kegiatan,
                    $l->hasil ?? '-',
                    $l->kendala ?? '-',
                    $l->rencana_besok ?? '-',
                    ucfirst($l->status),
                ];
            });

        return $this->eksporCsv(
            'logbook_magang_' . now()->format('Ymd') . '.csv',
            ['No', 'Tanggal', 'Jam Mulai', 'Jam Selesai', 'Kegiatan', 'Hasil', 'Kendala', 'Rencana Besok', 'Status'],
            $rows
        );
    }
}
