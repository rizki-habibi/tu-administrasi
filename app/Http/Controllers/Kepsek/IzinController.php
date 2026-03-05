<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanIzin::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->latest()->paginate(20);
        return view('kepala-sekolah.izin.index', compact('leaveRequests'));
    }

    public function show(PengajuanIzin $leaveRequest)
    {
        $leaveRequest->load('user');
        return view('kepala-sekolah.izin.show', compact('leaveRequest'));
    }

    public function approve(PengajuanIzin $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'disetujui_oleh' => auth()->id(),
        ]);
        return redirect()->back()->with('success', 'Pengajuan izin disetujui.');
    }

    public function reject(Request $request, PengajuanIzin $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'disetujui_oleh' => auth()->id(),
            'rejection_reason' => $request->input('rejection_reason', ''),
        ]);
        return redirect()->back()->with('success', 'Pengajuan izin ditolak.');
    }
}
