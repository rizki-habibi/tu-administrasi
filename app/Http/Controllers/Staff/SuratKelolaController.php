<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\EksporImporTrait;
use App\Models\Surat;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKelolaController extends Controller
{
    use EksporImporTrait;

    public function edit(Surat $surat)
    {
        abort_if($surat->dibuat_oleh !== auth()->id(), 403);
        return view('staf.surat.edit', compact('surat'));
    }

    public function update(Request $request, Surat $surat)
    {
        abort_if($surat->dibuat_oleh !== auth()->id(), 403);

        $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'kategori' => 'required|in:dinas,undangan,keterangan,keputusan,edaran,tugas,pemberitahuan,lainnya',
            'perihal' => 'required|string|max:255',
            'isi' => 'nullable|string',
            'tujuan' => 'nullable|string|max:255',
            'asal' => 'nullable|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'nullable|date',
            'sifat' => 'required|in:biasa,penting,segera,rahasia',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $data = $request->only(['jenis', 'kategori', 'perihal', 'isi', 'tujuan', 'asal', 'tanggal_surat', 'tanggal_terima', 'sifat', 'catatan']);

        if ($request->hasFile('file')) {
            if ($surat->path_file) {
                Storage::disk('public')->delete($surat->path_file);
            }
            $file = $request->file('file');
            $data['path_file'] = $file->store('surat', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        $surat->update($data);

        return redirect()->route('staf.surat.show', $surat)->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        abort_if($surat->dibuat_oleh !== auth()->id(), 403);

        if ($surat->path_file) {
            Storage::disk('public')->delete($surat->path_file);
        }

        $surat->delete();
        return redirect()->route('staf.surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function updateStatus(Request $request, Surat $surat)
    {
        abort_if($surat->dibuat_oleh !== auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:draft,dikirim,diterima,diarsipkan',
        ]);

        $surat->update(['status' => $request->status]);

        return back()->with('success', 'Status surat berhasil diperbarui.');
    }

    public function export()
    {
        $rows = Surat::where('dibuat_oleh', auth()->id())->latest()->get()->map(function ($r, $i) {
            return [
                $i + 1,
                $r->nomor_surat,
                ucfirst($r->jenis),
                ucfirst($r->kategori),
                $r->perihal,
                $r->tujuan ?? $r->asal ?? '-',
                ucfirst($r->sifat),
                ucfirst($r->status),
                $r->tanggal_surat?->format('d/m/Y'),
            ];
        });

        return $this->eksporCsv(
            'daftar_surat_' . now()->format('Ymd') . '.csv',
            ['No', 'Nomor Surat', 'Jenis', 'Kategori', 'Perihal', 'Tujuan/Asal', 'Sifat', 'Status', 'Tanggal'],
            $rows
        );
    }
}
