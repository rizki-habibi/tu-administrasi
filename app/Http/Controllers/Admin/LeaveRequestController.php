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

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $leaveRequests = $query->latest()->paginate(15);
        return view('admin.leave.index', compact('leaveRequests'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load('user', 'approver');
        return view('admin.leave.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        Notification::create([
            'user_id' => $leaveRequest->user_id,
            'title' => 'Pengajuan Disetujui',
            'message' => "Pengajuan {$leaveRequest->type} Anda dari tanggal {$leaveRequest->start_date->format('d/m/Y')} s/d {$leaveRequest->end_date->format('d/m/Y')} telah disetujui.",
            'type' => 'izin',
            'link' => route('staff.leave.show', $leaveRequest->id),
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['admin_note' => 'required|string']);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'admin_note' => $request->admin_note,
        ]);

        Notification::create([
            'user_id' => $leaveRequest->user_id,
            'title' => 'Pengajuan Ditolak',
            'message' => "Pengajuan {$leaveRequest->type} Anda ditolak. Alasan: {$request->admin_note}",
            'type' => 'izin',
            'link' => route('staff.leave.show', $leaveRequest->id),
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
