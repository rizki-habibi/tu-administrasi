@extends('peran.admin.app')
@section('judul', 'Log Aktivitas')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas Sistem</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">Riwayat semua aktivitas pengguna di sistem administrasi</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">Modul</label>
                <select name="modul" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($modules as $m)
                        <option value="{{ $m }}" {{ request('modul')==$m ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$m)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Aksi</label>
                <select name="aksi" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" {{ request('aksi')==$a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
            <thead class="table-light">
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Modul</th>
                    <th>Deskripsi</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-nowrap">
                        <small>{{ $log->created_at->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $log->pengguna->nama ?? '-' }}</div>
                        <small class="text-muted">{{ $log->pengguna->role_label ?? '' }}</small>
                    </td>
                    <td>
                        @php $ac = ['create'=>'success','update'=>'warning','delete'=>'danger','login'=>'info','logout'=>'secondary','approve'=>'primary','reject'=>'danger']; @endphp
                        <span class="badge bg-{{ $ac[$log->aksi] ?? 'secondary' }} bg-opacity-10 text-{{ $ac[$log->aksi] ?? 'secondary' }}">{{ ucfirst($log->aksi) }}</span>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$log->modul)) }}</span></td>
                    <td>{{ Str::limit($log->deskripsi, 60) }}</td>
                    <td><code style="font-size:.75rem;">{{ $log->ip_address }}</code></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-clock" style="font-size:2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada log aktivitas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white border-0 py-3">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
