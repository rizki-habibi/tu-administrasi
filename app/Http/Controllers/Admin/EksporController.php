<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\Report;
use App\Models\Surat;
use App\Models\Event;
use App\Models\LeaveRequest;

class EksporController extends Controller
{
    public function index()
    {
        $stats = [
            'staff' => User::whereIn('peran', User::STAFF_ROLES)->count(),
            'kehadiran' => Attendance::whereMonth('tanggal', now()->month)->count(),
            'dokumen' => Document::count(),
            'laporan' => Report::count(),
            'surat' => Surat::count(),
            'agenda' => Event::count(),
            'izin' => LeaveRequest::count(),
        ];

        return view('admin.ekspor.index', compact('stats'));
    }
}
