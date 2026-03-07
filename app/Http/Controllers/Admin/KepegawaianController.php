<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenKepegawaian;
use App\Models\Pengguna;
use App\Models\RiwayatJabatan;
use App\Models\RiwayatPangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KepegawaianController extends Controller
{
    // ─── RIWAYAT JABATAN ───
    public function jabatanIndex(Request $request)
    {
        $query = RiwayatJabatan::with('pengguna');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_jabatan', 'like', "%{$s}%")
                  ->orWhere('nomor_sk', 'like', "%{$s}%")
                  ->orWhereHas('pengguna', fn ($q2) => $q2->where('nama', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('pegawai')) {
            $query->where('pengguna_id', $request->pegawai);
        }

        $riwayat = $query->latest('tmt_jabatan')->paginate(15);
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();

        return view('admin.kepegawaian.jabatan.index', compact('riwayat', 'pegawaiList'));
    }

    public function jabatanCreate(Request $request)
    {
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();
        $pegawaiId = $request->pegawai;
        return view('admin.kepegawaian.jabatan.create', compact('pegawaiList', 'pegawaiId'));
    }

    public function jabatanStore(Request $request)
    {
        $request->validate([
            'pengguna_id'     => 'required|exists:pengguna,id',
            'nama_jabatan'    => 'required|string|max:255',
            'unit_kerja'      => 'nullable|string|max:255',
            'tmt_jabatan'     => 'required|date',
            'tmt_selesai'     => 'nullable|date|after_or_equal:tmt_jabatan',
            'nomor_sk'        => 'nullable|string|max:255',
            'tanggal_sk'      => 'nullable|date',
            'pejabat_penetap' => 'nullable|string|max:255',
            'file_sk'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan'      => 'nullable|string',
        ]);

        $data = $request->except('file_sk');

        if ($request->hasFile('file_sk')) {
            $data['file_sk'] = $request->file('file_sk')->store('kepegawaian/jabatan', 'public');
        }

        RiwayatJabatan::create($data);

        return redirect()->route('admin.kepegawaian.jabatan.index')
            ->with('success', 'Riwayat jabatan berhasil ditambahkan.');
    }

    public function jabatanShow(RiwayatJabatan $jabatan)
    {
        $jabatan->load('pengguna');
        return view('admin.kepegawaian.jabatan.show', compact('jabatan'));
    }

    public function jabatanEdit(RiwayatJabatan $jabatan)
    {
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();
        return view('admin.kepegawaian.jabatan.edit', compact('jabatan', 'pegawaiList'));
    }

    public function jabatanUpdate(Request $request, RiwayatJabatan $jabatan)
    {
        $request->validate([
            'pengguna_id'     => 'required|exists:pengguna,id',
            'nama_jabatan'    => 'required|string|max:255',
            'unit_kerja'      => 'nullable|string|max:255',
            'tmt_jabatan'     => 'required|date',
            'tmt_selesai'     => 'nullable|date|after_or_equal:tmt_jabatan',
            'nomor_sk'        => 'nullable|string|max:255',
            'tanggal_sk'      => 'nullable|date',
            'pejabat_penetap' => 'nullable|string|max:255',
            'file_sk'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan'      => 'nullable|string',
        ]);

        $data = $request->except('file_sk');

        if ($request->hasFile('file_sk')) {
            if ($jabatan->file_sk) {
                Storage::disk('public')->delete($jabatan->file_sk);
            }
            $data['file_sk'] = $request->file('file_sk')->store('kepegawaian/jabatan', 'public');
        }

        $jabatan->update($data);

        return redirect()->route('admin.kepegawaian.jabatan.index')
            ->with('success', 'Riwayat jabatan berhasil diperbarui.');
    }

    public function jabatanDestroy(RiwayatJabatan $jabatan)
    {
        if ($jabatan->file_sk) {
            Storage::disk('public')->delete($jabatan->file_sk);
        }
        $jabatan->delete();

        return redirect()->route('admin.kepegawaian.jabatan.index')
            ->with('success', 'Riwayat jabatan berhasil dihapus.');
    }

    // ─── RIWAYAT PANGKAT ───
    public function pangkatIndex(Request $request)
    {
        $query = RiwayatPangkat::with('pengguna');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('pangkat', 'like', "%{$s}%")
                  ->orWhere('golongan', 'like', "%{$s}%")
                  ->orWhere('nomor_sk', 'like', "%{$s}%")
                  ->orWhereHas('pengguna', fn ($q2) => $q2->where('nama', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('pegawai')) {
            $query->where('pengguna_id', $request->pegawai);
        }

        $riwayat = $query->latest('tmt_pangkat')->paginate(15);
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();

        return view('admin.kepegawaian.pangkat.index', compact('riwayat', 'pegawaiList'));
    }

    public function pangkatCreate(Request $request)
    {
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();
        $pegawaiId = $request->pegawai;
        return view('admin.kepegawaian.pangkat.create', compact('pegawaiList', 'pegawaiId'));
    }

    public function pangkatStore(Request $request)
    {
        $request->validate([
            'pengguna_id'     => 'required|exists:pengguna,id',
            'pangkat'         => 'required|string|max:255',
            'golongan'        => 'required|string|max:50',
            'tmt_pangkat'     => 'required|date',
            'nomor_sk'        => 'nullable|string|max:255',
            'tanggal_sk'      => 'nullable|date',
            'pejabat_penetap' => 'nullable|string|max:255',
            'jenis_kenaikan'  => 'nullable|string|max:100',
            'file_sk'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan'      => 'nullable|string',
        ]);

        $data = $request->except('file_sk');

        if ($request->hasFile('file_sk')) {
            $data['file_sk'] = $request->file('file_sk')->store('kepegawaian/pangkat', 'public');
        }

        $riwayat = RiwayatPangkat::create($data);

        // Update pangkat & golongan di tabel pengguna
        $pengguna = Pengguna::find($request->pengguna_id);
        $pengguna->update([
            'pangkat'   => $request->pangkat,
            'golongan'  => $request->golongan,
        ]);

        return redirect()->route('admin.kepegawaian.pangkat.index')
            ->with('success', 'Riwayat pangkat berhasil ditambahkan.');
    }

    public function pangkatShow(RiwayatPangkat $pangkat)
    {
        $pangkat->load('pengguna');
        return view('admin.kepegawaian.pangkat.show', compact('pangkat'));
    }

    public function pangkatEdit(RiwayatPangkat $pangkat)
    {
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();
        return view('admin.kepegawaian.pangkat.edit', compact('pangkat', 'pegawaiList'));
    }

    public function pangkatUpdate(Request $request, RiwayatPangkat $pangkat)
    {
        $request->validate([
            'pengguna_id'     => 'required|exists:pengguna,id',
            'pangkat'         => 'required|string|max:255',
            'golongan'        => 'required|string|max:50',
            'tmt_pangkat'     => 'required|date',
            'nomor_sk'        => 'nullable|string|max:255',
            'tanggal_sk'      => 'nullable|date',
            'pejabat_penetap' => 'nullable|string|max:255',
            'jenis_kenaikan'  => 'nullable|string|max:100',
            'file_sk'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan'      => 'nullable|string',
        ]);

        $data = $request->except('file_sk');

        if ($request->hasFile('file_sk')) {
            if ($pangkat->file_sk) {
                Storage::disk('public')->delete($pangkat->file_sk);
            }
            $data['file_sk'] = $request->file('file_sk')->store('kepegawaian/pangkat', 'public');
        }

        $pangkat->update($data);

        return redirect()->route('admin.kepegawaian.pangkat.index')
            ->with('success', 'Riwayat pangkat berhasil diperbarui.');
    }

    public function pangkatDestroy(RiwayatPangkat $pangkat)
    {
        if ($pangkat->file_sk) {
            Storage::disk('public')->delete($pangkat->file_sk);
        }
        $pangkat->delete();

        return redirect()->route('admin.kepegawaian.pangkat.index')
            ->with('success', 'Riwayat pangkat berhasil dihapus.');
    }

    // ─── DOKUMEN KEPEGAWAIAN ───
    public function dokumenIndex(Request $request)
    {
        $query = DokumenKepegawaian::with('pengguna');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('nomor_dokumen', 'like', "%{$s}%")
                  ->orWhereHas('pengguna', fn ($q2) => $q2->where('nama', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('pegawai')) {
            $query->where('pengguna_id', $request->pegawai);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $dokumen = $query->latest()->paginate(15);
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();
        $kategoriList = DokumenKepegawaian::KATEGORI;

        return view('admin.kepegawaian.dokumen.index', compact('dokumen', 'pegawaiList', 'kategoriList'));
    }

    public function dokumenCreate(Request $request)
    {
        $pegawaiList = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->orderBy('nama')->get();
        $kategoriList = DokumenKepegawaian::KATEGORI;
        $pegawaiId = $request->pegawai;
        return view('admin.kepegawaian.dokumen.create', compact('pegawaiList', 'kategoriList', 'pegawaiId'));
    }

    public function dokumenStore(Request $request)
    {
        $request->validate([
            'pengguna_id'     => 'required|exists:pengguna,id',
            'judul'           => 'required|string|max:255',
            'kategori'        => 'required|string',
            'nomor_dokumen'   => 'nullable|string|max:255',
            'tanggal_dokumen' => 'nullable|date',
            'file_path'       => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'keterangan'      => 'nullable|string',
        ]);

        $file = $request->file('file_path');
        $data = $request->except('file_path');
        $data['file_path'] = $file->store('kepegawaian/dokumen', 'public');
        $data['file_type'] = $file->getClientOriginalExtension();
        $data['file_size'] = $file->getSize();

        DokumenKepegawaian::create($data);

        return redirect()->route('admin.kepegawaian.dokumen.index')
            ->with('success', 'Dokumen kepegawaian berhasil diunggah.');
    }

    public function dokumenShow(DokumenKepegawaian $dokumen)
    {
        $dokumen->load('pengguna');
        return view('admin.kepegawaian.dokumen.show', compact('dokumen'));
    }

    public function dokumenDestroy(DokumenKepegawaian $dokumen)
    {
        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();

        return redirect()->route('admin.kepegawaian.dokumen.index')
            ->with('success', 'Dokumen kepegawaian berhasil dihapus.');
    }

    // ─── LAPORAN KEPEGAWAIAN ───
    public function laporanIndex()
    {
        $totalPegawai = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->count();
        $pegawaiAktif = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->count();

        $byJenisPegawai = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)
            ->whereNotNull('jenis_pegawai')
            ->selectRaw('jenis_pegawai, count(*) as total')
            ->groupBy('jenis_pegawai')
            ->pluck('total', 'jenis_pegawai');

        $byGolongan = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)
            ->whereNotNull('golongan')
            ->selectRaw('golongan, count(*) as total')
            ->groupBy('golongan')
            ->orderBy('golongan')
            ->pluck('total', 'golongan');

        $byPendidikan = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)
            ->whereNotNull('pendidikan_terakhir')
            ->selectRaw('pendidikan_terakhir, count(*) as total')
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir');

        return view('admin.kepegawaian.laporan.index', compact(
            'totalPegawai', 'pegawaiAktif', 'byJenisPegawai', 'byGolongan', 'byPendidikan'
        ));
    }
}
