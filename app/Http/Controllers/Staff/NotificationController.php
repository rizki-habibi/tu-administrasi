<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id());

        if ($request->input('filter') === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->latest()->paginate(20);
        return view('staff.notification.index', compact('notifications'));
    }

    public function json()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->take(10)
            ->get(['id', 'title', 'message', 'type', 'is_read', 'created_at']);

        $unreadCount = Notification::where('user_id', auth()->id())->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => \Str::limit($n->message, 60),
                    'type' => $n->type,
                    'time' => $n->created_at->diffForHumans(),
                    'read_url' => route('staff.notification.read', $n->id),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
