<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\LeaveRequest;
use App\Models\Report;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStaff = User::where('role', 'staff')->count();
        $activeStaff = User::where('role', 'staff')->where('is_active', true)->count();
        $todayPresent = Attendance::whereDate('date', today())->whereIn('status', ['hadir', 'terlambat'])->count();
        $todayLate = Attendance::whereDate('date', today())->where('status', 'terlambat')->count();
        $pendingLeave = LeaveRequest::where('status', 'pending')->count();
        $monthReports = Report::whereMonth('created_at', now()->month)->count();
        $totalDocs = Document::count();
        $upcomingEvents = Event::where('event_date', '>=', today())->where('status', 'upcoming')->take(5)->get();

        $recentAttendances = Attendance::with('user')->whereDate('date', today())->latest()->take(8)->get();
        $recentLeaves = LeaveRequest::with('user')->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStaff', 'activeStaff', 'todayPresent', 'todayLate',
            'pendingLeave', 'monthReports', 'totalDocs', 'upcomingEvents',
            'recentAttendances', 'recentLeaves'
        ));
    }
}
