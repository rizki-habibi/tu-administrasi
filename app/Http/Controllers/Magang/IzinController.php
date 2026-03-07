<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanIzin::where('pengguna_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->latest()->paginate(15);
        return view('magang.izin.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('magang.izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:izin,sakit',
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

        // Notifikasi ke admin
        $admins = Pengguna::where('peran', 'admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'pengguna_id' => $admin->id,
                'judul' => 'Izin Magang',
                'pesan' => auth()->user()->nama . " (Magang) mengajukan {$leave->jenis}",
                'jenis' => 'izin',
                'tautan' => route('admin.izin.show', $leave->id),
            ]);
        }

        return redirect()->route('magang.izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    public function show(PengajuanIzin $izin)
    {
        abort_if($izin->pengguna_id !== auth()->id(), 403);
        return view('magang.izin.show', compact('izin'));
    }

    public function destroy(PengajuanIzin $izin)
    {
        abort_if($izin->pengguna_id !== auth()->id(), 403);
        abort_if($izin->status !== 'pending', 403, 'Hanya izin pending yang bisa dibatalkan.');

        $izin->delete();

        return redirect()->route('magang.izin.index')
            ->with('success', 'Pengajuan izin dibatalkan.');
    }
}
