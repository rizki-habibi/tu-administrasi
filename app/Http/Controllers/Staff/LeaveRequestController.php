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
        $query = LeaveRequest::where('pengguna_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->latest()->paginate(15);
        return view('staf.izin.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('staf.izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:izin,sakit,cuti,dinas_luar',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->except('lampiran');
        $data['pengguna_id'] = auth()->id();

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('leave-attachments', 'public');
        }

        $leave = LeaveRequest::create($data);

        // Notify admin
        $admins = User::where('peran', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'pengguna_id' => $admin->id,
                'judul' => 'Pengajuan Baru',
                'pesan' => auth()->user()->nama . " mengajukan {$leave->jenis} dari {$leave->tanggal_mulai->format('d/m/Y')} s/d {$leave->tanggal_selesai->format('d/m/Y')}",
                'jenis' => 'izin',
                'tautan' => route('admin.izin.show', $leave->id),
            ]);
        }

        return redirect()->route('staf.izin.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->pengguna_id !== auth()->id()) {
            abort(403);
        }
        $leaveRequest->load('approver');
        return view('staf.izin.show', compact('leaveRequest'));
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->pengguna_id !== auth()->id() || $leaveRequest->status !== 'pending') {
            abort(403);
        }

        if ($leaveRequest->lampiran) {
            Storage::disk('public')->delete($leaveRequest->lampiran);
        }

        $leaveRequest->delete();
        return redirect()->route('staf.izin.index')->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}
