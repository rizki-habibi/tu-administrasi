<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $todayAttendance = Attendance::where('user_id', $user->id)->whereDate('date', today())->first();

        $monthlyStats = [
            'hadir' => Attendance::where('user_id', $user->id)->where('status', 'hadir')->whereMonth('date', now()->month)->count(),
            'terlambat' => Attendance::where('user_id', $user->id)->where('status', 'terlambat')->whereMonth('date', now()->month)->count(),
            'izin' => Attendance::where('user_id', $user->id)->where('status', 'izin')->whereMonth('date', now()->month)->count(),
            'sakit' => Attendance::where('user_id', $user->id)->where('status', 'sakit')->whereMonth('date', now()->month)->count(),
            'alpha' => Attendance::where('user_id', $user->id)->where('status', 'alpha')->whereMonth('date', now()->month)->count(),
        ];

        $pendingLeaves = LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count();
        $upcomingEvents = Event::where('event_date', '>=', today())->where('status', 'upcoming')->orderBy('event_date')->take(5)->get();
        $unreadNotifications = Notification::where('user_id', $user->id)->where('is_read', false)->count();
        $recentNotifications = Notification::where('user_id', $user->id)->latest()->take(5)->get();

        return view('staff.dashboard', compact(
            'todayAttendance', 'monthlyStats', 'pendingLeaves',
            'upcomingEvents', 'unreadNotifications', 'recentNotifications'
        ));
    }
}
