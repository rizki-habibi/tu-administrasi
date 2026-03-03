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
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->latest()->paginate(20);
        $staffs = User::where('role', 'staff')->where('is_active', true)->get();
        $setting = AttendanceSetting::first();

        return view('admin.attendance.index', compact('attendances', 'staffs', 'setting'));
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $staffs = User::where('role', 'staff')->where('is_active', true)->get();

        $attendanceData = [];
        foreach ($staffs as $staff) {
            $attendances = Attendance::where('user_id', $staff->id)
                ->whereBetween('date', [$startDate, $endDate])
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

        return view('admin.attendance.report', compact('attendanceData', 'startDate', 'endDate'));
    }

    public function settings()
    {
        $setting = AttendanceSetting::firstOrCreate([], [
            'clock_in_time' => '08:00:00',
            'clock_out_time' => '16:00:00',
            'late_tolerance_minutes' => 15,
            'max_distance_meters' => 200,
        ]);

        return view('admin.attendance.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'clock_in_time' => 'required',
            'clock_out_time' => 'required',
            'late_tolerance_minutes' => 'required|integer|min:0',
            'office_latitude' => 'nullable|numeric',
            'office_longitude' => 'nullable|numeric',
            'max_distance_meters' => 'required|integer|min:0',
        ]);

        $setting = AttendanceSetting::first();
        $setting->update($request->all());

        return redirect()->back()->with('success', 'Pengaturan absensi berhasil diperbarui.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user');
        return view('admin.attendance.show', compact('attendance'));
    }

    public function export(Request $request)
    {
        $query = Attendance::with('user');
        if ($request->filled('start_date')) $query->where('date', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->where('date', '<=', $request->end_date);
        if ($request->filled('status')) $query->where('status', $request->status);
        $attendances = $query->latest('date')->get();
        $format = $request->get('format', 'csv');

        if ($format === 'csv' || $format === 'excel') {
            $filename = 'kehadiran_' . now()->format('Ymd_His') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
            $callback = function() use ($attendances) {
                $f = fopen('php://output', 'w');
                fputcsv($f, ['No', 'Nama', 'Tanggal', 'Masuk', 'Pulang', 'Status', 'Keterangan']);
                foreach ($attendances as $i => $a) {
                    fputcsv($f, [$i+1, $a->user->name ?? '-', $a->date->format('d/m/Y'), $a->clock_in ?? '-', $a->clock_out ?? '-', ucfirst($a->status), $a->note ?? '-']);
                }
                fclose($f);
            };
            return response()->stream($callback, 200, $headers);
        }
        return view('admin.attendance.print', compact('attendances'));
    }
}
