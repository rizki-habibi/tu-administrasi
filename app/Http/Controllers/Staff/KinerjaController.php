<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AnalisisStar;
use App\Models\BuktiFisik;
use App\Models\KontenPublik;
use App\Models\EvaluasiGuru;
use App\Models\MetodePembelajaran;
use App\Models\PenilaianP5;
use App\Models\Skp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KinerjaController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $query = KontenPublik::query()
            ->aktif()
            ->bagian('kinerja')
            ->orderBy('urutan')
            ->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->string('kategori'));
        }

        if ($request->filled('cari')) {
            $cari = trim($request->string('cari'));
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%")
                    ->orWhere('konten', 'like', "%{$cari}%");
            });
        }

        $konten = $query->paginate(12)->withQueryString();

        $kategoriList = KontenPublik::query()
            ->aktif()
            ->bagian('kinerja')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $ringkasan = [
            'konten_total' => KontenPublik::query()->aktif()->bagian('kinerja')->count(),
            'konten_unggulan' => KontenPublik::query()->aktif()->bagian('kinerja')->where('unggulan', true)->count(),
            'skp_total' => Skp::query()->where('pengguna_id', $userId)->count(),
            'skp_diajukan' => Skp::query()->where('pengguna_id', $userId)->where('status', 'diajukan')->count(),
            'pkg_total' => EvaluasiGuru::query()->where('pengguna_id', $userId)->count(),
            'p5_total' => PenilaianP5::query()->count(),
            'star_total' => AnalisisStar::query()->where('dibuat_oleh', $userId)->count(),
            'bukti_total' => BuktiFisik::query()->where('diunggah_oleh', $userId)->count(),
            'pembelajaran_total' => MetodePembelajaran::query()->where('dibuat_oleh', $userId)->count(),
        ];

        return view('staf.kinerja.index', compact('konten', 'kategoriList', 'ringkasan'));
    }

    public function show(KontenPublik $kinerja)
    {
        abort_unless($kinerja->aktif && in_array($kinerja->bagian, ['kinerja', 'keduanya']), 404);

        return view('staf.kinerja.show', [
            'item' => $kinerja,
        ]);
    }

    public function download(KontenPublik $kinerja)
    {
        abort_unless($kinerja->aktif && in_array($kinerja->bagian, ['kinerja', 'keduanya']), 404);

        if ($kinerja->url_external) {
            return redirect()->away($kinerja->url_external);
        }

        if (!$kinerja->path_file || !Storage::disk('public')->exists($kinerja->path_file)) {
            return back()->with('error', 'File tidak tersedia untuk diunduh.');
        }

        return Storage::disk('public')->download($kinerja->path_file, $kinerja->nama_file ?: basename($kinerja->path_file));
    }
}
