@extends('staf.tata-letak.app')
@section('judul', 'Surat Menyurat')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-envelope-paper-fill text-primary me-2"></i>Surat Menyurat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Daftar surat masuk dan surat keluar</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staf.surat.create', ['jenis'=>'masuk']) }}" class="btn btn-outline-success btn-sm"><i class="bi bi-envelope-arrow-down me-1"></i>Catat Surat Masuk</a>
        <a href="{{ route('staf.surat.create', ['jenis'=>'keluar']) }}" class="btn btn-primary btn-sm"><i class="bi bi-envelope-arrow-up me-1"></i>Buat Surat Keluar</a>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="masuk" {{ request('jenis')=='masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="keluar" {{ request('jenis')=='keluar' ? 'selected' : '' }}>Surat Keluar</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">Pencarian</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nomor / perihal..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('staf.surat.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Surat List -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Nomor Surat</th>
                    <th>Jenis</th>
                    <th>Perihal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $i => $surat)
                <tr>
                    <td class="text-muted">{{ $surats->firstItem() + $i }}</td>
                    <td><code class="text-primary" style="font-size:.78rem;">{{ $surat->nomor_surat }}</code></td>
                    <td>
                        @if($surat->jenis == 'masuk')
                            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-envelope-arrow-down me-1"></i>Masuk</span>
                        @else
                            <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-envelope-arrow-up me-1"></i>Keluar</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem;">{{ Str::limit($surat->perihal, 45) }}</div>
                        <small class="text-muted">{{ ucfirst($surat->kategori) }} &middot; {!! $surat->sifat_badge !!}</small>
                    </td>
                    <td style="font-size:.82rem;">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td>{!! $surat->status_badge !!}</td>
                    <td class="text-end">
                        <a href="{{ route('staf.surat.show', $surat) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-envelope-x" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada data surat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($surats->hasPages())
    <div class="card-footer bg-white border-0 py-3">{{ $surats->links() }}</div>
    @endif
</div>
@endsection
