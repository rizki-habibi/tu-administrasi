<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\PengajuanIzin;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = PengajuanIzin::with('user', 'approver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $leaveRequests = $query->latest()->paginate(15);
        return view('admin.izin.index', compact('leaveRequests'));
    }

    public function show(PengajuanIzin $leaveRequest)
    {
        $leaveRequest->load('user', 'approver');
        return view('admin.izin.show', compact('leaveRequest'));
    }

    public function approve(PengajuanIzin $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'disetujui_oleh' => auth()->id(),
        ]);

        Notifikasi::create([
            'pengguna_id' => $leaveRequest->pengguna_id,
            'judul' => 'Pengajuan Disetujui',
            'pesan' => "Pengajuan {$leaveRequest->jenis} Anda dari tanggal {$leaveRequest->tanggal_mulai->format('d/m/Y')} s/d {$leaveRequest->tanggal_selesai->format('d/m/Y')} telah disetujui.",
            'jenis' => 'izin',
            'tautan' => route('staf.izin.show', $leaveRequest->id),
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, PengajuanIzin $leaveRequest)
    {
        $request->validate(['catatan_admin' => 'required|string']);

        $leaveRequest->update([
            'status' => 'rejected',
            'disetujui_oleh' => auth()->id(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        Notifikasi::create([
            'pengguna_id' => $leaveRequest->pengguna_id,
            'judul' => 'Pengajuan Ditolak',
            'pesan' => "Pengajuan {$leaveRequest->jenis} Anda ditolak. Alasan: {$request->catatan_admin}",
            'jenis' => 'izin',
            'tautan' => route('staf.izin.show', $leaveRequest->id),
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function export()
    {
        $rows = PengajuanIzin::with('user')->latest()->get()->map(function ($iz, $i) {
            return [
                $i + 1,
                $iz->user->nama ?? '-',
                ucfirst(str_replace('_', ' ', $iz->jenis)),
                $iz->tanggal_mulai?->format('d/m/Y'),
                $iz->tanggal_selesai?->format('d/m/Y'),
                $iz->alasan ?? '-',
                ucfirst($iz->status),
                $iz->created_at?->format('d/m/Y H:i'),
            ];
        });

        return $this->eksporCsv(
            'pengajuan_izin_' . now()->format('Ymd') . '.csv',
            ['No', 'Nama Staf', 'Jenis', 'Mulai', 'Selesai', 'Alasan', 'Status', 'Tanggal Ajukan'],
            $rows
        );
    }
}
