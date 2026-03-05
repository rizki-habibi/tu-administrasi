<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DataSiswa;
use Illuminate\Http\Request;

class KesiswaanController extends Controller
{
    public function index(Request $request)
    {
        $query = DataSiswa::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nis', 'like', "%{$request->search}%")
                  ->orWhere('nisn', 'like', "%{$request->search}%");
            });
        }
        if ($request->kelas) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('kelas')->orderBy('nama')->paginate(20);
        $kelasList = DataSiswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('staf.kesiswaan.index', compact('students', 'kelasList'));
    }

    public function show(DataSiswa $kesiswaan)
    {
        $kesiswaan->load(['achievements', 'violations']);
        return view('staf.kesiswaan.show', compact('kesiswaan'));
    }
}
