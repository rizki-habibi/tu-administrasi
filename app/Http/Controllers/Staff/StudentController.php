<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRecord::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('nis', 'like', "%{$request->search}%")
                  ->orWhere('nisn', 'like', "%{$request->search}%");
            });
        }
        if ($request->class) {
            $query->where('class', $request->class);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('class')->orderBy('name')->paginate(20);
        $kelasList = StudentRecord::select('class')->distinct()->orderBy('class')->pluck('class');

        return view('staff.kesiswaan.index', compact('students', 'kelasList'));
    }

    public function show(StudentRecord $kesiswaan)
    {
        $kesiswaan->load(['achievements', 'violations']);
        return view('staff.kesiswaan.show', compact('kesiswaan'));
    }
}
