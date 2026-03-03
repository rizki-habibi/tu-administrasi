<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('creator')->latest();

        if ($request->filled('jenis'))  $query->where('jenis', $request->jenis);
        if ($request->filled('search')) $query->where(function ($q) use ($request) {
            $q->where('nomor_surat', 'like', "%{$request->search}%")
              ->orWhere('perihal', 'like', "%{$request->search}%");
        });

        // Staff can see all non-draft letters, plus their own drafts
        $query->where(function ($q) {
            $q->where('status', '!=', 'draft')
              ->orWhere('created_by', auth()->id());
        });

        $surats = $query->paginate(15)->withQueryString();

        return view('staff.surat.index', compact('surats'));
    }

    public function create(Request $request)
    {
        $jenis = $request->get('jenis', 'keluar');
        return view('staff.surat.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'          => 'required|in:masuk,keluar',
            'kategori'       => 'required|in:dinas,undangan,keterangan,keputusan,edaran,tugas,pemberitahuan,lainnya',
            'perihal'        => 'required|string|max:255',
            'isi'            => 'nullable|string',
            'tujuan'         => 'nullable|string|max:255',
            'asal'           => 'nullable|string|max:255',
            'tanggal_surat'  => 'required|date',
            'tanggal_terima' => 'nullable|date',
            'sifat'          => 'required|in:biasa,penting,segera,rahasia',
            'catatan'        => 'nullable|string',
            'file'           => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $data = $request->only(['jenis', 'kategori', 'perihal', 'isi', 'tujuan', 'asal', 'tanggal_surat', 'tanggal_terima', 'sifat', 'catatan']);
        $data['nomor_surat'] = Surat::generateNomor($request->jenis, $request->kategori);
        $data['status'] = 'draft';
        $data['created_by'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file_path'] = $file->store('surat', 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        $surat = Surat::create($data);

        return redirect()->route('staff.surat.show', $surat)->with('success', 'Surat berhasil dibuat. Menunggu persetujuan admin.');
    }

    public function show(Surat $surat)
    {
        $surat->load('creator', 'approver');
        return view('staff.surat.show', compact('surat'));
    }
}
