<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\AttendanceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        } else {
            $query->whereDate('tanggal', today());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pengguna_id')) {
            $query->where('pengguna_id', $request->pengguna_id);
        }

        $attendances = $query->latest()->paginate(20);
        $staffs = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->get();
        $setting = AttendanceSetting::first();

        return view('admin.kehadiran.index', compact('attendances', 'staffs', 'setting'));
    }

    public function report(Request $request)
    {
        $startDate = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('tanggal_selesai', now()->format('Y-m-d'));

        $staffs = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->get();

        $attendanceData = [];
        foreach ($staffs as $staff) {
            $attendances = Attendance::where('pengguna_id', $staff->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $attendanceData[] = [
                'staff' => $staff,
                'hadir' => $attendances->where('status', 'hadir')->count(),
                'terlambat' => $attendances->where('status', 'terlambat')->count(),
                'izin' => $attendances->where('status', 'izin')->count(),
                'sakit' => $attendances->where('status', 'sakit')->count(),
                'alpha' => $attendances->where('status', 'alpha')->count(),
                'total' => $attendances->count(),
            ];
        }

        return view('admin.kehadiran.rekap', compact('attendanceData', 'startDate', 'endDate'));
    }

    public function settings()
    {
        $setting = AttendanceSetting::firstOrCreate([], [
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'toleransi_terlambat_menit' => 15,
            'jarak_maksimal_meter' => 200,
        ]);

        return view('admin.kehadiran.pengaturan', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_terlambat_menit' => 'required|integer|min:0',
            'lat_kantor' => 'nullable|numeric',
            'lng_kantor' => 'nullable|numeric',
            'jarak_maksimal_meter' => 'required|integer|min:0',
        ]);

        $setting = AttendanceSetting::first();
        $setting->update($request->all());

        return redirect()->back()->with('success', 'Pengaturan absensi berhasil diperbarui.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user');
        return view('admin.kehadiran.show', compact('attendance'));
    }

    public function export(Request $request)
    {
        $query = Attendance::with('user');
        if ($request->filled('tanggal_mulai')) $query->where('tanggal', '>=', $request->tanggal_mulai);
        if ($request->filled('tanggal_selesai')) $query->where('tanggal', '<=', $request->tanggal_selesai);
        if ($request->filled('status')) $query->where('status', $request->status);
        $attendances = $query->latest('tanggal')->get();
        $format = $request->get('format', 'csv');

        if ($format === 'csv' || $format === 'excel') {
            $filename = 'kehadiran_' . now()->format('Ymd_His') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
            $callback = function() use ($attendances) {
                $f = fopen('php://output', 'w');
                fputcsv($f, ['No', 'Nama', 'Tanggal', 'Masuk', 'Pulang', 'Status', 'Keterangan']);
                foreach ($attendances as $i => $a) {
                    fputcsv($f, [$i+1, $a->user->nama ?? '-', $a->tanggal->format('d/m/Y'), $a->jam_masuk ?? '-', $a->jam_pulang ?? '-', ucfirst($a->status), $a->catatan ?? '-']);
                }
                fclose($f);
            };
            return response()->stream($callback, 200, $headers);
        }
        return view('admin.kehadiran.cetak', compact('attendances'));
    }
}
