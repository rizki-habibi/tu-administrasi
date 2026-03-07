<?php

namespace App\Http\Controllers;

use App\Models\KontenPublik;
use App\Models\SaranPengunjung;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DokumenPublikController extends Controller
{
    /**
     * Kategori yang tersedia di sidebar dokumen.
     */
    private array $kategoriMenu = [
        'profil'      => ['label' => 'Profil Sekolah',   'icon' => 'bi-building'],
        'visi_misi'   => ['label' => 'Visi & Misi',      'icon' => 'bi-bullseye'],
        'pengurus'    => ['label' => 'Pengurus',          'icon' => 'bi-people-fill'],
        'dokumen'     => ['label' => 'Dokumen',           'icon' => 'bi-file-earmark-text-fill'],
        'galeri'      => ['label' => 'Galeri & Media',    'icon' => 'bi-images'],
        'video'       => ['label' => 'Video',             'icon' => 'bi-play-circle-fill'],
        'kerjasama'   => ['label' => 'Kerjasama / MOU',   'icon' => 'bi-handshake'],
        'prestasi'    => ['label' => 'Prestasi',          'icon' => 'bi-trophy-fill'],
        'pengumuman'  => ['label' => 'Pengumuman',        'icon' => 'bi-megaphone-fill'],
    ];

    /**
     * Beranda dokumentasi — ringkasan semua kategori.
     */
    public function beranda(Request $request)
    {
        $this->catatKunjungan($request, '/dokumen');

        $konten = KontenPublik::aktif()
            ->bagian('kinerja')
            ->orderBy('kategori')
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kategori');

        $unggulan = KontenPublik::aktif()
            ->bagian('kinerja')
            ->unggulan()
            ->orderBy('urutan')
            ->take(6)
            ->get();

        $statistik = [];
        foreach ($this->kategoriMenu as $key => $info) {
            $statistik[$key] = KontenPublik::aktif()->bagian('kinerja')->where('kategori', $key)->count();
        }

        return view('dokumen.beranda', [
            'konten'       => $konten,
            'unggulan'     => $unggulan,
            'statistik'    => $statistik,
            'kategoriMenu' => $this->kategoriMenu,
            'aktifMenu'    => 'beranda',
        ]);
    }

    /**
     * Konten per kategori.
     */
    public function kategori(Request $request, string $kategori)
    {
        abort_unless(array_key_exists($kategori, $this->kategoriMenu), 404);

        $this->catatKunjungan($request, "/dokumen/{$kategori}");

        $query = KontenPublik::aktif()
            ->bagian('kinerja')
            ->where('kategori', $kategori)
            ->orderBy('urutan')
            ->latest();

        if ($request->filled('cari')) {
            $cari = trim($request->string('cari'));
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        $items = $query->paginate(12)->withQueryString();

        return view('dokumen.kategori', [
            'items'        => $items,
            'kategori'     => $kategori,
            'info'         => $this->kategoriMenu[$kategori],
            'kategoriMenu' => $this->kategoriMenu,
            'aktifMenu'    => $kategori,
            'cari'         => $request->string('cari'),
        ]);
    }

    /**
     * Arsip — semua dokumen file yang bisa diunduh.
     */
    public function arsip(Request $request)
    {
        $this->catatKunjungan($request, '/dokumen/arsip');

        $query = KontenPublik::aktif()
            ->bagian('kinerja')
            ->whereNotNull('path_file')
            ->orderBy('created_at', 'desc');

        if ($request->filled('cari')) {
            $cari = trim($request->string('cari'));
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('nama_file', 'like', "%{$cari}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return view('dokumen.arsip', [
            'items'        => $items,
            'kategoriMenu' => $this->kategoriMenu,
            'aktifMenu'    => 'arsip',
            'cari'         => $request->string('cari'),
        ]);
    }

    /**
     * Detail konten.
     */
    public function show(KontenPublik $kontenPublik)
    {
        abort_unless($kontenPublik->aktif && in_array($kontenPublik->bagian, ['kinerja', 'keduanya']), 404);

        return view('dokumen.show', [
            'item'         => $kontenPublik,
            'kategoriMenu' => $this->kategoriMenu,
            'aktifMenu'    => $kontenPublik->kategori,
        ]);
    }

    /**
     * Halaman saran & masukan.
     */
    public function saran()
    {
        return view('dokumen.saran', [
            'kategoriMenu' => $this->kategoriMenu,
            'aktifMenu'    => 'saran',
            'statistik'    => [],
        ]);
    }

    /**
     * Kirim saran pengunjung.
     */
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

    /**
     * Catat kunjungan pengunjung.
     */
    private function catatKunjungan(Request $request, string $halaman): void
    {
        if (Schema::hasTable('pengunjung')) {
            Pengunjung::catat($request, $halaman);
        }
    }
}
