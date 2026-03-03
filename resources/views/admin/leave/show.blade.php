@extends('layouts.admin')
@section('title', 'Detail Pengajuan')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th width="180">Pengaju</th><td>{{ $leaveRequest->user->name ?? '-' }}</td></tr>
                    <tr><th>Jenis</th><td><span class="badge bg-info">{{ ucfirst(str_replace('_',' ',$leaveRequest->type)) }}</span></td></tr>
                    <tr><th>Tanggal</th><td>{{ $leaveRequest->start_date->format('d M Y') }} - {{ $leaveRequest->end_date->format('d M Y') }}</td></tr>
                    <tr><th>Durasi</th><td>{{ $leaveRequest->start_date->diffInDays($leaveRequest->end_date) + 1 }} hari</td></tr>
                    <tr><th>Alasan</th><td>{{ $leaveRequest->reason }}</td></tr>
                    <tr><th>Status</th><td>
                        @switch($leaveRequest->status)
                            @case('pending')<span class="badge bg-warning">Pending</span>@break
                            @case('approved')<span class="badge bg-success">Disetujui</span>@break
                            @case('rejected')<span class="badge bg-danger">Ditolak</span>@break
                        @endswitch
                    </td></tr>
                    @if($leaveRequest->approved_by)
                    <tr><th>Diproses Oleh</th><td>{{ $leaveRequest->approver->name ?? '-' }}</td></tr>
                    @endif
                    @if($leaveRequest->admin_note)
                    <tr><th>Catatan Admin</th><td>{{ $leaveRequest->admin_note }}</td></tr>
                    @endif
                    @if($leaveRequest->attachment)
                    <tr>
                        <th>Lampiran</th>
                        <td>
                            @php $ext = pathinfo($leaveRequest->attachment, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                                <img src="{{ asset('storage/' . $leaveRequest->attachment) }}" class="img-fluid rounded mb-2" style="max-height: 200px;"><br>
                            @endif
                            <a href="{{ asset('storage/' . $leaveRequest->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Lihat Lampiran</a>
                        </td>
                    </tr>
                    @endif
                    <tr><th>Tanggal Pengajuan</th><td>{{ $leaveRequest->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($leaveRequest->status === 'pending')
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><h6 class="mb-0">Tindakan</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.leave.approve', $leaveRequest) }}" method="POST" class="mb-3">
                    @csrf @method('PATCH')
                    <button class="btn btn-success w-100"><i class="bi bi-check-lg"></i> Setujui</button>
                </form>
                <form action="{{ route('admin.leave.reject', $leaveRequest) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="admin_note" class="form-control @error('admin_note') is-invalid @enderror" rows="3" required></textarea>
                        @error('admin_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-danger w-100"><i class="bi bi-x-lg"></i> Tolak</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('admin.leave.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
@endsection
