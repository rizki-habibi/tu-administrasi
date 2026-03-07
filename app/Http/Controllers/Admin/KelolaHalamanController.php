<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontenPublik;
use App\Models\SaranPengunjung;
use App\Models\Pengunjung;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class KelolaHalamanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Konten Publik CRUD
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = KontenPublik::with('pembuat')->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('bagian')) {
            $query->where('bagian', $request->bagian);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhere('deskripsi', 'like', "%{$request->search}%");
            });
        }

        $konten = $query->paginate(15)->withQueryString();

        return view('admin.halaman-publik.index', compact('konten'));
    }

    public function create()
    {
        return view('admin.halaman-publik.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:1000',
            'konten'       => 'nullable|string',
            'kategori'     => 'required|in:profil,visi_misi,pengurus,dokumen,galeri,video,kerjasama,prestasi,pengumuman,saran',
            'tipe'         => 'required|in:teks,gambar,video,dokumen,link',
            'bagian'       => 'required|in:halaman_utama,kinerja,keduanya',
            'url_external' => 'nullable|url|max:500',
            'urutan'       => 'nullable|integer|min:0',
            'aktif'        => 'nullable|boolean',
            'unggulan'     => 'nullable|boolean',
            'file'         => 'nullable|file|max:20480',
            'thumbnail'    => 'nullable|image|max:2048',
        ]);

        $data = collect($validated)->except(['file', 'thumbnail'])->toArray();
        $data['aktif'] = $request->boolean('aktif', true);
        $data['unggulan'] = $request->boolean('unggulan', false);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('konten-publik', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['tipe_file'] = $file->getMimeType();
            $data['ukuran_file'] = $file->getSize();
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('konten-publik/thumbnails', 'public');
        }

        $konten = KontenPublik::create($data);

        LogAktivitas::catat('create', 'halaman_publik', "Menambah konten publik: {$konten->judul}", $konten);

        return redirect()->route('admin.halaman-publik.index')
            ->with('success', 'Konten berhasil ditambahkan.');
    }

    public function show(KontenPublik $kontenPublik)
    {
        return view('admin.halaman-publik.show', compact('kontenPublik'));
    }

    public function edit(KontenPublik $kontenPublik)
    {
        return view('admin.halaman-publik.edit', compact('kontenPublik'));
    }

    public function update(Request $request, KontenPublik $kontenPublik)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:1000',
            'konten'       => 'nullable|string',
            'kategori'     => 'required|in:profil,visi_misi,pengurus,dokumen,galeri,video,kerjasama,prestasi,pengumuman,saran',
            'tipe'         => 'required|in:teks,gambar,video,dokumen,link',
            'bagian'       => 'required|in:halaman_utama,kinerja,keduanya',
            'url_external' => 'nullable|url|max:500',
            'urutan'       => 'nullable|integer|min:0',
            'aktif'        => 'nullable|boolean',
            'unggulan'     => 'nullable|boolean',
            'file'         => 'nullable|file|max:20480',
            'thumbnail'    => 'nullable|image|max:2048',
        ]);

        $data = collect($validated)->except(['file', 'thumbnail'])->toArray();
        $data['aktif'] = $request->boolean('aktif', true);
        $data['unggulan'] = $request->boolean('unggulan', false);
        $data['urutan'] = $data['urutan'] ?? 0;

        if ($request->hasFile('file')) {
            if ($kontenPublik->path_file) {
                Storage::disk('public')->delete($kontenPublik->path_file);
            }
            $file = $request->file('file');
            $data['path_file'] = $file->store('konten-publik', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['tipe_file'] = $file->getMimeType();
            $data['ukuran_file'] = $file->getSize();
        }

        if ($request->hasFile('thumbnail')) {
            if ($kontenPublik->thumbnail) {
                Storage::disk('public')->delete($kontenPublik->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('konten-publik/thumbnails', 'public');
        }

        $kontenPublik->update($data);

        LogAktivitas::catat('update', 'halaman_publik', "Mengubah konten publik: {$kontenPublik->judul}", $kontenPublik);

        return redirect()->route('admin.halaman-publik.index')
            ->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(KontenPublik $kontenPublik)
    {
        if ($kontenPublik->path_file) {
            Storage::disk('public')->delete($kontenPublik->path_file);
        }
        if ($kontenPublik->thumbnail) {
            Storage::disk('public')->delete($kontenPublik->thumbnail);
        }

        LogAktivitas::catat('delete', 'halaman_publik', "Menghapus konten publik: {$kontenPublik->judul}", $kontenPublik);

        $kontenPublik->delete();

        return redirect()->route('admin.halaman-publik.index')
            ->with('success', 'Konten berhasil dihapus.');
    }

    public function toggleAktif(KontenPublik $kontenPublik)
    {
        $kontenPublik->update(['aktif' => !$kontenPublik->aktif]);

        return back()->with('success', 'Status konten diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | Saran Pengunjung
    |--------------------------------------------------------------------------
    */

    public function saranIndex(Request $request)
    {
        $query = SaranPengunjung::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $saran = $query->paginate(15)->withQueryString();
        $countBaru = SaranPengunjung::baru()->count();

        return view('admin.halaman-publik.saran', compact('saran', 'countBaru'));
    }

    public function saranTanggapi(Request $request, SaranPengunjung $saranPengunjung)
    {
        $request->validate([
            'tanggapan' => 'required|string|max:2000',
        ]);

        $saranPengunjung->update([
            'status' => 'ditanggapi',
            'tanggapan' => $request->tanggapan,
            'ditanggapi_oleh' => auth()->id(),
            'ditanggapi_pada' => now(),
        ]);

        return back()->with('success', 'Saran berhasil ditanggapi.');
    }

    public function saranDestroy(SaranPengunjung $saranPengunjung)
    {
        $saranPengunjung->delete();
        return back()->with('success', 'Saran dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Statistik Pengunjung
    |--------------------------------------------------------------------------
    */

    public function statistikPengunjung()
    {
        $tersedia = Schema::hasTable('pengunjung');

        $stats = [
            'hari_ini'   => $tersedia ? Pengunjung::hariIni() : 0,
            'bulan_ini'  => $tersedia ? Pengunjung::bulanIni() : 0,
            'total_unik' => $tersedia ? Pengunjung::totalUnik() : 0,
            'total_kunjungan' => $tersedia ? Pengunjung::totalKunjungan() : 0,
        ];

        $pengunjungTerbaru = $tersedia
            ? Pengunjung::latest()->take(50)->get()
            : collect();

        // Data per hari (30 hari terakhir) untuk chart
        $chartData = $tersedia
            ? Pengunjung::selectRaw('DATE(created_at) as tanggal, COUNT(DISTINCT ip_address) as unik, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupByRaw('DATE(created_at)')
                ->orderBy('tanggal')
                ->get()
            : collect();

        return view('admin.halaman-publik.statistik', compact('stats', 'pengunjungTerbaru', 'chartData'));
    }
}
