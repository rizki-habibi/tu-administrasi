<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\BukuPerpustakaan;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = BukuPerpustakaan::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhere('pengarang', 'like', "%{$request->search}%")
                  ->orWhere('kode_buku', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $buku = $query->latest()->paginate(20)->withQueryString();

        $totalBuku = BukuPerpustakaan::count();
        $totalTersedia = BukuPerpustakaan::sum('jumlah_tersedia');
        $totalNilai = BukuPerpustakaan::whereNotNull('harga')->selectRaw('SUM(harga * jumlah_total) as total')->value('total') ?? 0;

        return view('staf.perpustakaan.buku-index', compact('buku', 'totalBuku', 'totalTersedia', 'totalNilai'));
    }

    public function create()
    {
        return view('staf.perpustakaan.buku-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:20',
            'kategori' => 'required|in:' . implode(',', array_keys(BukuPerpustakaan::KATEGORI)),
            'lokasi_rak' => 'nullable|string|max:100',
            'jumlah_total' => 'required|integer|min:1',
            'harga' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|in:' . implode(',', array_keys(BukuPerpustakaan::SUMBER_DANA)),
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only(['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'isbn', 'kategori', 'lokasi_rak', 'jumlah_total', 'harga', 'sumber_dana', 'kondisi', 'keterangan']);
        $data['kode_buku'] = BukuPerpustakaan::generateKode();
        $data['jumlah_tersedia'] = $request->jumlah_total;
        $data['dibuat_oleh'] = auth()->id();

        BukuPerpustakaan::create($data);

        return redirect()->route('staf.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(BukuPerpustakaan $buku)
    {
        $buku->load('peminjaman.pencatat');
        return view('staf.perpustakaan.buku-show', compact('buku'));
    }

    public function edit(BukuPerpustakaan $buku)
    {
        return view('staf.perpustakaan.buku-edit', compact('buku'));
    }

    public function update(Request $request, BukuPerpustakaan $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:20',
            'kategori' => 'required|in:' . implode(',', array_keys(BukuPerpustakaan::KATEGORI)),
            'lokasi_rak' => 'nullable|string|max:100',
            'jumlah_total' => 'required|integer|min:1',
            'harga' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|in:' . implode(',', array_keys(BukuPerpustakaan::SUMBER_DANA)),
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only(['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'isbn', 'kategori', 'lokasi_rak', 'jumlah_total', 'harga', 'sumber_dana', 'kondisi', 'keterangan']);

        $sedangDipinjam = $buku->peminjaman()->where('status', 'dipinjam')->count();
        $data['jumlah_tersedia'] = max(0, $request->jumlah_total - $sedangDipinjam);

        $buku->update($data);

        return redirect()->route('staf.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(BukuPerpustakaan $buku)
    {
        if ($buku->peminjaman()->where('status', 'dipinjam')->exists()) {
            return back()->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam.');
        }

        $buku->delete();
        return redirect()->route('staf.buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    public function export()
    {
        $rows = BukuPerpustakaan::latest()->get()->map(function ($r, $i) {
            return [
                $i + 1,
                $r->kode_buku,
                $r->judul,
                $r->pengarang,
                $r->penerbit ?? '-',
                $r->tahun_terbit ?? '-',
                BukuPerpustakaan::KATEGORI[$r->kategori] ?? $r->kategori,
                $r->lokasi_rak ?? '-',
                $r->jumlah_total,
                $r->jumlah_tersedia,
                $r->harga ? number_format($r->harga, 0, ',', '.') : '-',
                ucfirst(str_replace('_', ' ', $r->kondisi)),
            ];
        });

        return $this->eksporCsv(
            'daftar_buku_' . now()->format('Ymd') . '.csv',
            ['No', 'Kode', 'Judul', 'Pengarang', 'Penerbit', 'Tahun', 'Kategori', 'Rak', 'Total', 'Tersedia', 'Harga', 'Kondisi'],
            $rows
        );
    }
}
