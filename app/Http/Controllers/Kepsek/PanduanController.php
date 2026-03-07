<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use App\Services\LayananCadanganGoogleDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Panduan::aktif()->untukSemua()->orderBy('urutan')->orderBy('created_at', 'desc');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $panduan = $query->paginate(12)->withQueryString();

        return view('kepala-sekolah.panduan.index', compact('panduan'));
    }

    public function show(Panduan $panduan)
    {
        if (!$panduan->aktif || $panduan->visibilitas !== 'semua') {
            abort(404);
        }

        $dokumenList = Panduan::aktif()->untukSemua()->orderBy('urutan')->get();

        return view('kepala-sekolah.panduan.show', compact('panduan', 'dokumenList'));
    }

    public function download(Panduan $panduan)
    {
        if (!$panduan->aktif || $panduan->visibilitas !== 'semua') {
            abort(404);
        }

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
        if (!$panduan->aktif || $panduan->visibilitas !== 'semua') {
            abort(404);
        }

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
            return back()->with('error', 'Google Drive belum terhubung. Silakan hubungi administrator.');
        }

        $fileId = $driveService->uploadFile($tempPath, 'TU_Admin_Panduan');
        @unlink($tempPath);

        if ($fileId) {
            return back()->with('success', 'Dokumen berhasil diupload ke Google Drive!');
        }

        return back()->with('error', 'Gagal mengupload ke Google Drive. Silakan coba lagi.');
    }
}
