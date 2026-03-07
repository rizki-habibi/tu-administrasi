<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Inventaris;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventarisController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = Inventaris::with('creator')->latest();

        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('kondisi')) $query->where('kondisi', $request->kondisi);
        if ($request->filled('lokasi')) $query->where('lokasi', $request->lokasi);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Inventaris::count(),
            'baik' => Inventaris::where('kondisi', 'baik')->count(),
            'rusak_ringan' => Inventaris::where('kondisi', 'rusak_ringan')->count(),
            'rusak_berat' => Inventaris::where('kondisi', 'rusak_berat')->count(),
        ];

        return view('admin.inventaris.index', compact('items', 'stats'));
    }

    public function create()
    {
        return view('admin.inventaris.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
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
        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function show(Inventaris $inventari)
    {
        $inventari->load(['creator', 'damageReports.reporter']);
        return view('admin.inventaris.show', compact('inventari'));
    }

    public function edit(Inventaris $inventari)
    {
        return view('admin.inventaris.edit', compact('inventari'));
    }

    public function update(Request $request, Inventaris $inventari)
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
            if ($inventari->foto) Storage::disk('public')->delete($inventari->foto);
            $data['foto'] = $request->file('foto')->store('inventaris', 'public');
        }

        $inventari->update($data);
        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(Inventaris $inventari)
    {
        if ($inventari->foto) Storage::disk('public')->delete($inventari->foto);
        $inventari->delete();
        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
    }

    public function export()
    {
        $rows = Inventaris::with('creator')->latest()->get()->map(function ($item, $i) {
            return [
                $i + 1,
                $item->kode_barang,
                $item->nama_barang,
                ucfirst(str_replace('_', ' ', $item->kategori)),
                $item->lokasi,
                $item->jumlah,
                ucfirst(str_replace('_', ' ', $item->kondisi)),
                $item->tanggal_perolehan ? \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y') : '-',
                $item->harga_perolehan ? number_format($item->harga_perolehan, 0, ',', '.') : '-',
                $item->sumber_dana ?? '-',
                $item->creator->nama ?? '-',
            ];
        });

        return $this->eksporCsv(
            'inventaris_' . now()->format('Ymd') . '.csv',
            ['No', 'Kode', 'Nama Barang', 'Kategori', 'Lokasi', 'Jumlah', 'Kondisi', 'Tgl Perolehan', 'Harga', 'Sumber Dana', 'Dicatat Oleh'],
            $rows
        );
    }
}
