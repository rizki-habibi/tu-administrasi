@extends('staf.tata-letak.app')
@section('judul', 'Inventaris & Sarpras')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-box-seam me-2"></i>Inventaris & Sarpras</h4>
    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#damageModal">
        <i class="bi bi-exclamation-triangle me-1"></i> Lapor Kerusakan
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari barang..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="kondisi" class="form-select form-select-sm">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi')=='baik'?'selected':'' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi')=='rusak_ringan'?'selected':'' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi')=='rusak_berat'?'selected':'' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="lokasi" class="form-select form-select-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations ?? [] as $loc)
                    <option value="{{ $loc }}" {{ request('lokasi')==$loc?'selected':'' }}>{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items ?? [] as $i => $item)
                <tr>
                    <td>{{ ($items instanceof \Illuminate\Pagination\LengthAwarePaginator ? $items->firstItem() + $i : $i + 1) }}</td>
                    <td><code>{{ $item->kode_barang }}</code></td>
                    <td class="fw-semibold">{{ $item->nama_barang }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td>{{ $item->jumlah ?? 0 }}</td>
                    <td>
                        @if($item->kondisi == 'baik')
                        <span class="badge bg-success">Baik</span>
                        @elseif($item->kondisi == 'rusak_ringan')
                        <span class="badge bg-warning text-dark">Rusak Ringan</span>
                        @else
                        <span class="badge bg-danger">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('staf.inventaris.show', $item->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-box-seam" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada data inventaris</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items ?? false)
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $items->count() }} dari {{ $items->total() }} barang</small>
        {{ $items->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Report Damage Modal --}}
<div class="modal fade" id="damageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staf.inventaris.damage') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Lapor Kerusakan Barang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="inventaris_id" class="form-select" required>
                            <option value="">Pilih barang...</option>
                            @foreach($allItems ?? [] as $item)
                            <option value="{{ $item->id }}">{{ $item->kode_barang }} - {{ $item->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Kerusakan</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-exclamation-triangle me-1"></i> Laporkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
