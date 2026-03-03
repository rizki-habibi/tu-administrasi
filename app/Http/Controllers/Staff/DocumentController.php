<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('uploader')->latest();
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('search')) $query->where('title', 'like', '%' . $request->search . '%');

        $documents = $query->paginate(15)->withQueryString();
        return view('staff.document.index', compact('documents'));
    }

    public function show(Document $document)
    {
        return view('staff.document.show', compact('document'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:kurikulum,administrasi,keuangan,kepegawaian,kesiswaan,surat,inventaris,lainnya',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'file_path' => $file->store('documents', 'public'),
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('staff.document.index')->with('success', 'Dokumen berhasil diupload.');
    }
}
