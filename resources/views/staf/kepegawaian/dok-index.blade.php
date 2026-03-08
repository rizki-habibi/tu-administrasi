@extends('peran.staf.app')
@section('judul', 'Dokumen Kepegawaian')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-folder2-open"></i> Dokumen Kepegawaian</h4>
    <div>
        <a href="{{ route('staf.dok-kepegawaian.ekspor') }}" class="btn btn-success me-2"><i class="bi bi-download"></i> Ekspor CSV</a>
        <a href="{{ route('staf.dok-kepegawaian.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Dokumen</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Judul, nomor, atau nama pegawai...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\DokumenKepegawaian::KATEGORI as $k => $v)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="40">No</th>
                    <th>Pegawai</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokumen as $i => $d)
                <tr>
                    <td>{{ $dokumen->firstItem() + $i }}</td>
                    <td>{{ $d->pengguna->nama ?? '-' }}</td>
                    <td>{{ $d->judul }}</td>
                    <td><span class="badge bg-info text-dark">{{ \App\Models\DokumenKepegawaian::KATEGORI[$d->kategori] ?? $d->kategori }}</span></td>
                    <td>{{ $d->nomor_dokumen ?? '-' }}</td>
                    <td>{{ $d->tanggal_dokumen ? $d->tanggal_dokumen->format('d/m/Y') : '-' }}</td>
                    <td>
                        <a href="{{ route('staf.dok-kepegawaian.show', $d) }}" class="btn btn-sm btn-outline-info" title="Lihat"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('staf.dok-kepegawaian.edit', $d) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('staf.dok-kepegawaian.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada dokumen kepegawaian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dokumen->hasPages())
        <div class="card-footer">{{ $dokumen->links() }}</div>
    @endif
</div>
@endsection
