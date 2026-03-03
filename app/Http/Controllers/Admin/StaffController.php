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
        $query = User::where('role', 'staff');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $staffs = $query->latest()->paginate(15);
        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('photo', 'password_confirmation');
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'staff';

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff-photos', 'public');
        }

        $user = User::create($data);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Selamat Datang!',
            'message' => 'Akun Anda telah dibuat oleh admin. Silakan lengkapi profil Anda.',
            'type' => 'sistem',
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan.');
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
            'hadir' => $staff->attendances()->where('status', 'hadir')->whereMonth('date', now()->month)->count(),
            'terlambat' => $staff->attendances()->where('status', 'terlambat')->whereMonth('date', now()->month)->count(),
            'izin' => $staff->attendances()->where('status', 'izin')->whereMonth('date', now()->month)->count(),
            'sakit' => $staff->attendances()->where('status', 'sakit')->whereMonth('date', now()->month)->count(),
            'alpha' => $staff->attendances()->where('status', 'alpha')->whereMonth('date', now()->month)->count(),
        ];

        return view('admin.staff.show', compact('staff', 'attendanceStats'));
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('photo', 'password', 'password_confirmation');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }
            $data['photo'] = $request->file('photo')->store('staff-photos', 'public');
        }

        $data['is_active'] = $request->has('is_active');
        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        if ($staff->photo) {
            Storage::disk('public')->delete($staff->photo);
        }
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus.');
    }

    public function toggleStatus(User $staff)
    {
        $staff->update(['is_active' => !$staff->is_active]);
        $status = $staff->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Staff berhasil {$status}.");
    }

    public function export(Request $request)
    {
        $staffs = User::where('role', 'staff')->latest()->get();
        $format = $request->get('format', 'csv');

        if ($format === 'csv' || $format === 'excel') {
            $filename = 'data_staff_' . now()->format('Ymd_His') . '.csv';
            $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
            $callback = function() use ($staffs) {
                $f = fopen('php://output', 'w');
                fputcsv($f, ['No', 'Nama', 'Email', 'Jabatan', 'Telepon', 'Status', 'Tanggal Dibuat']);
                foreach ($staffs as $i => $s) {
                    fputcsv($f, [$i+1, $s->name, $s->email, $s->position ?? '-', $s->phone ?? '-', $s->is_active ? 'Aktif' : 'Nonaktif', $s->created_at->format('d/m/Y')]);
                }
                fclose($f);
            };
            return response()->stream($callback, 200, $headers);
        }
        return view('admin.staff.print', compact('staffs'));
    }
}
