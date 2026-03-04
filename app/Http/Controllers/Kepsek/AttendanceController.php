<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $todayAttendances = Attendance::with('user')->whereDate('tanggal', today())->get();
        $allStaff = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->orderBy('nama')->get();
        return view('kepala-sekolah.kehadiran.index', compact('todayAttendances', 'allStaff'));
    }

    public function report(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Attendance::with('user')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'desc')
            ->paginate(30);

        $staffs = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->orderBy('nama')->get();

        return view('kepala-sekolah.kehadiran.rekap', compact('attendances', 'staffs', 'month', 'year'));
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user');
        return view('kepala-sekolah.kehadiran.show', compact('attendance'));
    }
}
