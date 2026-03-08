<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanFasilitas;
use Illuminate\Http\Request;

class PeminjamanFasilitasController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanFasilitas::with(['peminjam', 'approver'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_fasilitas', 'like', "%{$request->search}%")
                  ->orWhere('peminjam_nama', 'like', "%{$request->search}%");
            });
        }

        $peminjaman = $query->paginate(20)->withQueryString();

        $menunggu = PeminjamanFasilitas::where('status', 'pending')->count();
        $hariIni = PeminjamanFasilitas::where('tanggal', now()->toDateString())
            ->where('status', 'disetujui')->count();

        return view('staf.inventaris.peminjaman-index', compact('peminjaman', 'menunggu', 'hariIni'));
    }

    public function create()
    {
        return view('staf.inventaris.peminjaman-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'jenis' => 'required|in:' . implode(',', array_keys(PeminjamanFasilitas::JENIS)),
            'peminjam_nama' => 'required|string|max:255',
            'keperluan' => 'required|string|max:500',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'penanggung_jawab' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        // Check for conflicts
        $konflik = PeminjamanFasilitas::overlapping(
            $request->nama_fasilitas,
            $request->tanggal,
            $request->jam_mulai,
            $request->jam_selesai
        )->exists();

        if ($konflik) {
            return back()->with('error', 'Fasilitas sudah dipesan pada waktu tersebut. Silakan pilih waktu lain.')->withInput();
        }

        PeminjamanFasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'jenis' => $request->jenis,
            'peminjam_id' => auth()->id(),
            'peminjam_nama' => $request->peminjam_nama,
            'keperluan' => $request->keperluan,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'penanggung_jawab' => $request->penanggung_jawab,
            'catatan' => $request->catatan,
            'status' => 'pending',
        ]);

        return redirect()->route('staf.peminjaman.index')->with('success', 'Pengajuan peminjaman fasilitas berhasil dikirim.');
    }

    public function show(PeminjamanFasilitas $peminjaman)
    {
        $peminjaman->load(['peminjam', 'approver']);
        return view('staf.inventaris.peminjaman-show', compact('peminjaman'));
    }

    public function cekKetersediaan(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $jadwal = PeminjamanFasilitas::where('nama_fasilitas', $request->nama_fasilitas)
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['pending', 'disetujui'])
            ->orderBy('jam_mulai')
            ->get(['jam_mulai', 'jam_selesai', 'peminjam_nama', 'keperluan', 'status']);

        return response()->json(['jadwal' => $jadwal]);
    }

    public function setujui(PeminjamanFasilitas $peminjaman)
    {
        abort_if($peminjaman->status !== 'pending', 403);

        $peminjaman->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('success', 'Peminjaman fasilitas disetujui.');
    }

    public function tolak(Request $request, PeminjamanFasilitas $peminjaman)
    {
        abort_if($peminjaman->status !== 'pending', 403);

        $request->validate(['alasan_tolak' => 'required|string|max:500']);

        $peminjaman->update([
            'status' => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('success', 'Peminjaman fasilitas ditolak.');
    }

    public function selesai(PeminjamanFasilitas $peminjaman)
    {
        abort_if($peminjaman->status !== 'disetujui', 403);

        $peminjaman->update(['status' => 'selesai']);

        return back()->with('success', 'Peminjaman fasilitas ditandai selesai.');
    }
}
