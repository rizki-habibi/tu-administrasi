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
        $evaluations = TeacherEvaluation::where('user_id', auth()->id())
            ->latest()->paginate(15);
        return view('staff.evaluasi.pkg', compact('evaluations'));
    }

    // P5 Assessments - view all
    public function p5Index()
    {
        $assessments = P5Assessment::latest()->paginate(15);
        return view('staff.evaluasi.p5', compact('assessments'));
    }

    // STAR Analysis - view/create own
    public function starIndex()
    {
        $analyses = StarAnalysis::where('created_by', auth()->id())
            ->latest()->paginate(15);
        return view('staff.evaluasi.star', compact('analyses'));
    }

    public function starStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'situation' => 'required|string',
            'task' => 'required|string',
            'action' => 'required|string',
            'result' => 'required|string',
        ]);

        StarAnalysis::create([
            'judul' => $request->title,
            'kategori' => $request->kategori ?? 'pembelajaran',
            'situation' => $request->situation,
            'task' => $request->task,
            'action' => $request->action,
            'result' => $request->result,
            'refleksi' => $request->reflection,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('staff.evaluasi.star')->with('success', 'Analisis STAR berhasil disimpan.');
    }

    // Bukti Fisik - view/upload own
    public function buktiFisikIndex()
    {
        $evidences = PhysicalEvidence::where('uploaded_by', auth()->id())
            ->latest()->paginate(15);
        return view('staff.evaluasi.bukti-fisik', compact('evidences'));
    }

    public function buktiFisikStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $data = [
            'judul' => $request->title,
            'kategori' => $request->category,
            'deskripsi' => $request->description,
            'terkait' => $request->standar,
            'uploaded_by' => auth()->id(),
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('bukti-fisik', 'public');
            $data['file_name'] = $request->file('file')->getClientOriginalName();
            $data['file_type'] = $request->file('file')->getClientMimeType();
            $data['file_size'] = $request->file('file')->getSize();
        }

        PhysicalEvidence::create($data);
        return redirect()->route('staff.evaluasi.bukti-fisik')->with('success', 'Bukti fisik berhasil diunggah.');
    }

    // Learning Methods - view/create
    public function learningIndex()
    {
        $methods = LearningMethod::where('created_by', auth()->id())
            ->latest()->paginate(15);
        return view('staff.evaluasi.learning', compact('methods'));
    }

    public function learningStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'required|string',
        ]);

        LearningMethod::create([
            'nama_metode' => $request->name,
            'jenis' => $request->type,
            'deskripsi' => $request->description,
            'mata_pelajaran' => $request->subject,
            'kelebihan' => $request->benefits,
            'kekurangan' => $request->challenges,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('staff.evaluasi.learning')->with('success', 'Model pembelajaran berhasil disimpan.');
    }
}
