<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\DataSiswa;
use Illuminate\Http\Request;

class KesiswaanKelolaController extends Controller
{
    use EksporImporTrait;

    public function create()
    {
        return view('staf.kesiswaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:data_siswa,nis',
            'nisn' => 'nullable|string|max:20|unique:data_siswa,nisn',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'nama_wali' => 'nullable|string|max:255',
            'telepon_wali' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,pindah,lulus,dikeluarkan',
        ]);

        DataSiswa::create($request->only([
            'nis', 'nisn', 'nama', 'kelas', 'tahun_ajaran', 'jenis_kelamin',
            'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'nama_wali', 'telepon_wali', 'status',
        ]));

        return redirect()->route('staf.kesiswaan.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(DataSiswa $siswa)
    {
        return view('staf.kesiswaan.edit', compact('siswa'));
    }

    public function update(Request $request, DataSiswa $siswa)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:data_siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|string|max:20|unique:data_siswa,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'nama_wali' => 'nullable|string|max:255',
            'telepon_wali' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,pindah,lulus,dikeluarkan',
        ]);

        $siswa->update($request->only([
            'nis', 'nisn', 'nama', 'kelas', 'tahun_ajaran', 'jenis_kelamin',
            'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'nama_wali', 'telepon_wali', 'status',
        ]));

        return redirect()->route('staf.kesiswaan.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(DataSiswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('staf.kesiswaan.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function export()
    {
        $rows = DataSiswa::orderBy('kelas')->orderBy('nama')->get()->map(function ($r, $i) {
            return [
                $i + 1,
                $r->nis,
                $r->nisn ?? '-',
                $r->nama,
                $r->kelas,
                $r->tahun_ajaran,
                $r->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                ucfirst($r->status),
            ];
        });

        return $this->eksporCsv(
            'data_siswa_' . now()->format('Ymd') . '.csv',
            ['No', 'NIS', 'NISN', 'Nama', 'Kelas', 'Tahun Ajaran', 'Jenis Kelamin', 'Status'],
            $rows
        );
    }
}
