<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenAkreditasi;
use App\Models\EvaluasiSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkreditasiController extends Controller
{
    public function index(Request $request)
    {
        $query = DokumenAkreditasi::with('uploader')->latest();

        if ($request->filled('standar')) $query->where('standar', $request->standar);
        if ($request->filled('status')) $query->where('status', $request->status);

        $documents = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => DokumenAkreditasi::count(),
            'lengkap' => DokumenAkreditasi::where('status', 'lengkap')->count(),
            'belum_lengkap' => DokumenAkreditasi::where('status', 'belum_lengkap')->count(),
            'diverifikasi' => DokumenAkreditasi::where('status', 'diverifikasi')->count(),
        ];

        return view('admin.akreditasi.index', compact('documents', 'stats'));
    }

    public function create()
    {
        return view('admin.akreditasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'standar' => 'required|in:standar_isi,standar_proses,standar_kompetensi_lulusan,standar_pendidik,standar_sarpras,standar_pengelolaan,standar_pembiayaan,standar_penilaian',
            'komponen' => 'required|string|max:255',
            'indikator' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['diunggah_oleh'] = auth()->id();
        $data['status'] = 'belum_lengkap';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('akreditasi', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['status'] = 'lengkap';
        }

        DokumenAkreditasi::create($data);
        return redirect()->route('admin.akreditasi.index')->with('success', 'Dokumen akreditasi berhasil ditambahkan.');
    }

    public function show(DokumenAkreditasi $akreditasi)
    {
        $akreditasi->load('uploader');
        return view('admin.akreditasi.show', compact('akreditasi'));
    }

    public function destroy(DokumenAkreditasi $akreditasi)
    {
        if ($akreditasi->path_file) Storage::disk('public')->delete($akreditasi->path_file);
        $akreditasi->delete();
        return redirect()->route('admin.akreditasi.index')->with('success', 'Dokumen akreditasi berhasil dihapus.');
    }

    // EDS
    public function edsIndex()
    {
        $evaluations = EvaluasiSekolah::with('creator')->latest()->paginate(15);
        return view('admin.akreditasi.eds', compact('evaluations'));
    }

    public function edsStore(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string',
            'aspek' => 'required|string|max:255',
            'kondisi_saat_ini' => 'nullable|string',
            'target' => 'nullable|string',
            'program_tindak_lanjut' => 'nullable|string',
        ]);

        EvaluasiSekolah::create(array_merge($request->all(), ['dibuat_oleh' => auth()->id()]));
        return redirect()->route('admin.akreditasi.eds')->with('success', 'Evaluasi diri sekolah berhasil ditambahkan.');
    }
}
