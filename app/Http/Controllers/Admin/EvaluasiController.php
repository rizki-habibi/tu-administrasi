<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherEvaluation;
use App\Models\P5Assessment;
use App\Models\StarAnalysis;
use App\Models\PhysicalEvidence;
use App\Models\LearningMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvaluasiController extends Controller
{
    // === PKG / BKD ===
    public function pkgIndex(Request $request)
    {
        $query = TeacherEvaluation::with('user', 'evaluator')->latest();
        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);
        if ($request->filled('search')) $query->whereHas('user', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));

        $evaluations = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.pkg', compact('evaluations'));
    }

    public function pkgCreate()
    {
        $staffs = User::whereIn('peran', User::STAFF_ROLES)->where('aktif', true)->get();
        return view('admin.evaluasi.pkg-create', compact('staffs'));
    }

    public function pkgStore(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'periode' => 'required|string',
            'jenis' => 'required|in:pkg,bkd,skp',
            'nilai' => 'nullable|numeric|min:0|max:100',
            'predikat' => 'nullable|in:amat_baik,baik,cukup,kurang',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dievaluasi_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('evaluasi', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        TeacherEvaluation::create($data);
        return redirect()->route('admin.evaluasi.pkg')->with('success', 'Evaluasi berhasil ditambahkan.');
    }

    // === P5 Asesmen ===
    public function p5Index(Request $request)
    {
        $query = P5Assessment::with('creator')->latest();
        if ($request->filled('dimensi')) $query->where('dimensi', $request->dimensi);
        if ($request->filled('kelas')) $query->where('kelas', $request->kelas);

        $assessments = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.p5', compact('assessments'));
    }

    public function p5Create()
    {
        return view('admin.evaluasi.p5-create');
    }

    public function p5Store(Request $request)
    {
        $request->validate([
            'tema' => 'required|string|max:255',
            'judul_projek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas' => 'required|string',
            'fase' => 'required|in:E,F',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'dimensi' => 'required|in:beriman,mandiri,gotong_royong,berkebinekaan,bernalar_kritis,kreatif',
            'target_capaian' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('p5', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        P5Assessment::create($data);
        return redirect()->route('admin.evaluasi.p5')->with('success', 'Asesmen P5 berhasil ditambahkan.');
    }

    // === STAR Analysis ===
    public function starIndex(Request $request)
    {
        $query = StarAnalysis::with('creator')->latest();
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);

        $analyses = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.star', compact('analyses'));
    }

    public function starCreate()
    {
        return view('admin.evaluasi.star-create');
    }

    public function starStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:pembelajaran,administrasi,manajemen',
            'situasi' => 'required|string',
            'tugas' => 'required|string',
            'aksi' => 'required|string',
            'hasil' => 'required|string',
            'refleksi' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $data['path_file'] = $request->file('file')->store('star', 'public');
        }

        StarAnalysis::create($data);
        return redirect()->route('admin.evaluasi.star')->with('success', 'Analisis STAR berhasil ditambahkan.');
    }

    // === Bukti Fisik ===
    public function buktiFisikIndex(Request $request)
    {
        $query = PhysicalEvidence::with('uploader')->latest();
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('terkait')) $query->where('terkait', $request->terkait);

        $evidences = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.bukti-fisik', compact('evidences'));
    }

    public function buktiFisikStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:pembelajaran,administrasi,kegiatan,pengembangan_diri',
            'deskripsi' => 'nullable|string',
            'terkait' => 'nullable|in:pkg,bkd,akreditasi,p5',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        PhysicalEvidence::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'terkait' => $request->terkait,
            'path_file' => $file->store('bukti-fisik', 'public'),
            'nama_file' => $file->getClientOriginalName(),
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize(),
            'diunggah_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.evaluasi.bukti-fisik')->with('success', 'Bukti fisik berhasil diupload.');
    }

    public function buktiFisikDestroy(PhysicalEvidence $evidence)
    {
        Storage::disk('public')->delete($evidence->path_file);
        $evidence->delete();
        return redirect()->back()->with('success', 'Bukti fisik berhasil dihapus.');
    }

    // === Model Pembelajaran / Metode Teknologi ===
    public function learningIndex(Request $request)
    {
        $query = LearningMethod::with('creator')->latest();
        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);

        $methods = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.pembelajaran', compact('methods'));
    }

    public function learningCreate()
    {
        return view('admin.evaluasi.pembelajaran-create');
    }

    public function learningStore(Request $request)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:255',
            'jenis' => 'required|in:model_pembelajaran,teknologi_pembelajaran,media_pembelajaran',
            'deskripsi' => 'required|string',
            'langkah_pelaksanaan' => 'nullable|string',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'hasil' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('learning', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        LearningMethod::create($data);
        return redirect()->route('admin.evaluasi.pembelajaran')->with('success', 'Metode pembelajaran berhasil ditambahkan.');
    }
}
