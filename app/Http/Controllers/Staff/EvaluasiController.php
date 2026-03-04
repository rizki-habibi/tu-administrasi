<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TeacherEvaluation;
use App\Models\P5Assessment;
use App\Models\StarAnalysis;
use App\Models\PhysicalEvidence;
use App\Models\LearningMethod;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    // PKG / BKD - view own evaluations
    public function pkgIndex()
    {
        $evaluations = TeacherEvaluation::where('pengguna_id', auth()->id())
            ->latest()->paginate(15);
        return view('staf.evaluasi.pkg', compact('evaluations'));
    }

    // P5 Assessments - view all
    public function p5Index()
    {
        $assessments = P5Assessment::latest()->paginate(15);
        return view('staf.evaluasi.p5', compact('assessments'));
    }

    // STAR Analysis - view/create own
    public function starIndex()
    {
        $analyses = StarAnalysis::where('dibuat_oleh', auth()->id())
            ->latest()->paginate(15);
        return view('staf.evaluasi.star', compact('analyses'));
    }

    public function starStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'situasi' => 'required|string',
            'tugas' => 'required|string',
            'aksi' => 'required|string',
            'hasil' => 'required|string',
        ]);

        StarAnalysis::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori ?? 'pembelajaran',
            'situasi' => $request->situasi,
            'tugas' => $request->tugas,
            'aksi' => $request->aksi,
            'hasil' => $request->hasil,
            'refleksi' => $request->reflection,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('staf.evaluasi.star')->with('success', 'Analisis STAR berhasil disimpan.');
    }

    // Bukti Fisik - view/upload own
    public function buktiFisikIndex()
    {
        $evidences = PhysicalEvidence::where('diunggah_oleh', auth()->id())
            ->latest()->paginate(15);
        return view('staf.evaluasi.bukti-fisik', compact('evidences'));
    }

    public function buktiFisikStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $data = [
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'terkait' => $request->standar,
            'diunggah_oleh' => auth()->id(),
        ];

        if ($request->hasFile('file')) {
            $data['path_file'] = $request->file('file')->store('bukti-fisik', 'public');
            $data['nama_file'] = $request->file('file')->getClientOriginalName();
            $data['tipe_file'] = $request->file('file')->getClientMimeType();
            $data['ukuran_file'] = $request->file('file')->getSize();
        }

        PhysicalEvidence::create($data);
        return redirect()->route('staf.evaluasi.bukti-fisik')->with('success', 'Bukti fisik berhasil diunggah.');
    }

    // Learning Methods - view/create
    public function learningIndex()
    {
        $methods = LearningMethod::where('dibuat_oleh', auth()->id())
            ->latest()->paginate(15);
        return view('staf.evaluasi.pembelajaran', compact('methods'));
    }

    public function learningStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        LearningMethod::create([
            'nama_metode' => $request->nama,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'mata_pelajaran' => $request->mata_pelajaran,
            'kelebihan' => $request->benefits,
            'kekurangan' => $request->challenges,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('staf.evaluasi.pembelajaran')->with('success', 'Model pembelajaran berhasil disimpan.');
    }
}
