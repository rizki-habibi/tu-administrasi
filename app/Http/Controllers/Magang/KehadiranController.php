<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Kehadiran;
use App\Models\PengaturanKehadiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = Kehadiran::where('pengguna_id', auth()->id());

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('tanggal', 'desc')->paginate(20);
        $todayAttendance = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();
        $setting = PengaturanKehadiran::first();

        return view('magang.kehadiran.index', compact('attendances', 'todayAttendance', 'setting'));
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

        return back()->with('success', 'Berhasil absen masuk.');
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
            return back()->with('error', 'Anda belum absen masuk hari ini.');
        }

        $photoPath = $this->saveBase64Image($request->foto, 'attendance-photos');

        if ($attendance->foto_pulang) {
            Storage::disk('public')->delete($attendance->foto_pulang);
        }

        $attendance->update([
            'jam_pulang' => Carbon::now()->format('H:i:s'),
            'lat_pulang' => $request->latitude,
            'lng_pulang' => $request->longitude,
            'foto_pulang' => $photoPath,
            'alamat_pulang' => $request->input('alamat'),
        ]);

        return back()->with('success', 'Berhasil absen pulang.');
    }

    public function show(Kehadiran $kehadiran)
    {
        abort_if($kehadiran->pengguna_id !== auth()->id(), 403);
        return view('magang.kehadiran.show', compact('kehadiran'));
    }

    private function saveBase64Image(string $base64, string $folder): string
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $image = base64_decode($image);
        $filename = $folder . '/' . auth()->id() . '_' . now()->format('Ymd_His') . '.jpg';
        Storage::disk('public')->put($filename, $image);
        return $filename;
    }

    public function export()
    {
        $rows = Kehadiran::where('pengguna_id', auth()->id())
            ->orderBy('tanggal', 'desc')->get()->map(function ($a, $i) {
                return [
                    $i + 1,
                    $a->tanggal?->format('d/m/Y'),
                    $a->jam_masuk ?? '-',
                    $a->jam_pulang ?? '-',
                    ucfirst($a->status),
                ];
            });

        return $this->eksporCsv(
            'kehadiran_magang_' . now()->format('Ymd') . '.csv',
            ['No', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status'],
            $rows
        );
    }
}
