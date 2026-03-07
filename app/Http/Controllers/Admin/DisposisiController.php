<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisposisiSurat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\Surat;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $query = DisposisiSurat::with(['surat', 'dariPengguna', 'kepadaPengguna'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $disposisi = $query->paginate(15);
        $stats = [
            'total' => DisposisiSurat::count(),
            'belum_dibaca' => DisposisiSurat::where('status', 'belum_dibaca')->count(),
            'diproses' => DisposisiSurat::where('status', 'diproses')->count(),
            'selesai' => DisposisiSurat::where('status', 'selesai')->count(),
        ];

        return view('admin.disposisi.index', compact('disposisi', 'stats'));
    }

    public function create()
    {
        $suratList = Surat::where('jenis', 'masuk')->latest()->get();
        $staffList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.disposisi.create', compact('suratList', 'staffList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_id' => 'required|exists:surat,id',
            'kepada_pengguna_id' => 'required|exists:pengguna,id',
            'instruksi' => 'required|string|max:2000',
            'prioritas' => 'required|in:rendah,sedang,tinggi,urgent',
            'tenggat' => 'nullable|date|after_or_equal:today',
        ]);

        $validated['dari_pengguna_id'] = auth()->id();
        $disposisi = DisposisiSurat::create($validated);

        // Kirim notifikasi ke penerima
        Notifikasi::create([
            'pengguna_id' => $validated['kepada_pengguna_id'],
            'judul' => 'Disposisi Surat Baru',
            'pesan' => 'Anda menerima disposisi surat dengan prioritas ' . $validated['prioritas'],
            'jenis' => 'sistem',
            'tautan' => '#',
        ]);

        LogAktivitas::catat('create', 'disposisi', 'Membuat disposisi surat ke ' . $disposisi->kepadaPengguna->nama, $disposisi);

        return redirect()->route('admin.disposisi.index')
            ->with('success', 'Disposisi berhasil dibuat.');
    }

    public function show(DisposisiSurat $disposisi)
    {
        $disposisi->load(['surat', 'dariPengguna', 'kepadaPengguna']);
        return view('admin.disposisi.show', compact('disposisi'));
    }

    public function destroy(DisposisiSurat $disposisi)
    {
        LogAktivitas::catat('delete', 'disposisi', 'Menghapus disposisi surat #' . $disposisi->id, $disposisi);
        $disposisi->delete();

        return redirect()->route('admin.disposisi.index')
            ->with('success', 'Disposisi berhasil dihapus.');
    }
}
