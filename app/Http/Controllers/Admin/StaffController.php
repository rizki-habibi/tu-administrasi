<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('peran', User::STAFF_ROLES);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('aktif', $request->status === 'active');
        }

        $staffs = $query->latest()->paginate(15);
        return view('admin.pegawai.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|min:8|confirmed',
            'telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto', 'password_confirmation');
        $data['password'] = Hash::make($request->password);
        $data['peran'] = 'staff';

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('staff-photos', 'public');
        }

        $user = User::create($data);

        Notification::create([
            'pengguna_id' => $user->id,
            'judul' => 'Selamat Datang!',
            'pesan' => 'Akun Anda telah dibuat oleh admin. Silakan lengkapi profil Anda.',
            'jenis' => 'sistem',
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function show(User $staff)
    {
        $staff->load(['attendances' => function ($q) {
            $q->latest()->take(30);
        }, 'leaveRequests' => function ($q) {
            $q->latest()->take(10);
        }, 'reports' => function ($q) {
            $q->latest()->take(10);
        }]);

        $attendanceStats = [
            'hadir' => $staff->attendances()->where('status', 'hadir')->whereMonth('tanggal', now()->month)->count(),
            'terlambat' => $staff->attendances()->where('status', 'terlambat')->whereMonth('tanggal', now()->month)->count(),
            'izin' => $staff->attendances()->where('status', 'izin')->whereMonth('tanggal', now()->month)->count(),
            'sakit' => $staff->attendances()->where('status', 'sakit')->whereMonth('tanggal', now()->month)->count(),
            'alpha' => $staff->attendances()->where('status', 'alpha')->whereMonth('tanggal', now()->month)->count(),
        ];

        return view('admin.pegawai.show', compact('staff', 'attendanceStats'));
    }

    public function edit(User $staff)
    {
        return view('admin.pegawai.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $staff->id,
            'password' => 'nullable|min:8|confirmed',
            'telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'aktif' => 'boolean',
        ]);

        $data = $request->except('foto', 'password', 'password_confirmation');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($staff->foto) {
                Storage::disk('public')->delete($staff->foto);
            }
            $data['foto'] = $request->file('foto')->store('staff-photos', 'public');
        }

        $data['aktif'] = $request->has('aktif');
        $staff->update($data);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        if ($staff->foto) {
            Storage::disk('public')->delete($staff->foto);
        }
        $staff->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Staff berhasil dihapus.');
    }

    public function toggleStatus(User $staff)
    {
        $staff->update(['aktif' => !$staff->aktif]);
        $status = $staff->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Staff berhasil {$status}.");
    }

    public function export(Request $request)
    {
        $staffs = User::whereIn('peran', User::STAFF_ROLES)->latest()->get();
        $format = $request->get('format', 'csv');

        if ($format === 'csv' || $format === 'excel') {
            $filename = 'data_staff_' . now()->format('Ymd_His') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
            $callback = function() use ($staffs) {
                $f = fopen('php://output', 'w');
                fputcsv($f, ['No', 'Nama', 'Email', 'Jabatan', 'Telepon', 'Status', 'Tanggal Dibuat']);
                foreach ($staffs as $i => $s) {
                    fputcsv($f, [$i+1, $s->nama, $s->email, $s->jabatan ?? '-', $s->telepon ?? '-', $s->aktif ? 'Aktif' : 'Nonaktif', $s->created_at->format('d/m/Y')]);
                }
                fclose($f);
            };
            return response()->stream($callback, 200, $headers);
        }
        return view('admin.pegawai.cetak', compact('staffs'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        try {
            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');

            // Skip header row
            $header = fgetcsv($handle);
            $imported = 0;
            $skipped = 0;

            while (($row = fgetcsv($handle)) !== false) {
                // Expected: Nama, Email, Password, Jabatan, Telepon, Alamat
                if (count($row) < 2) continue;

                $name = trim($row[0] ?? '');
                $email = trim($row[1] ?? '');
                $password = trim($row[2] ?? 'password123');
                $position = trim($row[3] ?? '');
                $phone = trim($row[4] ?? '');
                $address = trim($row[5] ?? '');

                if (empty($name) || empty($email)) { $skipped++; continue; }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $skipped++; continue; }
                if (User::where('email', $email)->exists()) { $skipped++; continue; }

                User::create([
                    'nama' => $name,
                    'email' => $email,
                    'password' => \Hash::make($password ?: 'password123'),
                    'peran' => 'staff',
                    'jabatan' => $position ?: null,
                    'telepon' => $phone ?: null,
                    'alamat' => $address ?: null,
                    'aktif' => true,
                ]);
                $imported++;
            }

            fclose($handle);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'pesan' => "Berhasil import {$imported} staff." . ($skipped > 0 ? " {$skipped} data dilewati." : ''),
                ]);
            }

            return redirect()->route('admin.pegawai.index')->with('success', "Berhasil import {$imported} staff." . ($skipped > 0 ? " {$skipped} data dilewati." : ''));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'pesan' => 'Gagal memproses file: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
