<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = Notifikasi::where('pengguna_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('magang.notifikasi.index', compact('notifications'));
    }

    public function json()
    {
        $notifications = Notifikasi::where('pengguna_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    public function baca(Notifikasi $notifikasi)
    {
        abort_if($notifikasi->pengguna_id !== auth()->id(), 403);
        $notifikasi->update(['sudah_dibaca' => true]);

        if ($notifikasi->tautan) {
            return redirect($notifikasi->tautan);
        }

        return back();
    }

    public function bacaSemua()
    {
        Notifikasi::where('pengguna_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
