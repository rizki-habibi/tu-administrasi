<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->latest()->paginate(15);
        return view('staff.leave.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('staff.leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:izin,sakit,cuti,dinas_luar',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->except('attachment');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('leave-attachments', 'public');
        }

        $leave = LeaveRequest::create($data);

        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Baru',
                'message' => auth()->user()->name . " mengajukan {$leave->type} dari {$leave->start_date->format('d/m/Y')} s/d {$leave->end_date->format('d/m/Y')}",
                'type' => 'izin',
                'link' => route('admin.leave.show', $leave->id),
            ]);
        }

        return redirect()->route('staff.leave.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->user_id !== auth()->id()) {
            abort(403);
        }
        $leaveRequest->load('approver');
        return view('staff.leave.show', compact('leaveRequest'));
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->user_id !== auth()->id() || $leaveRequest->status !== 'pending') {
            abort(403);
        }

        if ($leaveRequest->attachment) {
            Storage::disk('public')->delete($leaveRequest->attachment);
        }

        $leaveRequest->delete();
        return redirect()->route('staff.leave.index')->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}
