@extends('peran.staf.app')
@section('judul', 'Detail Izin')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-eye"></i> Detail Pengajuan Izin</h4>
    <a href="{{ route('staf.izin.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Jenis Izin</div>
                    <div class="col-md-8"><span class="badge bg-info">{{ ucfirst(str_replace('_',' ',$leaveRequest->jenis)) }}</span></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Periode</div>
                    <div class="col-md-8">{{ $leaveRequest->tanggal_mulai->format('d F Y') }} - {{ $leaveRequest->tanggal_selesai->format('d F Y') }}
                        <small class="text-muted">({{ $leaveRequest->tanggal_mulai->diffInDays($leaveRequest->tanggal_selesai) + 1 }} hari)</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Alasan</div>
                    <div class="col-md-8">{{ $leaveRequest->alasan }}</div>
                </div>
                @if($leaveRequest->lampiran)
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Lampiran</div>
                    <div class="col-md-8"><a href="{{ asset('storage/' . $leaveRequest->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Lihat Lampiran</a></div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal Pengajuan</div>
                    <div class="col-md-8">{{ $leaveRequest->created_at->format('d F Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Status</h6></div>
            <div class="card-body text-center">
                @php $colors = ['pending'=>'warning','approved'=>'success','rejected'=>'danger']; $statusLabel = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak']; @endphp
                <span class="badge bg-{{ $colors[$leaveRequest->status] ?? 'secondary' }} fs-6 px-4 py-2">{{ $statusLabel[$leaveRequest->status] ?? ucfirst($leaveRequest->status) }}</span>

                @if($leaveRequest->approver)
                    <p class="text-muted mt-3 mb-1">Diproses oleh:</p>
                    <strong>{{ $leaveRequest->approver->nama }}</strong>
                @endif

                @if($leaveRequest->catatan_admin)
                    <div class="alert alert-light mt-3 text-start">
                        <small class="fw-bold">Catatan Admin:</small><br>
                        {{ $leaveRequest->catatan_admin }}
                    </div>
                @endif

                @if($leaveRequest->status == 'pending')
                    <div class="mt-3">
                        <form action="{{ route('staf.izin.destroy', $leaveRequest) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"><i class="bi bi-x-circle"></i> Batalkan Pengajuan</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
