<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenKurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        $query = DokumenKurikulum::with('uploader')->latest();

        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);
        if ($request->filled('tahun_ajaran')) $query->where('tahun_ajaran', $request->tahun_ajaran);
        if ($request->filled('search')) $query->where('judul', 'like', '%' . $request->search . '%');

        $documents = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => DokumenKurikulum::count(),
            'rpp' => DokumenKurikulum::where('jenis', 'rpp')->count(),
            'silabus' => DokumenKurikulum::where('jenis', 'silabus')->count(),
            'active' => DokumenKurikulum::where('status', 'active')->count(),
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
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|in:kalender_pendidikan,jadwal_pelajaran,rpp,silabus,modul_ajar,kisi_kisi,analisis_butir_soal,berita_acara_ujian,daftar_nilai,rekap_nilai,leger,raport',
            'tahun_ajaran' => 'required|string',
            'semester' => 'nullable|in:ganjil,genap',
            'mata_pelajaran' => 'nullable|string|max:255',
            'tingkat_kelas' => 'nullable|string|max:10',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');

        DokumenKurikulum::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'mata_pelajaran' => $request->mata_pelajaran,
            'tingkat_kelas' => $request->tingkat_kelas,
            'path_file' => $file->store('kurikulum', 'public'),
            'nama_file' => $file->getClientOriginalName(),
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize(),
            'status' => 'active',
            'diunggah_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil ditambahkan.');
    }

    public function show(DokumenKurikulum $kurikulum)
    {
        $kurikulum->load('uploader');
        return view('admin.kurikulum.show', compact('kurikulum'));
    }

    public function edit(DokumenKurikulum $kurikulum)
    {
        return view('admin.kurikulum.edit', compact('kurikulum'));
    }

    public function update(Request $request, DokumenKurikulum $kurikulum)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required',
            'tahun_ajaran' => 'required|string',
            'semester' => 'nullable|in:ganjil,genap',
            'mata_pelajaran' => 'nullable|string|max:255',
            'tingkat_kelas' => 'nullable|string|max:10',
            'file' => 'nullable|file|max:10240',
            'status' => 'nullable|in:draft,active,archived',
        ]);

        $data = $request->except('file');

        if ($request->hasFile('file')) {
            if ($kurikulum->path_file) Storage::disk('public')->delete($kurikulum->path_file);
            $file = $request->file('file');
            $data['path_file'] = $file->store('kurikulum', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['tipe_file'] = $file->getClientOriginalExtension();
            $data['ukuran_file'] = $file->getSize();
        }

        $kurikulum->update($data);
        return redirect()->route('admin.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil diperbarui.');
    }

    public function destroy(DokumenKurikulum $kurikulum)
    {
        if ($kurikulum->path_file) Storage::disk('public')->delete($kurikulum->path_file);
        $kurikulum->delete();
        return redirect()->route('admin.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil dihapus.');
    }
}
