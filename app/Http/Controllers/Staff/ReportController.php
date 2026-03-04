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
        $query = Report::where('pengguna_id', auth()->id());

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(15);
        return view('staf.laporan.index', compact('reports'));
    }

    public function create()
    {
        return view('staf.laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|in:surat_masuk,surat_keluar,inventaris,keuangan,kegiatan,lainnya',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->except('lampiran');
        $data['pengguna_id'] = auth()->id();
        $data['status'] = $request->input('status', 'submitted');

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('report-attachments', 'public');
        }

        Report::create($data);

        return redirect()->route('staf.laporan.index')->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Report $report)
    {
        if ($report->pengguna_id !== auth()->id()) {
            abort(403);
        }
        return view('staf.laporan.show', compact('report'));
    }

    public function edit(Report $report)
    {
        if ($report->pengguna_id !== auth()->id() || !in_array($report->status, ['draft', 'submitted'])) {
            abort(403);
        }
        return view('staf.laporan.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        if ($report->pengguna_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|in:surat_masuk,surat_keluar,inventaris,keuangan,kegiatan,lainnya',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->except('lampiran');

        if ($request->hasFile('lampiran')) {
            if ($report->lampiran) {
                Storage::disk('public')->delete($report->lampiran);
            }
            $data['lampiran'] = $request->file('lampiran')->store('report-attachments', 'public');
        }

        $report->update($data);

        return redirect()->route('staf.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        if ($report->pengguna_id !== auth()->id()) {
            abort(403);
        }

        if ($report->lampiran) {
            Storage::disk('public')->delete($report->lampiran);
        }

        $report->delete();
        return redirect()->route('staf.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
