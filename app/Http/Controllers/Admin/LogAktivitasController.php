<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('pengguna')->latest();

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }
        if ($request->filled('pengguna_id')) {
            $query->where('pengguna_id', $request->pengguna_id);
        }

        $logs = $query->paginate(25);

        $modules = LogAktivitas::distinct('modul')->pluck('modul');
        $actions = LogAktivitas::distinct('aksi')->pluck('aksi');

        return view('admin.log-aktivitas.index', compact('logs', 'modules', 'actions'));
    }
}
