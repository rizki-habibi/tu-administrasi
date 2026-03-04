<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user');
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        $reports = $query->latest()->paginate(20);
        return view('kepala-sekolah.laporan.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('user');
        return view('kepala-sekolah.laporan.show', compact('report'));
    }
}
