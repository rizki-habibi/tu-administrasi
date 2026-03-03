<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\StudentAchievement;
use App\Models\StudentViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRecord::latest();

        if ($request->filled('class')) $query->where('class', $request->class);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => StudentRecord::count(),
            'aktif' => StudentRecord::where('status', 'aktif')->count(),
            'laki' => StudentRecord::where('gender', 'L')->where('status', 'aktif')->count(),
            'perempuan' => StudentRecord::where('gender', 'P')->where('status', 'aktif')->count(),
        ];

        return view('admin.kesiswaan.index', compact('students', 'stats'));
    }

    public function create()
    {
        return view('admin.kesiswaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:student_records,nis',
            'nisn' => 'nullable|string|unique:student_records,nisn',
            'name' => 'required|string|max:255',
            'class' => 'required|string',
            'academic_year' => 'required|string',
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'religion' => 'nullable|string',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'status' => 'required|in:aktif,mutasi_masuk,mutasi_keluar,lulus,do',
        ]);

        $data = $request->except('photo');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('siswa-photos', 'public');
        }

        StudentRecord::create($data);
        return redirect()->route('admin.kesiswaan.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(StudentRecord $kesiswaan)
    {
        $kesiswaan->load(['achievements', 'violations.reporter']);
        return view('admin.kesiswaan.show', compact('kesiswaan'));
    }

    public function edit(StudentRecord $kesiswaan)
    {
        return view('admin.kesiswaan.edit', compact('kesiswaan'));
    }

    public function update(Request $request, StudentRecord $kesiswaan)
    {
        $request->validate([
            'nis' => 'required|string|unique:student_records,nis,' . $kesiswaan->id,
            'nisn' => 'nullable|string|unique:student_records,nisn,' . $kesiswaan->id,
            'name' => 'required|string|max:255',
            'class' => 'required|string',
            'academic_year' => 'required|string',
            'gender' => 'required|in:L,P',
            'status' => 'required|in:aktif,mutasi_masuk,mutasi_keluar,lulus,do',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($kesiswaan->photo) Storage::disk('public')->delete($kesiswaan->photo);
            $data['photo'] = $request->file('photo')->store('siswa-photos', 'public');
        }

        $kesiswaan->update($data);
        return redirect()->route('admin.kesiswaan.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(StudentRecord $kesiswaan)
    {
        if ($kesiswaan->photo) Storage::disk('public')->delete($kesiswaan->photo);
        $kesiswaan->delete();
        return redirect()->route('admin.kesiswaan.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
