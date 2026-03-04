<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest('tanggal_acara')->paginate(20);
        return view('kepala-sekolah.agenda.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('kepala-sekolah.agenda.show', compact('event'));
    }
}
