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

        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('search')) $query->where('title', 'like', '%' . $request->search . '%');

        $documents = $query->paginate(15)->withQueryString();
        return view('admin.document.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.document.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:kurikulum,administrasi,keuangan,kepegawaian,kesiswaan,surat,inventaris,lainnya',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.document.index')->with('success', 'Dokumen berhasil diupload.');
    }

    public function show(Document $document)
    {
        return view('admin.document.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('admin.document.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:kurikulum,administrasi,keuangan,kepegawaian,kesiswaan,surat,inventaris,lainnya',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'category']);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($document->file_path);
            $file = $request->file('file');
            $data['file_path'] = $file->store('documents', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);
        return redirect()->route('admin.document.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->route('admin.document.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $documents = Document::with('uploader')
            ->when($request->category, fn($q) => $q->where('category', $request->category))
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
                        $i + 1, $doc->title, ucfirst($doc->category), $doc->file_name,
                        $doc->file_size_formatted, $doc->uploader->name ?? '-', $doc->created_at->format('d/m/Y H:i'),
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        // PDF / Print
        return view('admin.document.print', compact('documents'));
    }
}
