<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\PenyimpananCloud;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{
    /**
     * Database Inspector — lihat semua tabel, jumlah record, ukuran.
     */
    public function index()
    {
        $dbName = config('database.connections.mysql.database');
        $tables = DB::select('SELECT TABLE_NAME as nama, TABLE_ROWS as jumlah_baris, 
            ROUND(DATA_LENGTH / 1024, 2) as ukuran_data_kb, 
            ROUND(INDEX_LENGTH / 1024, 2) as ukuran_index_kb,
            ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024, 2) as total_kb,
            ENGINE as engine, TABLE_COLLATION as collation,
            CREATE_TIME as dibuat, UPDATE_TIME as diperbarui
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME', [$dbName]);

        $totalSize = collect($tables)->sum('total_kb');
        $totalRows = collect($tables)->sum('jumlah_baris');

        return view('admin.database.index', compact('tables', 'dbName', 'totalSize', 'totalRows'));
    }

    /**
     * Lihat detail kolom dari satu tabel.
     */
    public function showTable(string $table)
    {
        $dbName = config('database.connections.mysql.database');

        // Validasi nama tabel agar aman
        $validTables = collect(DB::select('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?', [$dbName]))
            ->pluck('TABLE_NAME')->toArray();

        if (!in_array($table, $validTables, true)) {
            abort(404, 'Tabel tidak ditemukan');
        }

        $columns = DB::select('SELECT COLUMN_NAME as nama, COLUMN_TYPE as tipe, IS_NULLABLE as nullable, 
            COLUMN_KEY as kunci, COLUMN_DEFAULT as default_val, EXTRA as extra
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION', [$dbName, $table]);

        $rowCount = DB::table($table)->count();

        $recentRows = DB::table($table)->orderByDesc('id')->limit(10)->get();

        $tableInfo = DB::select('SELECT TABLE_ROWS as jumlah_baris,
            ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024, 2) as total_kb,
            ENGINE as engine, CREATE_TIME as dibuat
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?', [$dbName, $table]);

        return view('admin.database.show', compact('table', 'columns', 'rowCount', 'recentRows', 'tableInfo'));
    }

    /**
     * Cloud Drive Management — admin melihat semua drive dari semua peran.
     */
    public function cloudIndex(Request $request)
    {
        $query = PenyimpananCloud::with('pengguna')->latest();

        if ($request->filled('peran')) {
            $query->where('peran_pemilik', $request->peran);
        }
        if ($request->filled('jenis_drive')) {
            $query->where('jenis_drive', $request->jenis_drive);
        }
        if ($request->filled('jenis_data')) {
            $query->where('jenis_data', $request->jenis_data);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $drives = $query->paginate(20);

        // Statistik per peran
        $statPerPeran = PenyimpananCloud::select('peran_pemilik', DB::raw('COUNT(*) as total'))
            ->groupBy('peran_pemilik')->pluck('total', 'peran_pemilik');

        $statPerDrive = PenyimpananCloud::select('jenis_drive', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_drive')->pluck('total', 'jenis_drive');

        return view('admin.database.cloud', compact('drives', 'statPerPeran', 'statPerDrive'));
    }

    /**
     * Admin buat cloud drive entry (untuk admin sendiri atau peran lain).
     */
    public function cloudStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_drive' => 'required|string|in:google_drive,google_drive_bisnis,onedrive,terabox,custom',
            'jenis_drive_kustom' => 'nullable|required_if:jenis_drive,custom|string|max:100',
            'jenis_data' => 'required|string',
            'url_link' => 'required|url|max:2000',
            'deskripsi' => 'nullable|string|max:1000',
            'ukuran_byte' => 'nullable|integer|min:0',
            'bisa_dihapus' => 'boolean',
            'peran_pemilik' => 'required|string',
        ]);

        $validated['pengguna_id'] = auth()->id();
        $validated['bisa_dihapus'] = $request->boolean('bisa_dihapus', true);

        PenyimpananCloud::create($validated);

        LogAktivitas::catat('create', 'penyimpanan_cloud', 'Menambahkan link cloud drive: ' . $validated['nama']);

        return back()->with('sukses', 'Link cloud drive berhasil ditambahkan!');
    }

    /**
     * Update cloud drive.
     */
    public function cloudUpdate(Request $request, PenyimpananCloud $cloud)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_drive' => 'required|string|in:google_drive,google_drive_bisnis,onedrive,terabox,custom',
            'jenis_drive_kustom' => 'nullable|required_if:jenis_drive,custom|string|max:100',
            'jenis_data' => 'required|string',
            'url_link' => 'required|url|max:2000',
            'deskripsi' => 'nullable|string|max:1000',
            'bisa_dihapus' => 'boolean',
            'peran_pemilik' => 'required|string',
        ]);

        $validated['bisa_dihapus'] = $request->boolean('bisa_dihapus', true);

        $cloud->update($validated);

        LogAktivitas::catat('update', 'penyimpanan_cloud', 'Memperbarui cloud drive: ' . $cloud->nama);

        return back()->with('sukses', 'Cloud drive berhasil diperbarui!');
    }

    /**
     * Hapus cloud drive — hanya admin yang bisa hapus.
     */
    public function cloudDestroy(PenyimpananCloud $cloud)
    {
        $nama = $cloud->nama;
        $cloud->delete();

        LogAktivitas::catat('delete', 'penyimpanan_cloud', 'Menghapus cloud drive: ' . $nama);

        return back()->with('sukses', 'Cloud drive berhasil dihapus!');
    }
}
