<?php

namespace App\Http\Controllers;

use App\Models\KontenPublik;
use App\Models\SaranPengunjung;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class KinerjaController extends Controller
{
    public function index(Request $request)
    {
        // Catat kunjungan
        if (Schema::hasTable('pengunjung')) {
            Pengunjung::catat($request, '/kinerja');
        }

        // Ambil semua konten aktif untuk halaman kinerja
        $konten = KontenPublik::aktif()
            ->bagian('kinerja')
            ->orderBy('kategori')
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kategori');

        // Konten unggulan
        $unggulan = KontenPublik::aktif()
            ->bagian('kinerja')
            ->unggulan()
            ->orderBy('urutan')
            ->take(6)
            ->get();

        return view('kinerja', compact('konten', 'unggulan'));
    }

    public function storeSaran(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'email'  => 'nullable|email|max:150',
            'subjek' => 'required|string|max:200',
            'pesan'  => 'required|string|max:2000',
        ]);

        SaranPengunjung::create($validated);

        return back()->with('sukses', 'Terima kasih! Saran Anda telah berhasil dikirim.');
    }
}
