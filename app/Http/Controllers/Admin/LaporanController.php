<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with('user');

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

    public function create()
    {
        return view('admin.laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|in:surat_masuk,surat_keluar,inventaris,keuangan,kegiatan,lainnya',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'status'    => 'required|in:draft,submitted,reviewed,completed',
            'lampiran'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
        ]);

        $data = [
            'pengguna_id' => auth()->id(),
            'judul'       => $validated['judul'],
            'deskripsi'   => $validated['deskripsi'],
            'kategori'    => $validated['kategori'],
            'prioritas'   => $validated['prioritas'],
            'status'      => $validated['status'],
        ];

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('laporan', 'public');
        }

        Laporan::create($data);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function show(Laporan $report)
    {
        $report->load('user');
        return view('admin.laporan.show', compact('report'));
    }

    public function edit(Laporan $report)
    {
        return view('admin.laporan.edit', compact('report'));
    }

    public function update(Request $request, Laporan $report)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|in:surat_masuk,surat_keluar,inventaris,keuangan,kegiatan,lainnya',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'status'    => 'required|in:draft,submitted,reviewed,completed',
            'lampiran'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
        ]);

        $data = [
            'judul'     => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'kategori'  => $validated['kategori'],
            'prioritas' => $validated['prioritas'],
            'status'    => $validated['status'],
        ];

        if ($request->hasFile('lampiran')) {
            if ($report->lampiran) {
                Storage::disk('public')->delete($report->lampiran);
            }
            $data['lampiran'] = $request->file('lampiran')->store('laporan', 'public');
        }

        $report->update($data);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $report)
    {
        if ($report->lampiran) {
            Storage::disk('public')->delete($report->lampiran);
        }

        $report->delete();

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Laporan $report)
    {
        $request->validate(['status' => 'required|in:draft,submitted,reviewed,completed']);
        $report->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
