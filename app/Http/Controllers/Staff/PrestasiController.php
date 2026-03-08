<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DataSiswa;
use App\Models\PrestasiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = PrestasiSiswa::with('student')->latest();

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhereHas('student', fn($s) => $s->where('nama', 'like', "%{$request->search}%"));
            });
        }

        $prestasi = $query->paginate(20)->withQueryString();
        return view('staf.kesiswaan.prestasi-index', compact('prestasi'));
    }

    public function create()
    {
        $siswa = DataSiswa::where('status', 'aktif')->orderBy('nama')->get();
        return view('staf.kesiswaan.prestasi-create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:data_siswa,id',
            'judul' => 'required|string|max:255',
            'tingkat' => 'required|in:sekolah,kecamatan,kabupaten,provinsi,nasional,internasional',
            'jenis' => 'required|in:akademik,non_akademik,olahraga,seni,lainnya',
            'tanggal' => 'required|date',
            'penyelenggara' => 'nullable|string|max:255',
            'hasil' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->only(['siswa_id', 'judul', 'tingkat', 'jenis', 'tanggal', 'penyelenggara', 'hasil']);

        if ($request->hasFile('file')) {
            $data['path_file'] = $request->file('file')->store('prestasi-siswa', 'public');
        }

        PrestasiSiswa::create($data);

        return redirect()->route('staf.prestasi.index')->with('success', 'Prestasi siswa berhasil dicatat.');
    }

    public function show(PrestasiSiswa $prestasi)
    {
        $prestasi->load('student');
        return view('staf.kesiswaan.prestasi-show', compact('prestasi'));
    }
}
