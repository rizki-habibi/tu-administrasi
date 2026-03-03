<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $activeReminders = Reminder::where('user_id', auth()->id())
            ->where('is_completed', false)
            ->orderBy('due_date')
            ->get();

        $completedReminders = Reminder::where('user_id', auth()->id())
            ->where('is_completed', true)
            ->latest()
            ->take(10)
            ->get();

        $overdueCount = $activeReminders->filter(fn($r) => $r->due_date->isPast())->count();
        $activeCount = $activeReminders->count();
        $completedCount = Reminder::where('user_id', auth()->id())->where('is_completed', true)->count();

        return view('staff.reminder.index', compact('activeReminders', 'completedReminders', 'overdueCount', 'activeCount', 'completedCount'));
    }

    public function markComplete(Reminder $reminder)
    {
        if ($reminder->user_id != auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $reminder->update(['is_completed' => true]);
        return back()->with('success', 'Pengingat ditandai selesai.');
    }
}
