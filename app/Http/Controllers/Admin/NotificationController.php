<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->latest()->paginate(20);
        return view('admin.notification.index', compact('notifications'));
    }

    public function create()
    {
        $staffs = User::where('role', 'staff')->where('is_active', true)->get();
        return view('admin.notification.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:kehadiran,izin,event,laporan,sistem,pengumuman',
            'target' => 'required|in:all,specific',
            'user_ids' => 'required_if:target,specific|array',
        ]);

        if ($request->target === 'all') {
            $users = User::where('role', 'staff')->where('is_active', true)->get();
        } else {
            $users = User::whereIn('id', $request->user_ids)->get();
        }

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
            ]);
        }

        return redirect()->route('admin.notification.index')->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' staff.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
