<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index()
    {
        $todayAttendances = Kehadiran::with('user')->whereDate('tanggal', today())->get();
        $allStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->orderBy('nama')->get();
        return view('kepala-sekolah.kehadiran.index', compact('todayAttendances', 'allStaff'));
    }

    public function report(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Kehadiran::with('user')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'desc')
            ->paginate(30);

        $staffs = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->orderBy('nama')->get();

        return view('kepala-sekolah.kehadiran.rekap', compact('attendances', 'staffs', 'month', 'year'));
    }

    public function show(Kehadiran $attendance)
    {
        $attendance->load('user');
        return view('kepala-sekolah.kehadiran.show', compact('attendance'));
    }
}
