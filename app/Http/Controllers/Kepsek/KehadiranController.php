<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Kehadiran;
use App\Models\Pengguna;
use App\Models\PengaturanKehadiran;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    use EksporImporTrait;

    public function index()
    {
        $todayAttendances = Kehadiran::with('user')->whereDate('tanggal', today())->get();
        $allStaff = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->orderBy('nama')->get();
        $todayAttendance = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();
        $setting = PengaturanKehadiran::first();

        return view('kepala-sekolah.kehadiran.index', compact('todayAttendances', 'allStaff', 'todayAttendance', 'setting'));
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

    public function export()
    {
        $rows = Kehadiran::with('user')->whereMonth('tanggal', now()->month)
            ->orderBy('tanggal', 'desc')->get()->map(function ($a, $i) {
                return [
                    $i + 1,
                    $a->user->nama ?? '-',
                    $a->tanggal?->format('d/m/Y'),
                    $a->jam_masuk ?? '-',
                    $a->jam_pulang ?? '-',
                    ucfirst($a->status),
                ];
            });

        return $this->eksporCsv(
            'kehadiran_semua_' . now()->format('Ymd') . '.csv',
            ['No', 'Nama', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status'],
            $rows
        );
    }
}
