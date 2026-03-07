@extends('peran.kepala-sekolah.app')
@section('judul', 'Resolusi & Keputusan')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-journal-check text-primary me-2"></i>Resolusi & Keputusan</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Buat dan kelola keputusan resmi Kepala Sekolah</p>
    </div>
    <a href="{{ route('kepala-sekolah.resolusi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Resolusi</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h4><small class="text-muted">Total Resolusi</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-success">{{ $stats['berlaku'] }}</h4><small class="text-muted">Berlaku</small>
        </div></div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-warning">{{ $stats['draft'] }}</h4><small class="text-muted">Draft</small>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['kebijakan','sanksi','penghargaan','mutasi','anggaran','kurikulum','lainnya'] as $k)
                        <option value="{{ $k }}" {{ request('kategori')==$k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['draft','berlaku','dicabut'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('kepala-sekolah.resolusi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Resolusi</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Berlaku</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resolusi as $r)
                <tr>
                    <td><code class="text-primary" style="font-size:.8rem;">{{ $r->nomor_resolusi }}</code></td>
                    <td><div class="fw-semibold" style="font-size:.85rem;">{{ Str::limit($r->judul, 50) }}</div></td>
                    <td><span class="badge bg-light text-dark">{{ ucfirst($r->kategori) }}</span></td>
                    <td style="font-size:.82rem;">{{ $r->tanggal_berlaku->format('d/m/Y') }}</td>
                    <td>
                        @php $sc = ['draft'=>'warning','berlaku'=>'success','dicabut'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$r->status] ?? 'secondary' }}">{{ ucfirst($r->status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('kepala-sekolah.resolusi.show', $r) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('kepala-sekolah.resolusi.edit', $r) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('kepala-sekolah.resolusi.destroy', $r) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" onclick="return confirm('Hapus resolusi ini?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-journal-x" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada resolusi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($resolusi->hasPages())
    <div class="card-footer bg-white border-0 py-3">{{ $resolusi->links() }}</div>
    @endif
</div>
@endsection
