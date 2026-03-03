<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\CurriculumDocument;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $query = CurriculumDocument::where('status', 'final');

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $documents = $query->latest()->paginate(15);
        return view('staff.kurikulum.index', compact('documents'));
    }

    public function show(CurriculumDocument $kurikulum)
    {
        return view('staff.kurikulum.show', compact('kurikulum'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $data = $request->only(['title', 'type', 'academic_year', 'semester', 'subject', 'class_level', 'description']);
        $data['uploaded_by'] = auth()->id();
        $data['status'] = 'draft';

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('kurikulum', 'public');
            $data['file_name'] = $request->file('file')->getClientOriginalName();
            $data['file_size'] = $request->file('file')->getSize();
        }

        CurriculumDocument::create($data);
        return redirect()->route('staff.kurikulum.index')->with('success', 'Dokumen kurikulum berhasil diunggah.');
    }
}
