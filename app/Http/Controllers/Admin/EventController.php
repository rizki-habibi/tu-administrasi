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

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month . '-01');
            $query->whereYear('tanggal_acara', $date->year)
                  ->whereMonth('tanggal_acara', $date->month);
        }

        $events = $query->orderBy('tanggal_acara', 'desc')->paginate(15);
        return view('admin.agenda.index', compact('events'));
    }

    public function create()
    {
        return view('admin.agenda.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_acara' => 'required|date',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'nullable|string|max:255',
            'jenis' => 'required|in:rapat,kegiatan,upacara,pelatihan,lainnya',
        ]);

        $event = Event::create(array_merge($request->all(), [
            'dibuat_oleh' => auth()->id(),
        ]));

        // Notify all staff
        $staffs = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->get();
        foreach ($staffs as $staff) {
            Notification::create([
                'pengguna_id' => $staff->id,
                'judul' => 'Event Baru: ' . $event->judul,
                'pesan' => "Ada event baru pada {$event->tanggal_acara->format('d/m/Y')}: {$event->judul}",
                'jenis' => 'event',
                'tautan' => route('staf.agenda.show', $event->id),
            ]);
        }

        return redirect()->route('admin.agenda.index')->with('success', 'Event berhasil dibuat.');
    }

    public function show(Event $event)
    {
        $event->load('creator');
        return view('admin.agenda.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.agenda.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_acara' => 'required|date',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'nullable|string|max:255',
            'jenis' => 'required|in:rapat,kegiatan,upacara,pelatihan,lainnya',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $event->update($request->all());
        return redirect()->route('admin.agenda.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.agenda.index')->with('success', 'Event berhasil dihapus.');
    }
}
