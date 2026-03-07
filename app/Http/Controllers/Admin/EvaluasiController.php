<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluasiGuru;
use App\Models\PenilaianP5;
use App\Models\AnalisisStar;
use App\Models\BuktiFisik;
use App\Models\MetodePembelajaran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvaluasiController extends Controller
{
    // === PKG / BKD ===
    public function pkgIndex(Request $request)
    {
        $query = EvaluasiGuru::with('user', 'evaluator')->latest();
        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);
        if ($request->filled('search')) $query->whereHas('user', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));

        $evaluations = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.pkg', compact('evaluations'));
    }

    public function pkgCreate()
    {
        $staffs = Pengguna::whereIn('peran', Pengguna::STAFF_ROLES)->where('aktif', true)->get();
        return view('admin.evaluasi.pkg-create', compact('staffs'));
    }

    public function pkgStore(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'periode' => 'required|string',
            'jenis' => 'required|in:pkg,bkd,skp',
            'nilai' => 'nullable|numeric|min:0|max:100',
            'predikat' => 'nullable|in:amat_baik,baik,cukup,kurang',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dievaluasi_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('evaluasi', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        EvaluasiGuru::create($data);
        return redirect()->route('admin.evaluasi.pkg')->with('success', 'Evaluasi berhasil ditambahkan.');
    }

    // === P5 Asesmen ===
    public function p5Index(Request $request)
    {
        $query = PenilaianP5::with('creator')->latest();
        if ($request->filled('dimensi')) $query->where('dimensi', $request->dimensi);
        if ($request->filled('kelas')) $query->where('kelas', $request->kelas);

        $assessments = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.p5', compact('assessments'));
    }

    public function p5Create()
    {
        return view('admin.evaluasi.p5-create');
    }

    public function p5Store(Request $request)
    {
        $request->validate([
            'tema' => 'required|string|max:255',
            'judul_projek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas' => 'required|string',
            'fase' => 'required|in:E,F',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'dimensi' => 'required|in:beriman,mandiri,gotong_royong,berkebinekaan,bernalar_kritis,kreatif',
            'target_capaian' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('p5', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        PenilaianP5::create($data);
        return redirect()->route('admin.evaluasi.p5')->with('success', 'Asesmen P5 berhasil ditambahkan.');
    }

    // === STAR Analysis ===
    public function starIndex(Request $request)
    {
        $query = AnalisisStar::with('creator')->latest();
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);

        $analyses = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.star', compact('analyses'));
    }

    public function starCreate()
    {
        return view('admin.evaluasi.star-create');
    }

    public function starStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:pembelajaran,administrasi,manajemen',
            'situasi' => 'required|string',
            'tugas' => 'required|string',
            'aksi' => 'required|string',
            'hasil' => 'required|string',
            'refleksi' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $data['path_file'] = $request->file('file')->store('star', 'public');
        }

        AnalisisStar::create($data);
        return redirect()->route('admin.evaluasi.star')->with('success', 'Analisis STAR berhasil ditambahkan.');
    }

    public function starShow(AnalisisStar $star)
    {
        $star->load('creator');
        return view('admin.evaluasi.star-show', compact('star'));
    }

    public function starEdit(AnalisisStar $star)
    {
        return view('admin.evaluasi.star-edit', compact('star'));
    }

    public function starUpdate(Request $request, AnalisisStar $star)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:pembelajaran,administrasi,manajemen',
            'situasi' => 'required|string',
            'tugas' => 'required|string',
            'aksi' => 'required|string',
            'hasil' => 'required|string',
            'refleksi' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');

        if ($request->hasFile('file')) {
            if ($star->path_file) {
                Storage::disk('public')->delete($star->path_file);
            }
            $data['path_file'] = $request->file('file')->store('star', 'public');
        }

        $star->update($data);
        return redirect()->route('admin.evaluasi.star')->with('success', 'Analisis STAR berhasil diperbarui.');
    }

    public function starDestroy(AnalisisStar $star)
    {
        $star->delete();
        return redirect()->route('admin.evaluasi.star')->with('success', 'Analisis STAR dipindahkan ke sampah. Dapat dipulihkan dalam 30 hari.');
    }

    public function starTrash(Request $request)
    {
        $analyses = AnalisisStar::onlyTrashed()->with('creator')->latest('deleted_at')->paginate(15);
        return view('admin.evaluasi.star-trash', compact('analyses'));
    }

    public function starRestore($id)
    {
        AnalisisStar::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.evaluasi.star.trash')->with('success', 'Analisis STAR berhasil dipulihkan.');
    }

    public function starForceDelete($id)
    {
        $star = AnalisisStar::onlyTrashed()->findOrFail($id);
        if ($star->path_file) {
            Storage::disk('public')->delete($star->path_file);
        }
        $star->forceDelete();
        return redirect()->route('admin.evaluasi.star.trash')->with('success', 'Analisis STAR dihapus permanen.');
    }

    // === Bukti Fisik ===
    public function buktiFisikIndex(Request $request)
    {
        $query = BuktiFisik::with('uploader')->latest();
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('terkait')) $query->where('terkait', $request->terkait);

        $evidences = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.bukti-fisik', compact('evidences'));
    }

    public function buktiFisikStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:pembelajaran,administrasi,kegiatan,pengembangan_diri',
            'deskripsi' => 'nullable|string',
            'terkait' => 'nullable|in:pkg,bkd,akreditasi,p5',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        BuktiFisik::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'terkait' => $request->terkait,
            'path_file' => $file->store('bukti-fisik', 'public'),
            'nama_file' => $file->getClientOriginalName(),
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize(),
            'diunggah_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.evaluasi.bukti-fisik')->with('success', 'Bukti fisik berhasil diupload.');
    }

    public function buktiFisikDestroy(BuktiFisik $evidence)
    {
        Storage::disk('public')->delete($evidence->path_file);
        $evidence->delete();
        return redirect()->back()->with('success', 'Bukti fisik berhasil dihapus.');
    }

    // === Model Pembelajaran / Metode Teknologi ===
    public function learningIndex(Request $request)
    {
        $query = MetodePembelajaran::with('creator')->latest();
        if ($request->filled('jenis')) $query->where('jenis', $request->jenis);

        $methods = $query->paginate(15)->withQueryString();
        return view('admin.evaluasi.pembelajaran', compact('methods'));
    }

    public function learningCreate()
    {
        return view('admin.evaluasi.pembelajaran-create');
    }

    public function learningStore(Request $request)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:255',
            'jenis' => 'required|in:model_pembelajaran,teknologi_pembelajaran,media_pembelajaran',
            'deskripsi' => 'required|string',
            'langkah_pelaksanaan' => 'nullable|string',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'hasil' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['path_file'] = $file->store('learning', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        MetodePembelajaran::create($data);
        return redirect()->route('admin.evaluasi.pembelajaran')->with('success', 'Metode pembelajaran berhasil ditambahkan.');
    }

    public function learningShow(MetodePembelajaran $method)
    {
        $method->load('creator');
        return view('admin.evaluasi.pembelajaran-show', compact('method'));
    }

    public function learningEdit(MetodePembelajaran $method)
    {
        return view('admin.evaluasi.pembelajaran-edit', compact('method'));
    }

    public function learningUpdate(Request $request, MetodePembelajaran $method)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:255',
            'jenis' => 'required|in:model_pembelajaran,teknologi_pembelajaran,media_pembelajaran',
            'deskripsi' => 'required|string',
            'langkah_pelaksanaan' => 'nullable|string',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'hasil' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('file');

        if ($request->hasFile('file')) {
            if ($method->path_file) {
                Storage::disk('public')->delete($method->path_file);
            }
            $file = $request->file('file');
            $data['path_file'] = $file->store('learning', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
        }

        $method->update($data);
        return redirect()->route('admin.evaluasi.pembelajaran')->with('success', 'Metode pembelajaran berhasil diperbarui.');
    }

    public function learningDestroy(MetodePembelajaran $method)
    {
        if ($method->path_file) {
            Storage::disk('public')->delete($method->path_file);
        }
        $method->delete();
        return redirect()->route('admin.evaluasi.pembelajaran')->with('success', 'Metode pembelajaran berhasil dihapus.');
    }
}
