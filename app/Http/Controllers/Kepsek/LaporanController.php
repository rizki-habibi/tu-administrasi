<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
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
}
