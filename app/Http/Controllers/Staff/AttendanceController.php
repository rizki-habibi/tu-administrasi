<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::where('user_id', auth()->id());

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);
        $todayAttendance = Attendance::where('user_id', auth()->id())->whereDate('date', today())->first();
        $setting = AttendanceSetting::first();

        return view('staff.attendance.index', compact('attendances', 'todayAttendance', 'setting'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'photo'     => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Save base64 photo
        $photoPath = $this->saveBase64Image($request->photo, 'attendance-photos');

        $setting = AttendanceSetting::first();
        $now     = Carbon::now();
        $status  = 'hadir';

        if ($setting) {
            $clockInLimit = Carbon::parse($setting->clock_in_time)->addMinutes($setting->late_tolerance_minutes ?? 0);
            if ($now->format('H:i:s') > $clockInLimit->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        $existing = Attendance::where('user_id', auth()->id())->whereDate('date', today())->first();

        // Delete old photo if re-doing
        if ($existing && $existing->photo_in) {
            Storage::disk('public')->delete($existing->photo_in);
        }

        $attendance = Attendance::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => today()],
            [
                'clock_in'     => $now->format('H:i:s'),
                'status'       => $status,
                'latitude_in'  => $request->latitude,
                'longitude_in' => $request->longitude,
                'photo_in'     => $photoPath,
            ]
        );

        if ($status === 'terlambat') {
            Notification::create([
                'user_id' => auth()->id(),
                'title'   => 'Anda Terlambat!',
                'message' => 'Anda tercatat terlambat pada ' . $now->format('d/m/Y H:i:s'),
                'type'    => 'kehadiran',
            ]);
        }

        $message = ($existing && $existing->clock_in)
            ? 'Absen masuk berhasil diperbarui pada ' . $now->format('H:i:s') . ($status === 'terlambat' ? ' (Terlambat)' : '')
            : 'Absen masuk berhasil pada ' . $now->format('H:i:s') . ($status === 'terlambat' ? '. Anda tercatat TERLAMBAT.' : '.');

        return redirect()->back()->with('success', $message);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'photo'     => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $attendance = Attendance::where('user_id', auth()->id())->whereDate('date', today())->first();

        if (!$attendance || !$attendance->clock_in) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        // Delete old photo if re-doing
        if ($attendance->photo_out) {
            Storage::disk('public')->delete($attendance->photo_out);
        }

        $photoPath = $this->saveBase64Image($request->photo, 'attendance-photos');
        $now = Carbon::now();
        $isRedo = (bool) $attendance->clock_out;

        $attendance->update([
            'clock_out'     => $now->format('H:i:s'),
            'latitude_out'  => $request->latitude,
            'longitude_out' => $request->longitude,
            'photo_out'     => $photoPath,
        ]);

        $msg = $isRedo
            ? 'Absen pulang berhasil diperbarui pada ' . $now->format('H:i:s')
            : 'Absen pulang berhasil pada ' . $now->format('H:i:s');

        return redirect()->back()->with('success', $msg);
    }

    public function show(Attendance $attendance)
    {
        if ($attendance->user_id !== auth()->id()) {
            abort(403);
        }
        return view('staff.attendance.show', compact('attendance'));
    }

    /**
     * Update keterangan / status for a PAST day (date < today).
     * Clock times are locked — only status (izin/sakit/cuti/alpha) + note can be changed.
     */
    public function updateNote(Request $request, Attendance $attendance)
    {
        if ($attendance->user_id !== auth()->id()) abort(403);

        // Only allow editing past days via this endpoint
        if ($attendance->date->isToday()) {
            return redirect()->back()->with('error', 'Gunakan tombol Ubah Kehadiran untuk hari ini.');
        }

        $request->validate([
            'status' => 'required|in:hadir,terlambat,izin,sakit,alpha,cuti',
            'note'   => 'nullable|string|max:500',
        ]);

        $attendance->update([
            'status' => $request->status,
            'note'   => $request->note,
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
