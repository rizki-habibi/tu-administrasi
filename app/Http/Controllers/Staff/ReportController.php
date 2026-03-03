<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::where('user_id', auth()->id());

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(15);
        return view('staff.report.index', compact('reports'));
    }

    public function create()
    {
        return view('staff.report.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:surat_masuk,surat_keluar,inventaris,keuangan,kegiatan,lainnya',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->except('attachment');
        $data['user_id'] = auth()->id();
        $data['status'] = $request->input('status', 'submitted');

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('report-attachments', 'public');
        }

        Report::create($data);

        return redirect()->route('staff.report.index')->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }
        return view('staff.report.show', compact('report'));
    }

    public function edit(Report $report)
    {
        if ($report->user_id !== auth()->id() || !in_array($report->status, ['draft', 'submitted'])) {
            abort(403);
        }
        return view('staff.report.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:surat_masuk,surat_keluar,inventaris,keuangan,kegiatan,lainnya',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->except('attachment');

        if ($request->hasFile('attachment')) {
            if ($report->attachment) {
                Storage::disk('public')->delete($report->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('report-attachments', 'public');
        }

        $report->update($data);

        return redirect()->route('staff.report.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        if ($report->attachment) {
            Storage::disk('public')->delete($report->attachment);
        }

        $report->delete();
        return redirect()->route('staff.report.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
