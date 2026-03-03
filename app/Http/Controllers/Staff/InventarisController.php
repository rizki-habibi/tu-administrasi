<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\DamageReport;
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
        return view('staff.inventaris.index', compact('items'));
    }

    public function show(Inventaris $inventaris)
    {
        $inventaris->load('damageReports');
        return view('staff.inventaris.show', compact('inventaris'));
    }

    public function reportDamage(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['inventaris_id', 'description']);
        $data['reported_by'] = auth()->id();
        $data['status'] = 'dilaporkan';

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('damage-reports', 'public');
        }

        DamageReport::create($data);
        return back()->with('success', 'Laporan kerusakan berhasil dikirim.');
    }
}
