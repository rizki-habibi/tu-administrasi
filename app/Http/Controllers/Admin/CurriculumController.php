<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurriculumDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $query = CurriculumDocument::with('uploader')->latest();

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('academic_year')) $query->where('academic_year', $request->academic_year);
        if ($request->filled('search')) $query->where('title', 'like', '%' . $request->search . '%');

        $documents = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => CurriculumDocument::count(),
            'rpp' => CurriculumDocument::where('type', 'rpp')->count(),
            'silabus' => CurriculumDocument::where('type', 'silabus')->count(),
            'active' => CurriculumDocument::where('status', 'active')->count(),
        ];

        return view('admin.kurikulum.index', compact('documents', 'stats'));
    }

    public function create()
    {
        return view('admin.kurikulum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:kalender_pendidikan,jadwal_pelajaran,rpp,silabus,modul_ajar,kisi_kisi,analisis_butir_soal,berita_acara_ujian,daftar_nilai,rekap_nilai,leger,raport',
            'academic_year' => 'required|string',
            'semester' => 'nullable|in:ganjil,genap',
            'subject' => 'nullable|string|max:255',
            'class_level' => 'nullable|string|max:10',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');

        CurriculumDocument::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'subject' => $request->subject,
            'class_level' => $request->class_level,
            'file_path' => $file->store('kurikulum', 'public'),
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'active',
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil ditambahkan.');
    }

    public function show(CurriculumDocument $kurikulum)
    {
        $kurikulum->load('uploader');
        return view('admin.kurikulum.show', compact('kurikulum'));
    }

    public function edit(CurriculumDocument $kurikulum)
    {
        return view('admin.kurikulum.edit', compact('kurikulum'));
    }

    public function update(Request $request, CurriculumDocument $kurikulum)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required',
            'academic_year' => 'required|string',
            'semester' => 'nullable|in:ganjil,genap',
            'subject' => 'nullable|string|max:255',
            'class_level' => 'nullable|string|max:10',
            'file' => 'nullable|file|max:10240',
            'status' => 'nullable|in:draft,active,archived',
        ]);

        $data = $request->except('file');

        if ($request->hasFile('file')) {
            if ($kurikulum->file_path) Storage::disk('public')->delete($kurikulum->file_path);
            $file = $request->file('file');
            $data['file_path'] = $file->store('kurikulum', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();
        }

        $kurikulum->update($data);
        return redirect()->route('admin.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil diperbarui.');
    }

    public function destroy(CurriculumDocument $kurikulum)
    {
        if ($kurikulum->file_path) Storage::disk('public')->delete($kurikulum->file_path);
        $kurikulum->delete();
        return redirect()->route('admin.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil dihapus.');
    }
}
