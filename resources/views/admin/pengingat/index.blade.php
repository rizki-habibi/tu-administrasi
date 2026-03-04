@extends('admin.tata-letak.app')
@section('judul', 'Pengingat')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Pengingat / Reminder</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola pengingat deadline dan tugas</p>
    </div>
    <a href="{{ route('admin.pengingat.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Pengingat</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)">
            <div class="icon-box"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <h3>{{ $overdueCount ?? 0 }}</h3><p>Terlambat / Overdue</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
            <div class="icon-box"><i class="bi bi-hourglass-split"></i></div>
            <h3>{{ $activeCount ?? 0 }}</h3><p>Aktif</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#34d399)">
            <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
            <h3>{{ $completedCount ?? 0 }}</h3><p>Selesai</p>
        </div>
    </div>
</div>

<!-- Active Reminders -->
<div class="card mb-4">
    <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-bell me-1 text-warning"></i> Pengingat Aktif</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Judul</th><th>Deskripsi</th><th>Tipe</th><th>Untuk</th><th>Deadline</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($reminders as $r)
                    @php $isOverdue = \Carbon\Carbon::parse($r->tenggat)->isPast(); @endphp
                    <tr class="{{ $isOverdue ? 'table-warning' : '' }}">
                        <td>{{ $loop->iteration + ($reminders->currentPage()-1)*$reminders->perPage() }}</td>
                        <td class="fw-semibold">
                            {{ $r->judul }}
                            @if($isOverdue)<span class="badge bg-danger ms-1">Terlambat!</span>@endif
                        </td>
                        <td>{{ Str::limit($r->deskripsi, 50) }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucwords(str_replace('_', ' ', $r->jenis ?? '-')) }}</span></td>
                        <td><span class="badge bg-primary">{{ $r->user->nama ?? '-' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($r->tenggat)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.pengingat.toggle', $r) }}" method="POST">@csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" title="Tandai selesai"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form action="{{ route('admin.pengingat.destroy', $r) }}" method="POST">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Hapus pengingat ini?"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada pengingat aktif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reminders->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $reminders->withQueryString()->links() }}</div>
    @endif
</div>

<!-- Completed Reminders -->
@if(isset($completed) && $completed->count() > 0)
<div class="card">
    <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-check-circle me-1 text-success"></i> Selesai (Terakhir)</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Judul</th><th>Deadline</th><th>Selesai</th></tr></thead>
                <tbody>
                    @foreach($completed as $c)
                    <tr class="text-muted">
                        <td><s>{{ $c->judul }}</s></td>
                        <td>{{ \Carbon\Carbon::parse($c->tenggat)->format('d/m/Y') }}</td>
                        <td>{{ $c->updated_at ? $c->updated_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
