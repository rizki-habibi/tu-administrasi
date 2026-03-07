<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventaris::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%");
            });
        }
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->lokasi) {
            $query->where('lokasi', $request->lokasi);
        }

        $items = $query->latest()->paginate(20);
        return view('staf.inventaris.index', compact('items'));
    }

    public function show(Inventaris $inventaris)
    {
        $inventaris->load('damageReports');
        return view('staf.inventaris.show', compact('inventaris'));
    }

    public function create()
    {
        return view('staf.inventaris.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:mebeulair,elektronik,buku,alat_lab,olahraga,lainnya',
            'lokasi' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'tanggal_perolehan' => 'nullable|date',
            'sumber_dana' => 'nullable|string',
            'harga_perolehan' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');
        $data['kode_barang'] = Inventaris::generateKode($request->kategori);
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('inventaris', 'public');
        }

        Inventaris::create($data);
        return redirect()->route('staf.inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function edit(Inventaris $inventaris)
    {
        return view('staf.inventaris.edit', compact('inventaris'));
    }

    public function update(Request $request, Inventaris $inventaris)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required',
            'lokasi' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($inventaris->foto) Storage::disk('public')->delete($inventaris->foto);
            $data['foto'] = $request->file('foto')->store('inventaris', 'public');
        }

        $inventaris->update($data);
        return redirect()->route('staf.inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(Inventaris $inventaris)
    {
        if ($inventaris->foto) Storage::disk('public')->delete($inventaris->foto);
        $inventaris->delete();
        return redirect()->route('staf.inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
    }

    public function reportDamage(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|max:5120',
        ]);

        $data = [
            'inventaris_id' => $request->inventaris_id,
            'deskripsi_kerusakan' => $request->deskripsi,
            'tingkat_kerusakan' => $request->tingkat_kerusakan ?? 'ringan',
            'tanggal_laporan' => now()->toDateString(),
            'dilaporkan_oleh' => auth()->id(),
            'status' => 'dilaporkan',
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('damage-reports', 'public');
        }

        LaporanKerusakan::create($data);
        return back()->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    public function kerusakanIndex(Request $request)
    {
        $query = LaporanKerusakan::with(['inventaris', 'reporter'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('inventaris', fn($q) => $q->where('nama_barang', 'like', "%{$request->search}%"));
        }

        $reports = $query->paginate(20)->withQueryString();
        return view('staf.inventaris.kerusakan-index', compact('reports'));
    }

    public function kerusakanCreate()
    {
        $inventaris = Inventaris::orderBy('nama_barang')->get();
        return view('staf.inventaris.kerusakan-create', compact('inventaris'));
    }

    public function kerusakanStore(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'deskripsi_kerusakan' => 'required|string',
            'tingkat_kerusakan' => 'required|in:ringan,sedang,berat',
            'foto' => 'nullable|image|max:5120',
        ]);

        $data = [
            'inventaris_id' => $request->inventaris_id,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'tanggal_laporan' => now()->toDateString(),
            'dilaporkan_oleh' => auth()->id(),
            'status' => 'dilaporkan',
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('damage-reports', 'public');
        }

        LaporanKerusakan::create($data);
        return redirect()->route('staf.kerusakan.index')->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    public function kerusakanShow(LaporanKerusakan $kerusakan)
    {
        $kerusakan->load(['inventaris', 'reporter']);
        return view('staf.inventaris.kerusakan-show', compact('kerusakan'));
    }

    public function kerusakanUpdateStatus(Request $request, LaporanKerusakan $kerusakan)
    {
        $request->validate([
            'status' => 'required|in:dilaporkan,diproses,selesai',
            'tindakan' => 'nullable|string',
        ]);

        $kerusakan->update($request->only(['status', 'tindakan']));
        return back()->with('success', 'Status laporan kerusakan berhasil diperbarui.');
    }
}
