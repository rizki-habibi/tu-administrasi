@extends('peran.staf.app')
@section('judul', 'Catatan Keuangan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Catatan Keuangan</h4>
    <div>
        <a href="{{ route('staf.keuangan.ekspor') }}" class="btn btn-success"><i class="bi bi-download"></i> Ekspor CSV</a>
        <a href="{{ route('staf.keuangan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-success fw-bold fs-5">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                <small class="text-muted">Total Pemasukan</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-danger fw-bold fs-5">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                <small class="text-muted">Total Pengeluaran</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <div class="text-primary fw-bold fs-5">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</div>
                <small class="text-muted">Saldo</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua</option>
                    <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" value="{{ request('kategori') }}" placeholder="Kategori...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Uraian...">
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
                <tr><th>Tanggal</th><th>Kode</th><th>Jenis</th><th>Kategori</th><th>Uraian</th><th class="text-end">Jumlah</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($catatan as $c)
                <tr>
                    <td>{{ $c->tanggal?->format('d/m/Y') }}</td>
                    <td><code>{{ $c->kode_transaksi }}</code></td>
                    <td>
                        <span class="badge bg-{{ $c->jenis == 'pemasukan' ? 'success' : 'danger' }}">{{ ucfirst($c->jenis) }}</span>
                    </td>
                    <td>{{ $c->kategori }}</td>
                    <td>{{ Str::limit($c->uraian, 40) }}</td>
                    <td class="text-end fw-bold text-{{ $c->jenis == 'pemasukan' ? 'success' : 'danger' }}">
                        Rp {{ number_format($c->jumlah, 0, ',', '.') }}
                    </td>
                    <td>
                        @php $sc = ['draft'=>'secondary','verified'=>'success','rejected'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$c->status] ?? 'secondary' }}">{{ ucfirst($c->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('staf.keuangan.show', $c) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('staf.keuangan.edit', $c) }}" class="btn btn-sm btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('staf.keuangan.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada catatan keuangan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $catatan->links() }}</div>
@endsection
