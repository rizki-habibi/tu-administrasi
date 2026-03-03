<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::where(function($query) {
                $query->where('target', 'all')
                      ->orWhere('target_user_id', auth()->id());
            })
            ->where('is_completed', false)
            ->orderBy('due_date')
            ->paginate(15);

        $completed = Reminder::where(function($query) {
                $query->where('target', 'all')
                      ->orWhere('target_user_id', auth()->id());
            })
            ->where('is_completed', true)
            ->latest()
            ->take(10)
            ->get();

        return view('staff.reminder.index', compact('reminders', 'completed'));
    }

    public function markComplete(Reminder $reminder)
    {
        // Only mark if this reminder is targeted to this user
        if ($reminder->target_user_id == auth()->id() || $reminder->target == 'all') {
            $reminder->update(['is_completed' => true, 'completed_at' => now()]);
            return back()->with('success', 'Pengingat ditandai selesai.');
        }

        return back()->with('error', 'Anda tidak memiliki akses.');
    }
}
