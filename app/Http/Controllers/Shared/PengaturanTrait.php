<?php

namespace App\Http\Controllers\Shared;

use App\Models\PengaturanPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Shared Settings/Pengaturan logic — profil + tampilan + preferensi.
 */
trait PengaturanTrait
{
    public function index()
    {
        $user = auth()->user();
        $settings = PengaturanPengguna::semuaUntuk($user->id);
        $defaults = PengaturanPengguna::DEFAULTS;

        // Merge defaults with actual settings
        foreach ($defaults as $key => $val) {
            if (!isset($settings[$key])) {
                $settings[$key] = $val;
            }
        }

        $prefix = $user->getRoutePrefix();
        return view("{$prefix}.pengaturan.index", compact('settings'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'tanggal_lahir' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama', 'telepon', 'alamat', 'tanggal_lahir']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updateTampilan(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'tema' => 'required|in:gelap,terang',
            'ukuran_font' => 'required|in:kecil,normal,besar',
            'sidebar_mini' => 'required|in:true,false',
            'warna_aksen' => 'nullable|string|max:7',
            'notifikasi_suara' => 'required|in:true,false',
        ]);

        $keys = ['tema', 'ukuran_font', 'sidebar_mini', 'warna_aksen', 'notifikasi_suara'];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                PengaturanPengguna::atur($user->id, $key, $request->input($key));
            }
        }

        return response()->json(['success' => true, 'pesan' => 'Pengaturan tampilan berhasil disimpan.']);
    }
}
