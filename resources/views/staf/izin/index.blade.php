@extends('peran.staf.app')
@section('judul', 'Pengajuan Izin')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-calendar-x"></i> Pengajuan Izin / Cuti</h4>
    <a href="{{ route('staf.izin.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajukan Izin</a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
        $pending = $leaveRequests->where('status', 'pending')->count();
        $approved = $leaveRequests->where('status', 'approved')->count();
        $rejected = $leaveRequests->where('status', 'rejected')->count();
    @endphp
    <div class="col-md-4"><div class="card border-0 shadow-sm border-start border-warning border-4"><div class="card-body"><h6 class="text-muted">Menunggu</h6><h3>{{ $pending }}</h3></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm border-start border-success border-4"><div class="card-body"><h6 class="text-muted">Disetujui</h6><h3>{{ $approved }}</h3></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm border-start border-danger border-4"><div class="card-body"><h6 class="text-muted">Ditolak</h6><h3>{{ $rejected }}</h3></div></div></div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['izin','sakit','cuti','dinas_luar'] as $t)
                        <option value="{{ $t }}" {{ request('jenis') == $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Saring</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Tanggal Pengajuan</th><th>Jenis</th><th>Periode</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($leaveRequests as $leave)
                <tr>
                    <td>{{ $leave->created_at->format('d/m/Y') }}</td>
                    <td><span class="badge bg-info">{{ ucfirst(str_replace('_',' ',$leave->jenis)) }}</span></td>
                    <td>{{ $leave->tanggal_mulai->format('d/m/Y') }} - {{ $leave->tanggal_selesai->format('d/m/Y') }}</td>
                    <td>
                        @php $colors = ['pending'=>'warning','approved'=>'success','rejected'=>'danger']; $statusLabel = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak']; @endphp
                        <span class="badge bg-{{ $colors[$leave->status] ?? 'secondary' }}">{{ $statusLabel[$leave->status] ?? ucfirst($leave->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('staf.izin.show', $leave) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        @if($leave->status == 'pending')
                            <form action="{{ route('staf.izin.destroy', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pengajuan?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $leaveRequests->links() }}</div>
@endsection
