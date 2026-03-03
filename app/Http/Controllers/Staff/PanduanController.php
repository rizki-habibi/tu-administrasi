<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PanduanController extends Controller
{
    public function index()
    {
        $mdPath = base_path('docs/PANDUAN-PENGGUNAAN.md');
        $content = File::exists($mdPath) ? File::get($mdPath) : '# Panduan belum tersedia';

        return view('staff.panduan.index', compact('content'));
    }
}
