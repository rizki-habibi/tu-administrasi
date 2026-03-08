<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DataSiswa;
use App\Models\PelanggaranSiswa;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = PelanggaranSiswa::with(['student', 'reporter'])->latest();

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('deskripsi', 'like', "%{$request->search}%")
                  ->orWhereHas('student', fn($s) => $s->where('nama', 'like', "%{$request->search}%"));
            });
        }

        $pelanggaran = $query->paginate(20)->withQueryString();
        return view('staf.kesiswaan.pelanggaran-index', compact('pelanggaran'));
    }

    public function create()
    {
        $siswa = DataSiswa::where('status', 'aktif')->orderBy('nama')->get();
        return view('staf.kesiswaan.pelanggaran-create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:data_siswa,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:ringan,sedang,berat',
            'deskripsi' => 'required|string',
            'tindakan' => 'nullable|string',
        ]);

        PelanggaranSiswa::create([
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'tindakan' => $request->tindakan,
            'dilaporkan_oleh' => auth()->id(),
        ]);

        return redirect()->route('staf.pelanggaran.index')->with('success', 'Pelanggaran siswa berhasil dicatat.');
    }

    public function show(PelanggaranSiswa $pelanggaran)
    {
        $pelanggaran->load(['student', 'reporter']);
        return view('staf.kesiswaan.pelanggaran-show', compact('pelanggaran'));
    }
}
