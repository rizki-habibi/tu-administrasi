<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::with('uploader')->latest();
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('search')) $query->where('judul', 'like', '%' . $request->search . '%');

        $documents = $query->paginate(15)->withQueryString();
        return view('staf.dokumen.index', compact('documents'));
    }

    public function show(Dokumen $document)
    {
        return view('staf.dokumen.show', compact('document'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:kurikulum,administrasi,keuangan,kepegawaian,kesiswaan,surat,inventaris,lainnya',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        Dokumen::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'path_file' => $file->store('documents', 'public'),
            'nama_file' => $file->getClientOriginalName(),
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize(),
            'diunggah_oleh' => auth()->id(),
        ]);

        return redirect()->route('staf.dokumen.index')->with('success', 'Dokumen berhasil diupload.');
    }
}
