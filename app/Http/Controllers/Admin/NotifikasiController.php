<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Notifikasi::with('user');

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $notifications = $query->latest()->paginate(20);
        return view('admin.notifikasi.index', compact('notifications'));
    }

    public function json()
    {
        $notifications = Notifikasi::where('sudah_dibaca', false)
            ->latest()
            ->take(10)
            ->get(['id', 'judul', 'pesan', 'jenis', 'pengguna_id', 'sudah_dibaca', 'created_at']);

        $unreadCount = Notifikasi::where('sudah_dibaca', false)->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'judul' => $n->judul,
                    'pesan' => \Str::limit($n->pesan, 60),
                    'jenis' => $n->jenis,
                    'time' => $n->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    public function create()
    {
        $staffs = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();
        return view('admin.notifikasi.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'jenis' => 'required|in:kehadiran,izin,event,laporan,sistem,pengumuman',
            'target' => 'required|in:all,specific',
            'user_ids' => 'required_if:target,specific|array',
        ]);

        if ($request->target === 'all') {
            $users = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();
        } else {
            $users = Pengguna::whereIn('id', $request->user_ids)->get();
        }

        foreach ($users as $user) {
            Notifikasi::create([
                'pengguna_id' => $user->id,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
                'jenis' => $request->jenis,
            ]);
        }

        return redirect()->route('admin.notifikasi.index')->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' staff.');
    }

    public function destroy(Notifikasi $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
