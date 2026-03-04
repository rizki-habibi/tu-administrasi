<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with('user', 'approver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $leaveRequests = $query->latest()->paginate(15);
        return view('admin.izin.index', compact('leaveRequests'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load('user', 'approver');
        return view('admin.izin.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'disetujui_oleh' => auth()->id(),
        ]);

        Notification::create([
            'pengguna_id' => $leaveRequest->pengguna_id,
            'judul' => 'Pengajuan Disetujui',
            'pesan' => "Pengajuan {$leaveRequest->jenis} Anda dari tanggal {$leaveRequest->tanggal_mulai->format('d/m/Y')} s/d {$leaveRequest->tanggal_selesai->format('d/m/Y')} telah disetujui.",
            'jenis' => 'izin',
            'tautan' => route('staf.izin.show', $leaveRequest->id),
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['catatan_admin' => 'required|string']);

        $leaveRequest->update([
            'status' => 'rejected',
            'disetujui_oleh' => auth()->id(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        Notification::create([
            'pengguna_id' => $leaveRequest->pengguna_id,
            'judul' => 'Pengajuan Ditolak',
            'pesan' => "Pengajuan {$leaveRequest->jenis} Anda ditolak. Alasan: {$request->catatan_admin}",
            'jenis' => 'izin',
            'tautan' => route('staf.izin.show', $leaveRequest->id),
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
