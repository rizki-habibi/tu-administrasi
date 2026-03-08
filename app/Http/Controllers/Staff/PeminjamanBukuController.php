<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BukuPerpustakaan;
use App\Models\PeminjamanBuku;
use Illuminate\Http\Request;

class PeminjamanBukuController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanBuku::with(['buku', 'pencatat'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_peminjam', 'like', "%{$request->search}%")
                  ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$request->search}%"));
            });
        }

        $peminjaman = $query->paginate(20)->withQueryString();

        $totalDipinjam = PeminjamanBuku::where('status', 'dipinjam')->count();
        $totalTerlambat = PeminjamanBuku::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())->count();

        return view('staf.perpustakaan.peminjaman-index', compact('peminjaman', 'totalDipinjam', 'totalTerlambat'));
    }

    public function create()
    {
        $bukuTersedia = BukuPerpustakaan::where('jumlah_tersedia', '>', 0)->orderBy('judul')->get();
        return view('staf.perpustakaan.peminjaman-create', compact('bukuTersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku_perpustakaan,id',
            'nama_peminjam' => 'required|string|max:255',
            'kelas' => 'nullable|string|max:50',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan' => 'nullable|string',
        ]);

        $buku = BukuPerpustakaan::findOrFail($request->buku_id);

        if ($buku->jumlah_tersedia < 1) {
            return back()->with('error', 'Stok buku habis.')->withInput();
        }

        PeminjamanBuku::create([
            'buku_id' => $request->buku_id,
            'nama_peminjam' => $request->nama_peminjam,
            'kelas' => $request->kelas,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'catatan' => $request->catatan,
            'status' => 'dipinjam',
            'dicatat_oleh' => auth()->id(),
        ]);

        $buku->decrement('jumlah_tersedia');

        return redirect()->route('staf.peminjaman-buku.index')->with('success', 'Peminjaman buku berhasil dicatat.');
    }

    public function kembalikan(PeminjamanBuku $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $status = now()->toDateString() > $peminjaman->tanggal_kembali_rencana ? 'terlambat' : 'dikembalikan';

        $peminjaman->update([
            'status' => $status,
            'tanggal_kembali_aktual' => now()->toDateString(),
        ]);

        $peminjaman->buku->increment('jumlah_tersedia');

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
