<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceRecord;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = FinanceRecord::with('creator')->latest();

        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) $query->where('uraian', 'like', '%' . $request->search . '%');

        $transactions = $query->paginate(20)->withQueryString();

        $totalPemasukan = FinanceRecord::where('jenis', 'pemasukan')->where('status', 'approved')->sum('jumlah');
        $totalPengeluaran = FinanceRecord::where('jenis', 'pengeluaran')->where('status', 'approved')->sum('jumlah');
        $pendingCount = FinanceRecord::where('status', 'draft')->count();

        $budgets = Budget::latest()->take(5)->get();

        return view('admin.keuangan.index', compact('transactions', 'totalPemasukan', 'totalPengeluaran', 'pendingCount', 'budgets'));
    }

    public function create()
    {
        $budgets = Budget::where('status', 'active')->get();
        return view('admin.keuangan.create', compact('budgets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|in:bos,apbd,spp,operasional,gaji,pengadaan,lainnya',
            'uraian' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ]);

        $data = $request->except('bukti');
        $data['kode_transaksi'] = FinanceRecord::generateKode($request->jenis);
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $data['bukti_path'] = $file->store('keuangan', 'public');
            $data['bukti_nama'] = $file->getClientOriginalName();
        }

        FinanceRecord::create($data);
        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi keuangan berhasil dicatat.');
    }

    public function show(FinanceRecord $keuangan)
    {
        $keuangan->load('creator', 'verifier');
        return view('admin.keuangan.show', compact('keuangan'));
    }

    public function verify(FinanceRecord $keuangan)
    {
        $keuangan->update([
            'status' => 'approved',
            'diverifikasi_oleh' => auth()->id(),
        ]);
        return redirect()->back()->with('success', 'Transaksi berhasil diverifikasi.');
    }

    public function destroy(FinanceRecord $keuangan)
    {
        if ($keuangan->bukti_path) Storage::disk('public')->delete($keuangan->bukti_path);
        $keuangan->delete();
        return redirect()->route('admin.keuangan.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Budget / RKAS
    public function budgetIndex()
    {
        $budgets = Budget::with('creator')->latest()->paginate(15);
        return view('admin.keuangan.anggaran', compact('budgets'));
    }

    public function budgetStore(Request $request)
    {
        $request->validate([
            'nama_anggaran' => 'required|string|max:255',
            'tahun_anggaran' => 'required|string',
            'sumber_dana' => 'required|string',
            'total_anggaran' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Budget::create(array_merge($request->all(), ['dibuat_oleh' => auth()->id()]));
        return redirect()->route('admin.keuangan.anggaran')->with('success', 'Anggaran berhasil ditambahkan.');
    }
}
