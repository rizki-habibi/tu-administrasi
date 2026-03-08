<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DokumenKurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumKelolaController extends Controller
{
    public function create()
    {
        return view('staf.kurikulum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string',
            'tahun_ajaran' => 'nullable|string|max:20',
            'semester' => 'nullable|in:ganjil,genap',
            'mata_pelajaran' => 'nullable|string|max:255',
            'tingkat_kelas' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
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

    public function edit(DokumenKurikulum $kurikulum)
    {
        return view('staf.kurikulum.edit', compact('kurikulum'));
    }

    public function update(Request $request, DokumenKurikulum $kurikulum)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string',
            'tahun_ajaran' => 'nullable|string|max:20',
            'semester' => 'nullable|in:ganjil,genap',
            'mata_pelajaran' => 'nullable|string|max:255',
            'tingkat_kelas' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['judul', 'jenis', 'tahun_ajaran', 'semester', 'mata_pelajaran', 'tingkat_kelas', 'deskripsi']);

        if ($request->hasFile('file')) {
            if ($kurikulum->path_file) {
                Storage::disk('public')->delete($kurikulum->path_file);
            }
            $data['path_file'] = $request->file('file')->store('kurikulum', 'public');
            $data['nama_file'] = $request->file('file')->getClientOriginalName();
            $data['ukuran_file'] = $request->file('file')->getSize();
        }

        $kurikulum->update($data);

        return redirect()->route('staf.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil diperbarui.');
    }

    public function destroy(DokumenKurikulum $kurikulum)
    {
        if ($kurikulum->path_file) {
            Storage::disk('public')->delete($kurikulum->path_file);
        }

        $kurikulum->delete();
        return redirect()->route('staf.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil dihapus.');
    }
}
