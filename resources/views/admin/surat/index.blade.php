@extends('admin.tata-letak.app')
@section('judul', 'Manajemen Surat')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-envelope-paper-fill text-primary me-2"></i>Manajemen Surat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Kelola surat masuk, surat keluar, dan penomoran otomatis</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.surat.create', ['jenis'=>'masuk']) }}" class="btn btn-outline-success"><i class="bi bi-envelope-arrow-down me-1"></i>Surat Masuk</a>
        <a href="{{ route('admin.surat.create', ['jenis'=>'keluar']) }}" class="btn btn-primary"><i class="bi bi-envelope-arrow-up me-1"></i>Surat Keluar</a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h4>
                <small class="text-muted">Total Surat</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold mb-0 text-success">{{ $stats['masuk'] }}</h4>
                <small class="text-muted">Surat Masuk</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold mb-0 text-info">{{ $stats['keluar'] }}</h4>
                <small class="text-muted">Surat Keluar</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold mb-0 text-secondary">{{ $stats['draft'] }}</h4>
                <small class="text-muted">Draf</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold mb-0 text-warning">{{ $stats['diproses'] }}</h4>
                <small class="text-muted">Diproses</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="masuk" {{ request('jenis')=='masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="keluar" {{ request('jenis')=='keluar' ? 'selected' : '' }}>Surat Keluar</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['dinas','undangan','keterangan','keputusan','edaran','tugas','pemberitahuan','lainnya'] as $k)
                        <option value="{{ $k }}" {{ request('kategori')==$k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['draft','diproses','dikirim','diterima','diarsipkan'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Pencarian</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nomor / perihal / tujuan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('admin.surat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Nomor Surat</th>
                    <th>Jenis</th>
                    <th>Perihal</th>
                    <th>Tujuan / Asal</th>
                    <th>Tanggal</th>
                    <th>Sifat</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $i => $surat)
                <tr>
                    <td class="text-muted">{{ $surats->firstItem() + $i }}</td>
                    <td>
                        <code class="text-primary fw-semibold" style="font-size:.8rem;">{{ $surat->nomor_surat }}</code>
                    </td>
                    <td>
                        @if($surat->jenis == 'masuk')
                            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-envelope-arrow-down me-1"></i>Masuk</span>
                        @else
                            <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-envelope-arrow-up me-1"></i>Keluar</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem;">{{ Str::limit($surat->perihal, 40) }}</div>
                        <small class="text-muted">{{ ucfirst($surat->kategori) }}</small>
                    </td>
                    <td style="font-size:.85rem;">
                        @if($surat->jenis == 'masuk')
                            <i class="bi bi-building me-1 text-muted"></i>{{ $surat->asal ?? '-' }}
                        @else
                            <i class="bi bi-send me-1 text-muted"></i>{{ $surat->tujuan ?? '-' }}
                        @endif
                    </td>
                    <td style="font-size:.82rem;">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td>{!! $surat->sifat_badge !!}</td>
                    <td>{!! $surat->status_badge !!}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.surat.show', $surat) }}" class="btn btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.surat.edit', $surat) }}" class="btn btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.surat.destroy', $surat) }}" method="POST" onsubmit="return confirm('Hapus surat ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-envelope-x" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada data surat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($surats->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $surats->links() }}
    </div>
    @endif
</div>
@endsection
