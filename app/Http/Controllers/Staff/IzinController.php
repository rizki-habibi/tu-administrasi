<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanIzin::where('pengguna_id', auth()->id());

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
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->except('lampiran');
        $data['pengguna_id'] = auth()->id();

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('leave-attachments', 'public');
        }

        $leave = PengajuanIzin::create($data);

        // Notify admin
        $admins = Pengguna::where('peran', 'admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'pengguna_id' => $admin->id,
                'judul' => 'Pengajuan Baru',
                'pesan' => auth()->user()->nama . " mengajukan {$leave->jenis} dari {$leave->tanggal_mulai->format('d/m/Y')} s/d {$leave->tanggal_selesai->format('d/m/Y')}",
                'jenis' => 'izin',
                'tautan' => route('admin.izin.show', $leave->id),
            ]);
        }

        return redirect()->route('staf.izin.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function show(PengajuanIzin $leaveRequest)
    {
        if ($leaveRequest->pengguna_id !== auth()->id()) {
            abort(403);
        }
        $leaveRequest->load('approver');
        return view('staf.izin.show', compact('leaveRequest'));
    }

    public function destroy(PengajuanIzin $leaveRequest)
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
