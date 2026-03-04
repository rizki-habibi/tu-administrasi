<?php

namespace App\Http\Controllers\Admin;

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $reports = $query->latest()->paginate(15);
        return view('admin.laporan.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('user');
        return view('admin.laporan.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate(['status' => 'required|in:draft,submitted,reviewed,completed']);
        $report->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
