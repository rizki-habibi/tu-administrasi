<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('month')) {
            $query->whereMonth('event_date', $request->month);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(15);
        $upcomingEvents = Event::where('event_date', '>=', today())->where('status', 'upcoming')->orderBy('event_date')->get();

        return view('staff.event.index', compact('events', 'upcomingEvents'));
    }

    public function show(Event $event)
    {
        $event->load('creator');
        return view('staff.event.show', compact('event'));
    }
}
