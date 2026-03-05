<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Acara::query();

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tanggal_acara', $request->month);
        }

        $events = $query->orderBy('tanggal_acara', 'desc')->paginate(15);
        $upcomingEvents = Acara::where('tanggal_acara', '>=', today())->where('status', 'upcoming')->orderBy('tanggal_acara')->get();

        return view('staf.agenda.index', compact('events', 'upcomingEvents'));
    }

    public function show(Acara $event)
    {
        $event->load('creator');
        return view('staf.agenda.show', compact('event'));
    }
}
