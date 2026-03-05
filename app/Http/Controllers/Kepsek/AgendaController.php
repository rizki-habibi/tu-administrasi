<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $events = Acara::latest('tanggal_acara')->paginate(20);
        return view('kepala-sekolah.agenda.index', compact('events'));
    }

    public function show(Acara $event)
    {
        return view('kepala-sekolah.agenda.show', compact('event'));
    }
}
