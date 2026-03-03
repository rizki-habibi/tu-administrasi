<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('creator');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month . '-01');
            $query->whereYear('event_date', $date->year)
                  ->whereMonth('event_date', $date->month);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(15);
        return view('admin.event.index', compact('events'));
    }

    public function create()
    {
        return view('admin.event.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:rapat,kegiatan,upacara,pelatihan,lainnya',
        ]);

        $event = Event::create(array_merge($request->all(), [
            'created_by' => auth()->id(),
        ]));

        // Notify all staff
        $staffs = User::where('role', 'staff')->where('is_active', true)->get();
        foreach ($staffs as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'title' => 'Event Baru: ' . $event->title,
                'message' => "Ada event baru pada {$event->event_date->format('d/m/Y')}: {$event->title}",
                'type' => 'event',
                'link' => route('staff.event.show', $event->id),
            ]);
        }

        return redirect()->route('admin.event.index')->with('success', 'Event berhasil dibuat.');
    }

    public function show(Event $event)
    {
        $event->load('creator');
        return view('admin.event.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.event.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:rapat,kegiatan,upacara,pelatihan,lainnya',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $event->update($request->all());
        return redirect()->route('admin.event.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.event.index')->with('success', 'Event berhasil dihapus.');
    }
}
