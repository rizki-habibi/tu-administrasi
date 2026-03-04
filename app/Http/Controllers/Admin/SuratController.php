<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('creator')->latest();

        if ($request->filled('jenis'))    $query->where('jenis', $request->jenis);
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('sifat'))    $query->where('sifat', $request->sifat);
        if ($request->filled('search'))   $query->where(function ($q) use ($request) {
            $q->where('nomor_surat', 'like', "%{$request->search}%")
              ->orWhere('perihal', 'like', "%{$request->search}%")
              ->orWhere('tujuan', 'like', "%{$request->search}%");
        });

        $surats = $query->paginate(15)->withQueryString();

        $stats = [
            'total'   => Surat::count(),
            'masuk'   => Surat::masuk()->count(),
            'keluar'  => Surat::keluar()->count(),
            'draft'   => Surat::where('status', 'draft')->count(),
            'diproses'=> Surat::where('status', 'diproses')->count(),
        ];

        return view('admin.surat.index', compact('surats', 'stats'));
    }

    public function create(Request $request)
    {
        $jenis = $request->get('jenis', 'keluar');
        return view('admin.surat.create', compact('jenis'));
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
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('surat', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        $surat = Surat::create($data);

        return redirect()->route('admin.surat.show', $surat)->with('success', 'Surat berhasil dibuat dengan nomor: ' . $surat->nomor_surat);
    }

    public function show(Surat $surat)
    {
        $surat->load('creator', 'approver');
        return view('admin.surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        return view('admin.surat.edit', compact('surat'));
    }

    public function update(Request $request, Surat $surat)
    {
        $request->validate([
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

        $data = $request->only(['kategori', 'perihal', 'isi', 'tujuan', 'asal', 'tanggal_surat', 'tanggal_terima', 'sifat', 'catatan']);

        if ($request->hasFile('file')) {
            if ($surat->path_file) Storage::disk('public')->delete($surat->path_file);
            $file = $request->file('file');
            $data['path_file'] = $file->store('surat', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        $surat->update($data);

        return redirect()->route('admin.surat.show', $surat)->with('success', 'Surat berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Surat $surat)
    {
        $request->validate([
            'status' => 'required|in:draft,diproses,dikirim,diterima,diarsipkan',
        ]);

        $surat->update([
            'status' => $request->status,
            'disetujui_oleh' => in_array($request->status, ['dikirim', 'diterima']) ? auth()->id() : $surat->disetujui_oleh,
        ]);

        $statusLabel = ucfirst($request->status);
        return back()->with('success', "Status surat diubah menjadi: {$statusLabel}");
    }

    public function destroy(Surat $surat)
    {
        if ($surat->path_file) Storage::disk('public')->delete($surat->path_file);
        $surat->delete();

        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil dihapus.');
    }
}
