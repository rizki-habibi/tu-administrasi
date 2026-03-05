@extends('peran.admin.app')
@section('judul', 'Pengajuan Izin')

@section('konten')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['pending','approved','rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
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
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Staff</th>
                    <th>Tipe</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusColors = ['pending'=>'warning','approved'=>'success','rejected'=>'danger'];
                    $typeColors = ['izin'=>'info','sakit'=>'primary','cuti'=>'secondary','dinas_luar'=>'dark'];
                @endphp
                @forelse($leaveRequests as $i => $leave)
                <tr>
                    <td>{{ $leaveRequests->firstItem() + $i }}</td>
                    <td>{{ $leave->user->nama ?? '-' }}</td>
                    <td><span class="badge bg-{{ $typeColors[$leave->jenis] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$leave->jenis)) }}</span></td>
                    <td>{{ $leave->tanggal_mulai->format('d/m/Y') }}</td>
                    <td>{{ $leave->tanggal_selesai->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($leave->reason, 40) }}</td>
                    <td><span class="badge bg-{{ $statusColors[$leave->status] ?? 'secondary' }}">{{ ucfirst($leave->status) }}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.izin.show', $leave) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                            @if($leave->status === 'pending')
                                <form action="{{ route('admin.izin.setujui', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pengajuan ini?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-outline-success" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form action="{{ route('admin.izin.tolak', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pengajuan ini?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="admin_note" value="Ditolak">
                                    <button class="btn btn-outline-danger" title="Tolak"><i class="bi bi-x-lg"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pengajuan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $leaveRequests->links() }}</div>
@endsection
