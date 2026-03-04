<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('uploader')->latest();

        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('search')) $query->where('judul', 'like', '%' . $request->search . '%');

        $documents = $query->paginate(15)->withQueryString();
        return view('admin.dokumen.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.dokumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:kurikulum,administrasi,keuangan,kepegawaian,kesiswaan,surat,inventaris,lainnya',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'path_file' => $path,
            'nama_file' => $file->getClientOriginalName(),
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize(),
            'diunggah_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diupload.');
    }

    public function show(Document $document)
    {
        return view('admin.dokumen.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('admin.dokumen.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:kurikulum,administrasi,keuangan,kepegawaian,kesiswaan,surat,inventaris,lainnya',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'kategori']);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($document->path_file);
            $file = $request->file('file');
            $data['path_file'] = $file->store('documents', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['tipe_file'] = $file->getClientOriginalExtension();
            $data['ukuran_file'] = $file->getSize();
        }

        $document->update($data);
        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->path_file);
        $document->delete();
        return redirect()->route('admin.dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $documents = Document::with('uploader')
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->latest()->get();

        $format = $request->get('format', 'csv');

        if ($format === 'csv' || $format === 'excel') {
            $filename = 'dokumen_' . now()->format('Ymd_His') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

            $callback = function() use ($documents) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['No', 'Judul', 'Kategori', 'File', 'Ukuran', 'Diupload Oleh', 'Tanggal']);
                foreach ($documents as $i => $doc) {
                    fputcsv($file, [
                        $i + 1, $doc->judul, ucfirst($doc->kategori), $doc->nama_file,
                        $doc->file_size_formatted, $doc->uploader->nama ?? '-', $doc->created_at->format('d/m/Y H:i'),
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        // PDF / Print
        return view('admin.dokumen.cetak', compact('documents'));
    }
}
