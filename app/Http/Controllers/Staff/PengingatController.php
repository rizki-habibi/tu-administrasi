<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pengingat;
use Illuminate\Http\Request;

class PengingatController extends Controller
{
    public function index()
    {
        $activeReminders = Pengingat::where('pengguna_id', auth()->id())
            ->where('selesai', false)
            ->orderBy('tenggat')
            ->get();

        $completedReminders = Pengingat::where('pengguna_id', auth()->id())
            ->where('selesai', true)
            ->latest()
            ->take(10)
            ->get();

        $overdueCount = $activeReminders->filter(fn($r) => $r->tenggat->isPast())->count();
        $activeCount = $activeReminders->count();
        $completedCount = Pengingat::where('pengguna_id', auth()->id())->where('selesai', true)->count();

        return view('staf.pengingat.index', compact('activeReminders', 'completedReminders', 'overdueCount', 'activeCount', 'completedCount'));
    }

    public function markComplete(Pengingat $reminder)
    {
        if ($reminder->pengguna_id != auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $reminder->update(['selesai' => true]);
        return back()->with('success', 'Pengingat ditandai selesai.');
    }
}
