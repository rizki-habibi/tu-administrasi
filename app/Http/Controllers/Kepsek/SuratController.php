<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('user');
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        $surats = $query->latest()->paginate(20);
        return view('kepala-sekolah.surat.index', compact('surats'));
    }

    public function show(Surat $surat)
    {
        $surat->load('user');
        return view('kepala-sekolah.surat.show', compact('surat'));
    }
}
