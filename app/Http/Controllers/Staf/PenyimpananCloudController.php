<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\PenyimpananCloud;
use Illuminate\Http\Request;

class PenyimpananCloudController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PenyimpananCloud::where('pengguna_id', $user->id)->latest();

        if ($request->filled('jenis_drive')) {
            $query->where('jenis_drive', $request->jenis_drive);
        }
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $drives = $query->paginate(15);

        return view('staf.cloud-drive.index', compact('drives'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_drive' => 'required|string|in:google_drive,google_drive_bisnis,onedrive,terabox,custom',
            'jenis_drive_kustom' => 'nullable|required_if:jenis_drive,custom|string|max:100',
            'jenis_data' => 'required|string',
            'url_link' => 'required|url|max:2000',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $validated['pengguna_id'] = $user->id;
        $validated['peran_pemilik'] = $user->peran;
        $validated['bisa_dihapus'] = false; // Data dari staf = penting, hanya admin bisa hapus

        PenyimpananCloud::create($validated);

        LogAktivitas::catat('create', 'penyimpanan_cloud', 'Menambahkan cloud drive: ' . $validated['nama']);

        return back()->with('sukses', 'Cloud drive berhasil ditambahkan!');
    }

    public function update(Request $request, PenyimpananCloud $cloud)
    {
        // Pastikan hanya pemilik yang bisa edit
        if ($cloud->pengguna_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_drive' => 'required|string|in:google_drive,google_drive_bisnis,onedrive,terabox,custom',
            'jenis_drive_kustom' => 'nullable|required_if:jenis_drive,custom|string|max:100',
            'jenis_data' => 'required|string',
            'url_link' => 'required|url|max:2000',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $cloud->update($validated);

        LogAktivitas::catat('update', 'penyimpanan_cloud', 'Memperbarui cloud drive: ' . $cloud->nama);

        return back()->with('sukses', 'Cloud drive berhasil diperbarui!');
    }

    // Staf TIDAK bisa hapus (data penting) — hanya admin bisa
}
