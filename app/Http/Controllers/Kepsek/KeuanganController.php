<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\FinanceRecord;
use App\Models\Budget;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $records = FinanceRecord::with('user')->latest()->paginate(20);
        $budgets = Budget::latest()->take(5)->get();

        $totalPemasukan = FinanceRecord::where('jenis', 'pemasukan')->whereYear('tanggal', now()->year)->sum('jumlah');
        $totalPengeluaran = FinanceRecord::where('jenis', 'pengeluaran')->whereYear('tanggal', now()->year)->sum('jumlah');

        return view('kepala-sekolah.keuangan.index', compact('records', 'budgets', 'totalPemasukan', 'totalPengeluaran'));
    }
}
