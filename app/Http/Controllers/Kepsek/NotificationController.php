<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('pengguna_id', auth()->id())->latest()->paginate(20);
        return view('kepala-sekolah.notifikasi.index', compact('notifications'));
    }

    public function json()
    {
        $notifications = Notification::where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', false)->latest()->take(10)
            ->get(['id', 'judul', 'pesan', 'jenis', 'sudah_dibaca', 'created_at']);

        $unreadCount = Notification::where('pengguna_id', auth()->id())->where('sudah_dibaca', false)->count();

        return response()->json([
            'notifications' => $notifications->map(fn($n) => [
                'id' => $n->id,
                'judul' => $n->judul,
                'pesan' => \Str::limit($n->pesan, 60),
                'jenis' => $n->jenis,
                'time' => $n->created_at->diffForHumans(),
                'read_url' => route('kepala-sekolah.notifikasi.read', $n->id),
            ]),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->pengguna_id !== auth()->id()) {
            abort(403);
        }
        $notification->update(['sudah_dibaca' => true]);
        return $notification->tautan ? redirect($notification->tautan) : redirect()->back();
    }
}
