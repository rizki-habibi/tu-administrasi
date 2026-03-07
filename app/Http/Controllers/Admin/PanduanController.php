<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use App\Services\LayananCadanganGoogleDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PanduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Panduan::with('pembuat')->orderBy('urutan')->orderBy('created_at', 'desc');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%")
                  ->orWhere('konten', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('visibilitas')) {
            $query->where('visibilitas', $request->visibilitas);
        }

        $panduan = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Panduan::count(),
            'aktif' => Panduan::where('aktif', true)->count(),
            'panduan' => Panduan::where('kategori', 'panduan')->count(),
            'dokumentasi' => Panduan::where('kategori', 'dokumentasi')->count(),
        ];

        return view('admin.panduan.index', compact('panduan', 'stats'));
    }

    public function create()
    {
        return view('admin.panduan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:500',
            'konten'       => 'nullable|string',
            'ikon'         => 'required|string|max:50',
            'warna'        => 'required|string|max:20',
            'versi'        => 'nullable|string|max:20',
            'kategori'     => 'required|in:panduan,dokumentasi,changelog,referensi',
            'visibilitas'  => 'required|in:semua,admin',
            'urutan'       => 'nullable|integer|min:0',
            'aktif'        => 'nullable|boolean',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $data = [
            'judul'       => $validated['judul'],
            'slug'        => Str::slug($validated['judul']),
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'konten'      => $validated['konten'] ?? null,
            'ikon'        => $validated['ikon'],
            'warna'       => $validated['warna'],
            'versi'       => $validated['versi'] ?? null,
            'kategori'    => $validated['kategori'],
            'visibilitas' => $validated['visibilitas'],
            'urutan'      => $validated['urutan'] ?? 0,
            'aktif'       => $request->has('aktif'),
            'dibuat_oleh' => auth()->id(),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('panduan/logo', 'public');
        }

        $baseSlug = $data['slug'];
        $i = 1;
        while (Panduan::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $i++;
        }

        Panduan::create($data);

        return redirect()->route('admin.panduan.index')->with('success', 'Panduan berhasil ditambahkan!');
    }

    public function show(Panduan $panduan)
    {
        $panduan->load('pembuat');
        $dokumenList = Panduan::aktif()->orderBy('urutan')->get();

        return view('admin.panduan.show', compact('panduan', 'dokumenList'));
    }

    public function edit(Panduan $panduan)
    {
        return view('admin.panduan.edit', compact('panduan'));
    }

    public function update(Request $request, Panduan $panduan)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:500',
            'konten'       => 'nullable|string',
            'ikon'         => 'required|string|max:50',
            'warna'        => 'required|string|max:20',
            'versi'        => 'nullable|string|max:20',
            'kategori'     => 'required|in:panduan,dokumentasi,changelog,referensi',
            'visibilitas'  => 'required|in:semua,admin',
            'urutan'       => 'nullable|integer|min:0',
            'aktif'        => 'nullable|boolean',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $data = [
            'judul'       => $validated['judul'],
            'slug'        => Str::slug($validated['judul']),
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'konten'      => $validated['konten'] ?? null,
            'ikon'        => $validated['ikon'],
            'warna'       => $validated['warna'],
            'versi'       => $validated['versi'] ?? null,
            'kategori'    => $validated['kategori'],
            'visibilitas' => $validated['visibilitas'],
            'urutan'      => $validated['urutan'] ?? 0,
            'aktif'       => $request->has('aktif'),
        ];

        if ($request->hasFile('logo')) {
            if ($panduan->logo) {
                Storage::disk('public')->delete($panduan->logo);
            }
            $data['logo'] = $request->file('logo')->store('panduan/logo', 'public');
        }

        if ($request->has('hapus_logo') && $panduan->logo) {
            Storage::disk('public')->delete($panduan->logo);
            $data['logo'] = null;
        }

        $baseSlug = $data['slug'];
        $i = 1;
        while (Panduan::where('slug', $data['slug'])->where('id', '!=', $panduan->id)->exists()) {
            $data['slug'] = $baseSlug . '-' . $i++;
        }

        $panduan->update($data);

        return redirect()->route('admin.panduan.index')->with('success', 'Panduan berhasil diperbarui!');
    }

    public function destroy(Panduan $panduan)
    {
        if ($panduan->logo) {
            Storage::disk('public')->delete($panduan->logo);
        }

        $panduan->delete();

        return redirect()->route('admin.panduan.index')->with('success', 'Panduan berhasil dihapus!');
    }

    public function download(Panduan $panduan)
    {
        $panduan->load('pembuat');
        $html = view('komponen.panduan-cetak', compact('panduan'))->render();
        $filename = Str::slug($panduan->judul) . '.html';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function uploadDrive(Panduan $panduan)
    {
        $panduan->load('pembuat');
        $html = view('komponen.panduan-cetak', compact('panduan'))->render();
        $filename = Str::slug($panduan->judul) . '.html';
        $tempPath = storage_path('app/temp/' . $filename);

        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }
        file_put_contents($tempPath, $html);

        $driveService = new LayananCadanganGoogleDrive();
        if (!$driveService->initClient()) {
            @unlink($tempPath);
            return back()->with('error', 'Google Drive belum terhubung. Silakan hubungkan Google Drive terlebih dahulu di pengaturan.');
        }

        $fileId = $driveService->uploadFile($tempPath, 'TU_Admin_Panduan');
        @unlink($tempPath);

        if ($fileId) {
            return back()->with('success', 'Dokumen berhasil diupload ke Google Drive!');
        }

        return back()->with('error', 'Gagal mengupload ke Google Drive. Silakan coba lagi.');
    }
}
