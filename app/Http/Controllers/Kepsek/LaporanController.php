<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = Laporan::with('user');
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        $reports = $query->latest()->paginate(20);
        return view('kepala-sekolah.laporan.index', compact('reports'));
    }

    public function show(Laporan $report)
    {
        $report->load('user');
        return view('kepala-sekolah.laporan.show', compact('report'));
    }

    public function export()
    {
        $rows = Laporan::with('user')->latest()->get()->map(function ($r, $i) {
            return [
                $i + 1,
                $r->judul,
                $r->user->nama ?? '-',
                ucfirst(str_replace('_', ' ', $r->kategori)),
                ucfirst($r->prioritas),
                ucfirst($r->status),
                $r->created_at?->format('d/m/Y'),
            ];
        });

        return $this->eksporCsv(
            'laporan_' . now()->format('Ymd') . '.csv',
            ['No', 'Judul', 'Pembuat', 'Kategori', 'Prioritas', 'Status', 'Tanggal'],
            $rows
        );
    }
}
