@extends('layouts.admin')
@section('title', 'Inventaris / Sarana Prasarana')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Inventaris Barang</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola inventaris sarana prasarana sekolah</p>
    </div>
    <a href="{{ route('admin.inventaris.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Barang</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
            <div class="icon-box"><i class="bi bi-box-seam-fill"></i></div>
            <h3>{{ $items->total() }}</h3><p>Total Barang</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#34d399)">
            <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
            <h3>{{ $baikCount ?? 0 }}</h3><p>Kondisi Baik</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)">
            <div class="icon-box"><i class="bi bi-tools"></i></div>
            <h3>{{ $rusakRinganCount ?? 0 }}</h3><p>Rusak Ringan</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#f87171)">
            <div class="icon-box"><i class="bi bi-x-circle-fill"></i></div>
            <h3>{{ $rusakBeratCount ?? 0 }}</h3><p>Rusak Berat</p>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari kode/nama barang..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach(['mebeler','elektronik','alat_peraga','olahraga','laboratorium','kantor','lainnya'] as $kat)
                    <option value="{{ $kat }}" {{ request('kategori')==$kat?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$kat)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="kondisi" class="form-select">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi')=='baik'?'selected':'' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi')=='rusak_ringan'?'selected':'' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi')=='rusak_berat'?'selected':'' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Jumlah</th><th>Kondisi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->currentPage()-1)*$items->perPage() }}</td>
                        <td><code>{{ $item->kode_barang }}</code></td>
                        <td class="fw-semibold">{{ $item->nama_barang }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst(str_replace('_',' ',$item->kategori)) }}</span></td>
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ $item->jumlah }} {{ $item->satuan ?? 'pcs' }}</td>
                        <td>
                            @if($item->kondisi=='baik')<span class="badge bg-success">Baik</span>
                            @elseif($item->kondisi=='rusak_ringan')<span class="badge bg-warning text-dark">Rusak Ringan</span>
                            @else<span class="badge bg-danger">Rusak Berat</span>@endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.inventaris.show', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.inventaris.edit', $item) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.inventaris.destroy', $item) }}" method="POST">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus barang {{ $item->nama_barang }}?"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada data inventaris</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($items->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $items->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
