<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\EvaluasiGuru;
use App\Models\AnalisisStar;
use App\Models\BuktiFisik;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function pkgIndex()
    {
        $evaluations = EvaluasiGuru::with('user')->latest()->paginate(20);
        return view('kepala-sekolah.evaluasi.pkg', compact('evaluations'));
    }

    public function starIndex()
    {
        $analyses = AnalisisStar::with('user')->latest()->paginate(20);
        return view('kepala-sekolah.evaluasi.star', compact('analyses'));
    }

    public function buktiFisikIndex()
    {
        $evidences = BuktiFisik::with('user')->latest()->paginate(20);
        return view('kepala-sekolah.evaluasi.bukti-fisik', compact('evidences'));
    }
}
