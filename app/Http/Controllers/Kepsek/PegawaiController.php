<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    use EksporImporTrait;

    public function index(Request $request)
    {
        $query = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true);

        if ($request->filled('peran')) {
            $query->where('peran', $request->peran);
        }
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $staffs = $query->orderBy('nama')->paginate(20);
        return view('kepala-sekolah.pegawai.index', compact('staffs'));
    }

    public function show(Pengguna $staff)
    {
        $staff->load(['attendances' => fn($q) => $q->latest()->take(30), 'leaveRequests' => fn($q) => $q->latest()->take(10), 'skp' => fn($q) => $q->latest()]);
        return view('kepala-sekolah.pegawai.show', compact('staff'));
    }

    public function export()
    {
        $rows = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)
            ->orderBy('nama')->get()->map(function ($s, $i) {
                return [
                    $i + 1,
                    $s->nama,
                    $s->email,
                    ucfirst(str_replace('_', ' ', $s->peran)),
                    $s->jabatan ?? '-',
                    $s->telepon ?? '-',
                ];
            });

        return $this->eksporCsv(
            'data_pegawai_' . now()->format('Ymd') . '.csv',
            ['No', 'Nama', 'Email', 'Peran', 'Jabatan', 'Telepon'],
            $rows
        );
    }
}
