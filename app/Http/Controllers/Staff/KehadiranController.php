<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\PengaturanKehadiran;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::where('pengguna_id', auth()->id());

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('tanggal', 'desc')->paginate(20);
        $todayAttendance = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();
        $setting = PengaturanKehadiran::first();

        return view('staf.kehadiran.index', compact('attendances', 'todayAttendance', 'setting'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'foto'     => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat'   => 'nullable|string|max:500',
        ]);

        // Save base64 photo
        $photoPath = $this->saveBase64Image($request->foto, 'attendance-photos');

        $setting = PengaturanKehadiran::first();
        $now     = Carbon::now();
        $status  = 'hadir';

        if ($setting) {
            $clockInLimit = Carbon::parse($setting->jam_masuk)->addMinutes($setting->toleransi_terlambat_menit ?? 0);
            if ($now->format('H:i:s') > $clockInLimit->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        $existing = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();

        // Delete old photo if re-doing
        if ($existing && $existing->foto_masuk) {
            Storage::disk('public')->delete($existing->foto_masuk);
        }

        $attendance = Kehadiran::updateOrCreate(
            ['pengguna_id' => auth()->id(), 'tanggal' => today()],
            [
                'jam_masuk'     => $now->format('H:i:s'),
                'status'       => $status,
                'lat_masuk'  => $request->latitude,
                'lng_masuk' => $request->longitude,
                'foto_masuk'     => $photoPath,
                'alamat_masuk'   => $request->input('alamat'),
            ]
        );

        if ($status === 'terlambat') {
            Notifikasi::create([
                'pengguna_id' => auth()->id(),
                'judul'   => 'Anda Terlambat!',
                'pesan' => 'Anda tercatat terlambat pada ' . $now->format('d/m/Y H:i:s'),
                'jenis'    => 'kehadiran',
            ]);
        }

        $message = ($existing && $existing->jam_masuk)
            ? 'Absen masuk berhasil diperbarui pada ' . $now->format('H:i:s') . ($status === 'terlambat' ? ' (Terlambat)' : '')
            : 'Absen masuk berhasil pada ' . $now->format('H:i:s') . ($status === 'terlambat' ? '. Anda tercatat TERLAMBAT.' : '.');

        return redirect()->back()->with('success', $message);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'foto'     => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat'   => 'nullable|string|max:500',
        ]);

        $attendance = Kehadiran::where('pengguna_id', auth()->id())->whereDate('tanggal', today())->first();

        if (!$attendance || !$attendance->jam_masuk) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        // Delete old photo if re-doing
        if ($attendance->foto_pulang) {
            Storage::disk('public')->delete($attendance->foto_pulang);
        }

        $photoPath = $this->saveBase64Image($request->foto, 'attendance-photos');
        $now = Carbon::now();
        $isRedo = (bool) $attendance->jam_pulang;

        $attendance->update([
            'jam_pulang'     => $now->format('H:i:s'),
            'lat_pulang'  => $request->latitude,
            'lng_pulang' => $request->longitude,
            'foto_pulang'     => $photoPath,
            'alamat_pulang'   => $request->input('alamat'),
        ]);

        $msg = $isRedo
            ? 'Absen pulang berhasil diperbarui pada ' . $now->format('H:i:s')
            : 'Absen pulang berhasil pada ' . $now->format('H:i:s');

        return redirect()->back()->with('success', $msg);
    }

    public function show(Kehadiran $attendance)
    {
        if ($attendance->pengguna_id !== auth()->id()) {
            abort(403);
        }
        return view('staf.kehadiran.show', compact('attendance'));
    }

    /**
     * Update keterangan / status for a PAST day (date < today).
     * Clock times are locked — only status (izin/sakit/cuti/alpha) + note can be changed.
     */
    public function updateNote(Request $request, Kehadiran $attendance)
    {
        if ($attendance->pengguna_id !== auth()->id()) abort(403);

        // Only allow editing past days via this endpoint
        if ($attendance->tanggal->isToday()) {
            return redirect()->back()->with('error', 'Gunakan tombol Ubah Kehadiran untuk hari ini.');
        }

        $request->validate([
            'status' => 'required|in:hadir,terlambat,izin,sakit,alpha,cuti',
            'catatan'   => 'nullable|string|max:500',
        ]);

        $attendance->update([
            'status' => $request->status,
            'catatan'   => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Keterangan absensi berhasil diperbarui.');
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
