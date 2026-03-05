<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Kehadiran;
use App\Models\Dokumen;
use App\Models\Laporan;
use App\Models\Surat;
use App\Models\Acara;
use App\Models\PengajuanIzin;

class EksporController extends Controller
{
    public function index()
    {
        $stats = [
            'staff' => Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->count(),
            'kehadiran' => Kehadiran::whereMonth('tanggal', now()->month)->count(),
            'dokumen' => Dokumen::count(),
            'laporan' => Laporan::count(),
            'surat' => Surat::count(),
            'agenda' => Acara::count(),
            'izin' => PengajuanIzin::count(),
        ];

        return view('admin.ekspor.index', compact('stats'));
    }
}
