@extends('peran.kepala-sekolah.app')
@section('judul', 'Permohonan Izin/Cuti')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Permohonan Izin & Cuti</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola permohonan izin dan cuti staff</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                @php $statusOpt = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak']; @endphp
                @foreach($statusOpt as $sVal => $sLabel)
                    <option value="{{ $sVal }}" {{ request('status') == $sVal ? 'selected' : '' }}>{{ $sLabel }}</option>
                @endforeach
            </select>
            <select name="jenis" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Jenis</option>
                @foreach(['izin','sakit','cuti'] as $t)
                    <option value="{{ $t }}" {{ request('jenis') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>No</th><th>Pegawai</th><th>Jenis</th><th>Tanggal</th><th>Durasi</th><th>Alasan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($leaveRequests as $i => $lr)
                    <tr>
                        <td>{{ $leaveRequests instanceof \Illuminate\Pagination\LengthAwarePaginator ? $leaveRequests->firstItem() + $i : $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $lr->user->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $lr->user->role_label ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($lr->jenis) }}</span></td>
                        <td style="font-size:.8rem;">{{ $lr->tanggal_mulai->format('d/m/Y') }} - {{ $lr->tanggal_selesai->format('d/m/Y') }}</td>
                        <td>{{ $lr->duration }} hari</td>
                        <td style="max-width:180px;font-size:.8rem;">{{ \Str::limit($lr->reason, 40) }}</td>
                        @php $sl = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak']; @endphp
                        <td><span class="badge bg-{{ $lr->status_badge }} bg-opacity-10 text-{{ $lr->status_badge }}">{{ $sl[$lr->status] ?? ucfirst($lr->status) }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('kepala-sekolah.izin.show', $lr) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a>
                                @if($lr->status === 'pending')
                                    <form action="{{ route('kepala-sekolah.izin.setujui', $lr) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui" onclick="return confirm('Setujui permohonan ini?')"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('kepala-sekolah.izin.tolak', $lr) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak" onclick="return confirm('Tolak permohonan ini?')"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada permohonan izin/cuti</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($leaveRequests instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $leaveRequests->withQueryString()->links() }}</div>
@endif
@endsection
