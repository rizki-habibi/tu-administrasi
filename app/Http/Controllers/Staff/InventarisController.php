<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;

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
}
