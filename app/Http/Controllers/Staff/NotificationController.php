<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('pengguna_id', auth()->id());

        if ($request->input('filter') === 'unread') {
            $query->where('sudah_dibaca', false);
        }

        $notifications = $query->latest()->paginate(20);
        return view('staf.notifikasi.index', compact('notifications'));
    }

    public function json()
    {
        $notifications = Notification::where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->latest()
            ->take(10)
            ->get(['id', 'judul', 'pesan', 'jenis', 'sudah_dibaca', 'created_at']);

        $unreadCount = Notification::where('pengguna_id', auth()->id())->where('sudah_dibaca', false)->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'judul' => $n->judul,
                    'pesan' => \Str::limit($n->pesan, 60),
                    'jenis' => $n->jenis,
                    'time' => $n->created_at->diffForHumans(),
                    'read_url' => route('staf.notifikasi.baca', $n->id),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->pengguna_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['sudah_dibaca' => true]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        if ($notification->tautan) {
            return redirect($notification->tautan);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notification::where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
