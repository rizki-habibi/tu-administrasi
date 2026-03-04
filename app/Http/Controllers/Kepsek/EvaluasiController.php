<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\TeacherEvaluation;
use App\Models\StarAnalysis;
use App\Models\PhysicalEvidence;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function pkgIndex()
    {
        $evaluations = TeacherEvaluation::with('user')->latest()->paginate(20);
        return view('kepala-sekolah.evaluasi.pkg', compact('evaluations'));
    }

    public function starIndex()
    {
        $analyses = StarAnalysis::with('user')->latest()->paginate(20);
        return view('kepala-sekolah.evaluasi.star', compact('analyses'));
    }

    public function buktiFisikIndex()
    {
        $evidences = PhysicalEvidence::with('user')->latest()->paginate(20);
        return view('kepala-sekolah.evaluasi.bukti-fisik', compact('evidences'));
    }
}
