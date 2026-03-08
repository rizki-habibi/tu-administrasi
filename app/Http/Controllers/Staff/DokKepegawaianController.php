<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\DokumenKepegawaian;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokKepegawaianController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = DokumenKepegawaian::with('pengguna')->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhere('nomor_dokumen', 'like', "%{$request->search}%")
                  ->orWhereHas('pengguna', fn($p) => $p->where('nama', 'like', "%{$request->search}%"));
            });
        }

        $dokumen = $query->paginate(20)->withQueryString();
        return view('staf.kepegawaian.dok-index', compact('dokumen'));
    }

    public function create()
    {
        $pegawai = Pengguna::orderBy('nama')->get();
        return view('staf.kepegawaian.dok-create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(DokumenKepegawaian::KATEGORI)),
            'nomor_dokumen' => 'nullable|string|max:100',
            'tanggal_dokumen' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $data = $request->only(['pengguna_id', 'judul', 'kategori', 'nomor_dokumen', 'tanggal_dokumen', 'keterangan']);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('dokumen-kepegawaian', 'public');
            $data['file_type'] = $request->file('file')->getClientMimeType();
            $data['file_size'] = $request->file('file')->getSize();
        }

        DokumenKepegawaian::create($data);

        return redirect()->route('staf.dok-kepegawaian.index')->with('success', 'Dokumen kepegawaian berhasil ditambahkan.');
    }

    public function show(DokumenKepegawaian $dokumen)
    {
        $dokumen->load('pengguna');
        return view('staf.kepegawaian.dok-show', compact('dokumen'));
    }

    public function edit(DokumenKepegawaian $dokumen)
    {
        $pegawai = Pengguna::orderBy('nama')->get();
        return view('staf.kepegawaian.dok-edit', compact('dokumen', 'pegawai'));
    }

    public function update(Request $request, DokumenKepegawaian $dokumen)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(DokumenKepegawaian::KATEGORI)),
            'nomor_dokumen' => 'nullable|string|max:100',
            'tanggal_dokumen' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $data = $request->only(['pengguna_id', 'judul', 'kategori', 'nomor_dokumen', 'tanggal_dokumen', 'keterangan']);

        if ($request->hasFile('file')) {
            if ($dokumen->file_path) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            $data['file_path'] = $request->file('file')->store('dokumen-kepegawaian', 'public');
            $data['file_type'] = $request->file('file')->getClientMimeType();
            $data['file_size'] = $request->file('file')->getSize();
        }

        $dokumen->update($data);

        return redirect()->route('staf.dok-kepegawaian.index')->with('success', 'Dokumen kepegawaian berhasil diperbarui.');
    }

    public function destroy(DokumenKepegawaian $dokumen)
    {
        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();
        return redirect()->route('staf.dok-kepegawaian.index')->with('success', 'Dokumen kepegawaian berhasil dihapus.');
    }

    public function export()
    {
        $rows = DokumenKepegawaian::with('pengguna')->latest()->get()->map(function ($r, $i) {
            return [
                $i + 1,
                $r->pengguna?->nama ?? '-',
                $r->judul,
                DokumenKepegawaian::KATEGORI[$r->kategori] ?? $r->kategori,
                $r->nomor_dokumen ?? '-',
                $r->tanggal_dokumen?->format('d/m/Y') ?? '-',
            ];
        });

        return $this->eksporCsv(
            'dokumen_kepegawaian_' . now()->format('Ymd') . '.csv',
            ['No', 'Pegawai', 'Judul', 'Kategori', 'No Dokumen', 'Tgl Dokumen'],
            $rows
        );
    }
}
