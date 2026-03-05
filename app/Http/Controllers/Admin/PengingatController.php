<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengingat;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PengingatController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengingat::with('user', 'creator')->where('selesai', false)->latest();

        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);

        $reminders = $query->paginate(20)->withQueryString();
        $overdueCount = Pengingat::where('selesai', false)->where('tenggat', '<', now())->count();
        $activeCount = Pengingat::where('selesai', false)->count();
        $completedCount = Pengingat::where('selesai', true)->count();
        $completed = Pengingat::where('selesai', true)->latest('updated_at')->take(10)->get();

        return view('admin.pengingat.index', compact('reminders', 'overdueCount', 'activeCount', 'completedCount', 'completed'));
    }

    public function create()
    {
        $staffs = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();
        return view('admin.pengingat.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis' => 'required|in:deadline_laporan,bkd,evaluasi_semester,tugas,lainnya',
            'tenggat' => 'required|date',
            'waktu_pengingat' => 'nullable',
            'target' => 'required|in:all,specific',
            'user_ids' => 'required_if:target,specific|array',
        ]);

        if ($request->target === 'all') {
            $users = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();
        } else {
            $users = Pengguna::whereIn('id', $request->user_ids)->get();
        }

        foreach ($users as $user) {
            Pengingat::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'jenis' => $request->jenis,
                'tenggat' => $request->tenggat,
                'waktu_pengingat' => $request->waktu_pengingat,
                'pengguna_id' => $user->id,
                'dibuat_oleh' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.pengingat.index')->with('success', 'Pengingat berhasil dibuat untuk ' . $users->count() . ' staff.');
    }

    public function destroy(Pengingat $reminder)
    {
        $reminder->delete();
        return redirect()->back()->with('success', 'Pengingat berhasil dihapus.');
    }

    public function toggleComplete(Pengingat $reminder)
    {
        $reminder->update(['selesai' => !$reminder->selesai]);
        return redirect()->back()->with('success', 'Status pengingat diperbarui.');
    }
}
