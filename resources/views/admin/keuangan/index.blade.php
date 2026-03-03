@extends('layouts.admin')
@section('title', 'Keuangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Keuangan Sekolah</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola transaksi keuangan dan anggaran</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.keuangan.budget') }}" class="btn btn-outline-primary"><i class="bi bi-wallet2 me-1"></i> RKAS</a>
        <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Transaksi</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#34d399)">
            <div class="icon-box"><i class="bi bi-arrow-down-circle-fill"></i></div>
            <h3>Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h3><p>Total Pemasukan</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#f87171)">
            <div class="icon-box"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <h3>Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h3><p>Total Pengeluaran</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
            <div class="icon-box"><i class="bi bi-cash-stack"></i></div>
            <h3>Rp {{ number_format(($totalPemasukan ?? 0) - ($totalPengeluaran ?? 0), 0, ',', '.') }}</h3><p>Saldo</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)">
            <div class="icon-box"><i class="bi bi-hourglass-split"></i></div>
            <h3>{{ $pendingCount ?? 0 }}</h3><p>Menunggu Verifikasi</p>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari transaksi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="pemasukan" {{ request('jenis')=='pemasukan'?'selected':'' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('jenis')=='pengeluaran'?'selected':'' }}>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="verified" {{ request('status')=='verified'?'selected':'' }}>Terverifikasi</option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.keuangan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Kode</th><th>Tanggal</th><th>Keterangan</th><th>Kategori</th><th>Jenis</th><th class="text-end">Jumlah</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td>{{ $loop->iteration + ($transactions->currentPage()-1)*$transactions->perPage() }}</td>
                        <td><code>{{ $t->kode_transaksi }}</code></td>
                        <td>{{ $t->tanggal ? \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') : '-' }}</td>
                        <td class="fw-semibold">{{ Str::limit($t->uraian, 40) }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($t->kategori ?? '-') }}</span></td>
                        <td>
                            @if($t->jenis == 'pemasukan')<span class="badge bg-success"><i class="bi bi-arrow-down"></i> Masuk</span>
                            @else<span class="badge bg-danger"><i class="bi bi-arrow-up"></i> Keluar</span>@endif
                        </td>
                        <td class="text-end fw-semibold {{ $t->jenis=='pemasukan' ? 'text-success' : 'text-danger' }}">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status == 'approved')<span class="badge bg-success">Disetujui</span>
                            @elseif($t->status == 'verified')<span class="badge bg-info">Terverifikasi</span>
                            @else<span class="badge bg-warning text-dark">Draft</span>@endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.keuangan.show', $t) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @if($t->status == 'draft')
                                <form action="{{ route('admin.keuangan.verify', $t) }}" method="POST">@csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success" data-confirm="Verifikasi transaksi ini?"><i class="bi bi-check-lg"></i></button>
                                </form>
                                @endif
                                <form action="{{ route('admin.keuangan.destroy', $t) }}" method="POST">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus transaksi ini?"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transactions->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $transactions->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
