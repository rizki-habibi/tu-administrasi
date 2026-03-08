<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\CatatanKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = CatatanKeuangan::where('dibuat_oleh', auth()->id());

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('search')) {
            $query->where('uraian', 'like', "%{$request->search}%");
        }

        $catatan = $query->latest('tanggal')->paginate(20)->withQueryString();

        $totalPemasukan = CatatanKeuangan::where('dibuat_oleh', auth()->id())->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = CatatanKeuangan::where('dibuat_oleh', auth()->id())->where('jenis', 'pengeluaran')->sum('jumlah');

        return view('staf.keuangan.index', compact('catatan', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function create()
    {
        return view('staf.keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:100',
            'uraian' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->only(['jenis', 'kategori', 'uraian', 'jumlah', 'tanggal']);
        $data['kode_transaksi'] = CatatanKeuangan::generateKode($request->jenis);
        $data['dibuat_oleh'] = auth()->id();
        $data['status'] = 'draft';

        if ($request->hasFile('bukti')) {
            $data['bukti_path'] = $request->file('bukti')->store('keuangan-bukti', 'public');
        }

        CatatanKeuangan::create($data);

        return redirect()->route('staf.keuangan.index')->with('success', 'Catatan keuangan berhasil ditambahkan.');
    }

    public function show(CatatanKeuangan $catatan)
    {
        abort_if($catatan->dibuat_oleh !== auth()->id(), 403);
        return view('staf.keuangan.show', compact('catatan'));
    }

    public function edit(CatatanKeuangan $catatan)
    {
        abort_if($catatan->dibuat_oleh !== auth()->id(), 403);
        return view('staf.keuangan.edit', compact('catatan'));
    }

    public function update(Request $request, CatatanKeuangan $catatan)
    {
        abort_if($catatan->dibuat_oleh !== auth()->id(), 403);

        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:100',
            'uraian' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->only(['jenis', 'kategori', 'uraian', 'jumlah', 'tanggal']);

        if ($request->hasFile('bukti')) {
            if ($catatan->bukti_path) {
                Storage::disk('public')->delete($catatan->bukti_path);
            }
            $data['bukti_path'] = $request->file('bukti')->store('keuangan-bukti', 'public');
        }

        $catatan->update($data);

        return redirect()->route('staf.keuangan.index')->with('success', 'Catatan keuangan berhasil diperbarui.');
    }

    public function destroy(CatatanKeuangan $catatan)
    {
        abort_if($catatan->dibuat_oleh !== auth()->id(), 403);

        if ($catatan->bukti_path) {
            Storage::disk('public')->delete($catatan->bukti_path);
        }

        $catatan->delete();
        return redirect()->route('staf.keuangan.index')->with('success', 'Catatan keuangan berhasil dihapus.');
    }

    public function export()
    {
        $rows = CatatanKeuangan::where('dibuat_oleh', auth()->id())->latest('tanggal')->get()->map(function ($r, $i) {
            return [
                $i + 1,
                $r->kode_transaksi,
                ucfirst($r->jenis),
                $r->kategori,
                $r->uraian,
                number_format($r->jumlah, 0, ',', '.'),
                $r->tanggal?->format('d/m/Y'),
                ucfirst($r->status),
            ];
        });

        return $this->eksporCsv(
            'catatan_keuangan_' . now()->format('Ymd') . '.csv',
            ['No', 'Kode', 'Jenis', 'Kategori', 'Uraian', 'Jumlah', 'Tanggal', 'Status'],
            $rows
        );
    }
}
