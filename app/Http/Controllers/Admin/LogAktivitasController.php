<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pengguna;
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
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('modul', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('pengguna', fn($p) => $p->where('nama', 'like', "%{$search}%"));
            });
        }

        $logs = $query->paginate(25);

        $modules = LogAktivitas::distinct('modul')->pluck('modul');
        $actions = LogAktivitas::distinct('aksi')->pluck('aksi');
        $users = Pengguna::orderBy('nama')->pluck('nama', 'id');

        // Deteksi anomali: hapus massal, login gagal, dll
        $anomali = [];
        $deleteCount = LogAktivitas::where('aksi', 'delete')->whereDate('created_at', today())->count();
        if ($deleteCount > 10) {
            $anomali[] = ['level' => 'danger', 'pesan' => "Terdeteksi {$deleteCount} aksi penghapusan hari ini — periksa apakah data hilang!"];
        }
        $loginGagal = LogAktivitas::where('aksi', 'login_gagal')->whereDate('created_at', today())->count();
        if ($loginGagal > 5) {
            $anomali[] = ['level' => 'warning', 'pesan' => "Terdeteksi {$loginGagal} percobaan login gagal hari ini."];
        }

        return view('admin.log-aktivitas.index', compact('logs', 'modules', 'actions', 'users', 'anomali'));
    }
}
