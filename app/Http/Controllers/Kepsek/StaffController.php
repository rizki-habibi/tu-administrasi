<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true);

        if ($request->filled('peran')) {
            $query->where('peran', $request->peran);
        }
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $staffs = $query->orderBy('nama')->paginate(20);
        return view('kepala-sekolah.pegawai.index', compact('staffs'));
    }

    public function show(User $staff)
    {
        $staff->load(['attendances' => fn($q) => $q->latest()->take(30), 'leaveRequests' => fn($q) => $q->latest()->take(10), 'skp' => fn($q) => $q->latest()]);
        return view('kepala-sekolah.pegawai.show', compact('staff'));
    }
}
