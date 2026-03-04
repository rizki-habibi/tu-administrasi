@extends('staf.tata-letak.app')
@section('judul', 'Pengingat')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-bell me-2"></i>Pengingat Saya</h4>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#ef4444 0%,#f87171 100%);">
            <div class="card-body text-white text-center py-3">
                <h3 class="fw-bold mb-0">{{ $overdueCount ?? 0 }}</h3>
                <small>Terlambat</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#f59e0b 0%,#fbbf24 100%);">
            <div class="card-body text-white text-center py-3">
                <h3 class="fw-bold mb-0">{{ $activeCount ?? 0 }}</h3>
                <small>Aktif</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#10b981 0%,#34d399 100%);">
            <div class="card-body text-white text-center py-3">
                <h3 class="fw-bold mb-0">{{ $completedCount ?? 0 }}</h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
</div>

{{-- Active Reminders --}}
<div class="card mb-4">
    <div class="card-header bg-transparent">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-clock me-1"></i> Pengingat Aktif</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tipe</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeReminders ?? [] as $r)
                <tr>
                    <td class="fw-semibold">{{ $r->judul }}</td>
                    <td><small class="text-muted">{{ Str::limit($r->deskripsi, 50) }}</small></td>
                    <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucwords(str_replace('_', ' ', $r->jenis ?? '-')) }}</span></td>
                    <td>
                        @php $isOverdue = $r->tenggat && \Carbon\Carbon::parse($r->tenggat)->isPast(); @endphp
                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                            {{ $r->tenggat ? \Carbon\Carbon::parse($r->tenggat)->translatedFormat('d M Y, H:i') : '-' }}
                        </span>
                        @if($isOverdue)
                        <br><small class="text-danger">Terlambat!</small>
                        @endif
                    </td>
                    <td>
                        @if($isOverdue)
                        <span class="badge bg-danger">Terlambat</span>
                        @else
                        <span class="badge bg-info text-dark">Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="{{ route('staf.pengingat.selesai', $r->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm" title="Tandai Selesai"><i class="bi bi-check-lg"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada pengingat aktif</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Completed Reminders --}}
<div class="card">
    <div class="card-header bg-transparent">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-check-circle me-1"></i> Selesai</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Deadline</th>
                    <th>Diselesaikan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedReminders ?? [] as $r)
                <tr class="text-muted">
                    <td><s>{{ $r->judul }}</s></td>
                    <td>{{ $r->tenggat ? \Carbon\Carbon::parse($r->tenggat)->translatedFormat('d M Y') : '-' }}</td>
                    <td>{{ $r->updated_at->translatedFormat('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada pengingat selesai</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
