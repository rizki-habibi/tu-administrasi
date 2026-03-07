@extends('peran.admin.app')
@section('judul', 'Disposisi Surat')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-send-check-fill text-primary me-2"></i>Disposisi Surat</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Distribusikan surat masuk ke staff terkait untuk ditindaklanjuti</p>
    </div>
    <a href="{{ route('admin.disposisi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat Disposisi</a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h4><small class="text-muted">Total</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-danger">{{ $stats['belum_dibaca'] }}</h4><small class="text-muted">Belum Dibaca</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-warning">{{ $stats['diproses'] }}</h4><small class="text-muted">Diproses</small>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm"><div class="card-body text-center py-3">
            <h4 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h4><small class="text-muted">Selesai</small>
        </div></div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['belum_dibaca','dibaca','diproses','selesai'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Prioritas</label>
                <select name="prioritas" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['rendah','sedang','tinggi','urgent'] as $p)
                        <option value="{{ $p }}" {{ request('prioritas')==$p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('admin.disposisi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Surat</th>
                    <th>Ditujukan Kepada</th>
                    <th>Instruksi</th>
                    <th>Prioritas</th>
                    <th>Tenggat</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disposisi as $i => $d)
                <tr>
                    <td class="text-muted">{{ $disposisi->firstItem() + $i }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem;">{{ Str::limit($d->surat->perihal ?? '-', 30) }}</div>
                        <code class="text-primary" style="font-size:.75rem;">{{ $d->surat->nomor_surat ?? '' }}</code>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:30px;height:30px;font-size:.7rem;">
                                {{ strtoupper(substr($d->kepadaPengguna->nama ?? '', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-size:.85rem;">{{ $d->kepadaPengguna->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $d->kepadaPengguna->jabatan ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.82rem;">{{ Str::limit($d->instruksi, 40) }}</td>
                    <td>
                        @php $pc = ['rendah'=>'success','sedang'=>'info','tinggi'=>'warning','urgent'=>'danger']; @endphp
                        <span class="badge bg-{{ $pc[$d->prioritas] ?? 'secondary' }}">{{ ucfirst($d->prioritas) }}</span>
                    </td>
                    <td style="font-size:.82rem;">
                        @if($d->tenggat)
                            <span class="{{ $d->tenggat->isPast() ? 'text-danger fw-bold' : '' }}">{{ $d->tenggat->format('d/m/Y') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @php $sc = ['belum_dibaca'=>'danger','dibaca'=>'info','diproses'=>'warning','selesai'=>'success']; @endphp
                        <span class="badge bg-{{ $sc[$d->status] ?? 'secondary' }} bg-opacity-10 text-{{ $sc[$d->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$d->status)) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.disposisi.show', $d) }}" class="btn btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                            <form action="{{ route('admin.disposisi.destroy', $d) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" title="Hapus" onclick="return confirm('Hapus disposisi ini?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-send-x" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada disposisi surat</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($disposisi->hasPages())
    <div class="card-footer bg-white border-0 py-3">{{ $disposisi->links() }}</div>
    @endif
</div>
@endsection
