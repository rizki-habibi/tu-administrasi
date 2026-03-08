@extends('peran.staf.app')
@section('judul', 'Daftar Buku Perpustakaan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-book"></i> Daftar Buku Perpustakaan</h4>
    <div>
        <a href="{{ route('staf.buku.ekspor') }}" class="btn btn-success"><i class="bi bi-download"></i> Ekspor</a>
        <a href="{{ route('staf.buku.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Buku</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-primary fw-bold fs-5">{{ number_format($totalBuku) }}</div>
                <small class="text-muted">Total Judul Buku</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-success fw-bold fs-5">{{ number_format($totalTersedia) }}</div>
                <small class="text-muted">Total Eksemplar Tersedia</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-warning fw-bold fs-5">Rp {{ number_format($totalNilai, 0, ',', '.') }}</div>
                <small class="text-muted">Total Nilai Aset</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach(\App\Models\BukuPerpustakaan::KATEGORI as $k => $v)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select">
                    <option value="">Semua</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Judul/pengarang/kode...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Saring</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Kode</th><th>Judul</th><th>Pengarang</th><th>Kategori</th><th>Stok</th><th>Kondisi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($buku as $b)
                <tr>
                    <td><code>{{ $b->kode_buku }}</code></td>
                    <td>{{ Str::limit($b->judul, 40) }}</td>
                    <td>{{ $b->pengarang }}</td>
                    <td><span class="badge bg-info">{{ \App\Models\BukuPerpustakaan::KATEGORI[$b->kategori] ?? $b->kategori }}</span></td>
                    <td>
                        <span class="badge bg-{{ $b->jumlah_tersedia > 0 ? 'success' : 'danger' }}">{{ $b->jumlah_tersedia }}/{{ $b->jumlah_total }}</span>
                    </td>
                    <td>
                        @php $kc = ['baik'=>'success','rusak_ringan'=>'warning','rusak_berat'=>'danger']; @endphp
                        <span class="badge bg-{{ $kc[$b->kondisi] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$b->kondisi)) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('staf.buku.show', $b) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('staf.buku.edit', $b) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('staf.buku.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data buku</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $buku->links() }}</div>
@endsection
