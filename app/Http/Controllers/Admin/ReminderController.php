<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $query = Reminder::with('user', 'creator')->latest();

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->input('filter') === 'overdue') $query->overdue();
        if ($request->input('filter') === 'upcoming') $query->upcoming();

        $reminders = $query->paginate(20)->withQueryString();
        $overdueCount = Reminder::overdue()->count();
        $upcomingCount = Reminder::upcoming()->count();

        return view('admin.reminder.index', compact('reminders', 'overdueCount', 'upcomingCount'));
    }

    public function create()
    {
        $staffs = User::where('role', 'staff')->where('is_active', true)->get();
        return view('admin.reminder.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:deadline_laporan,bkd,evaluasi_semester,tugas,lainnya',
            'due_date' => 'required|date',
            'reminder_time' => 'nullable',
            'target' => 'required|in:all,specific',
            'user_ids' => 'required_if:target,specific|array',
        ]);

        if ($request->target === 'all') {
            $users = User::where('role', 'staff')->where('is_active', true)->get();
        } else {
            $users = User::whereIn('id', $request->user_ids)->get();
        }

        foreach ($users as $user) {
            Reminder::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'due_date' => $request->due_date,
                'reminder_time' => $request->reminder_time,
                'user_id' => $user->id,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.reminder.index')->with('success', 'Pengingat berhasil dibuat untuk ' . $users->count() . ' staff.');
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return redirect()->back()->with('success', 'Pengingat berhasil dihapus.');
    }

    public function toggleComplete(Reminder $reminder)
    {
        $reminder->update(['is_completed' => !$reminder->is_completed]);
        return redirect()->back()->with('success', 'Status pengingat diperbarui.');
    }
}
