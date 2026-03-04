<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRecord::latest();

        if ($request->filled('kelas')) $query->where('kelas', $request->kelas);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate(20)->withQueryString();

        $aktifCount = StudentRecord::where('status', 'aktif')->count();
        $lakiCount = StudentRecord::where('jenis_kelamin', 'L')->where('status', 'aktif')->count();
        $perempuanCount = StudentRecord::where('jenis_kelamin', 'P')->where('status', 'aktif')->count();
        $kelasList = StudentRecord::select('class')->distinct()->orderBy('class')->pluck('class');

        return view('admin.kesiswaan.index', compact('students', 'aktifCount', 'lakiCount', 'perempuanCount', 'kelasList'));
    }

    public function create()
    {
        return view('admin.kesiswaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:data_siswa,nis',
            'nisn' => 'nullable|string|unique:data_siswa,nisn',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'nama_orang_tua' => 'nullable|string',
            'telepon_orang_tua' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'status' => 'required|in:aktif,mutasi_masuk,mutasi_keluar,lulus,do',
        ]);

        $data = $request->except('foto');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('siswa-photos', 'public');
        }

        StudentRecord::create($data);
        return redirect()->route('admin.kesiswaan.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(StudentRecord $kesiswaan)
    {
        $kesiswaan->load(['achievements', 'violations.reporter']);
        return view('admin.kesiswaan.show', compact('kesiswaan'));
    }

    public function edit(StudentRecord $kesiswaan)
    {
        return view('admin.kesiswaan.edit', compact('kesiswaan'));
    }

    public function update(Request $request, StudentRecord $kesiswaan)
    {
        $request->validate([
            'nis' => 'required|string|unique:data_siswa,nis,' . $kesiswaan->id,
            'nisn' => 'nullable|string|unique:data_siswa,nisn,' . $kesiswaan->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:aktif,mutasi_masuk,mutasi_keluar,lulus,do',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($kesiswaan->foto) Storage::disk('public')->delete($kesiswaan->foto);
            $data['foto'] = $request->file('foto')->store('siswa-photos', 'public');
        }

        $kesiswaan->update($data);
        return redirect()->route('admin.kesiswaan.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(StudentRecord $kesiswaan)
    {
        if ($kesiswaan->foto) Storage::disk('public')->delete($kesiswaan->foto);
        $kesiswaan->delete();
        return redirect()->route('admin.kesiswaan.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
