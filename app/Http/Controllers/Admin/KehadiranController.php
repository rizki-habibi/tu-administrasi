<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Pengguna;
use App\Models\PengaturanKehadiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with('user');

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
        $staffs = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();
        $setting = PengaturanKehadiran::first();
        $todayAttendance = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();

        return view('admin.kehadiran.index', compact('attendances', 'staffs', 'setting', 'todayAttendance'));
    }

    public function report(Request $request)
    {
        $startDate = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('tanggal_selesai', now()->format('Y-m-d'));

        $staffs = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();

        $attendanceData = [];
        foreach ($staffs as $staff) {
            $attendances = Kehadiran::where('pengguna_id', $staff->id)
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
        $setting = PengaturanKehadiran::firstOrCreate([], [
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

        $setting = PengaturanKehadiran::first();
        $setting->update($request->all());

        return redirect()->back()->with('success', 'Pengaturan absensi berhasil diperbarui.');
    }

    public function show(Kehadiran $attendance)
    {
        $attendance->load('user');
        return view('admin.kehadiran.show', compact('attendance'));
    }

    public function export(Request $request)
    {
        $query = Kehadiran::with('user');
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

    public function clockIn(Request $request)
    {
        $request->validate([
            'foto' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat' => 'nullable|string|max:500',
        ]);

        $photoPath = $this->saveBase64Image($request->foto, 'attendance-photos');
        $setting = PengaturanKehadiran::first();
        $now = Carbon::now();
        $status = 'hadir';

        if ($setting) {
            $clockInLimit = Carbon::parse($setting->jam_masuk)->addMinutes($setting->toleransi_terlambat_menit ?? 0);
            if ($now->format('H:i:s') > $clockInLimit->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        $existing = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();
        if ($existing && $existing->foto_masuk) {
            Storage::disk('public')->delete($existing->foto_masuk);
        }

        Kehadiran::updateOrCreate(
            ['pengguna_id' => auth()->id(), 'tanggal' => today()],
            [
                'jam_masuk' => $now->format('H:i:s'),
                'status' => $status,
                'lat_masuk' => $request->latitude,
                'lng_masuk' => $request->longitude,
                'foto_masuk' => $photoPath,
                'alamat_masuk' => $request->input('alamat'),
            ]
        );

        $message = 'Absen masuk berhasil pada ' . $now->format('H:i:s') . ($status === 'terlambat' ? ' (Terlambat)' : '');
        return redirect()->back()->with('success', $message);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'foto' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat' => 'nullable|string|max:500',
        ]);

        $attendance = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();
        if (!$attendance || !$attendance->jam_masuk) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        if ($attendance->foto_pulang) {
            Storage::disk('public')->delete($attendance->foto_pulang);
        }

        $photoPath = $this->saveBase64Image($request->foto, 'attendance-photos');
        $now = Carbon::now();

        $attendance->update([
            'jam_pulang' => $now->format('H:i:s'),
            'lat_pulang' => $request->latitude,
            'lng_pulang' => $request->longitude,
            'foto_pulang' => $photoPath,
            'alamat_pulang' => $request->input('alamat'),
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil pada ' . $now->format('H:i:s'));
    }

    private function saveBase64Image($base64, $folder)
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $folder . '/' . uniqid() . '_' . time() . '.png';
        Storage::disk('public')->put($imageName, base64_decode($image));
        return $imageName;
    }
}
