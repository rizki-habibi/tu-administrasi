<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DokumenKurikulum;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        $query = DokumenKurikulum::where('status', 'active');

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->search) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        $documents = $query->latest()->paginate(15);
        return view('staf.kurikulum.index', compact('documents'));
    }

    public function show(DokumenKurikulum $kurikulum)
    {
        return view('staf.kurikulum.show', compact('kurikulum'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $data = $request->only(['judul', 'jenis', 'tahun_ajaran', 'semester', 'mata_pelajaran', 'tingkat_kelas', 'deskripsi']);
        $data['diunggah_oleh'] = auth()->id();
        $data['status'] = 'draft';

        if ($request->hasFile('file')) {
            $data['path_file'] = $request->file('file')->store('kurikulum', 'public');
            $data['nama_file'] = $request->file('file')->getClientOriginalName();
            $data['ukuran_file'] = $request->file('file')->getSize();
        }

        DokumenKurikulum::create($data);
        return redirect()->route('staf.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil diunggah.');
    }
}
