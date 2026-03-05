<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\CatatanKeuangan;
use App\Models\Anggaran;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $records = CatatanKeuangan::with('user')->latest()->paginate(20);
        $budgets = Budget::latest()->take(5)->get();

        $totalPemasukan = CatatanKeuangan::where('jenis', 'pemasukan')->whereYear('tanggal', now()->year)->sum('jumlah');
        $totalPengeluaran = CatatanKeuangan::where('jenis', 'pengeluaran')->whereYear('tanggal', now()->year)->sum('jumlah');

        return view('kepala-sekolah.keuangan.index', compact('records', 'budgets', 'totalPemasukan', 'totalPengeluaran'));
    }
}
