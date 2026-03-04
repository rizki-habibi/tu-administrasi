@extends('peran.kepala-sekolah.app')
@section('judul', 'Detail Permohonan Izin')

@section('konten')
<div class="mb-4">
    <a href="{{ route('kepala-sekolah.izin.index') }}" class="text-decoration-none text-warning" style="font-size:.85rem;"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-calendar2-check text-warning me-2"></i>Detail Permohonan</h6>
            </div>
            <div class="card-body">
                <div class="row g-3" style="font-size:.85rem;">
                    <div class="col-md-6"><strong class="text-muted d-block">Pegawai</strong>{{ $leaveRequest->user->nama ?? '-' }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Peran</strong>{{ $leaveRequest->user->role_label ?? '-' }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Jenis</strong><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($leaveRequest->jenis) }}</span></div>
                    <div class="col-md-6"><strong class="text-muted d-block">Status</strong><span class="badge bg-{{ $leaveRequest->status_badge }} bg-opacity-10 text-{{ $leaveRequest->status_badge }}">{{ ucfirst($leaveRequest->status) }}</span></div>
                    <div class="col-md-6"><strong class="text-muted d-block">Tanggal Mulai</strong>{{ $leaveRequest->tanggal_mulai->translatedFormat('d F Y') }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Tanggal Selesai</strong>{{ $leaveRequest->tanggal_selesai->translatedFormat('d F Y') }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Durasi</strong>{{ $leaveRequest->duration }} hari</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Diajukan</strong>{{ $leaveRequest->created_at->translatedFormat('d F Y H:i') }}</div>
                    <div class="col-12"><strong class="text-muted d-block">Alasan</strong>{{ $leaveRequest->reason ?? '-' }}</div>
                    @if($leaveRequest->catatan_admin)
                    <div class="col-12"><strong class="text-muted d-block">Catatan Atasan</strong>{{ $leaveRequest->catatan_admin }}</div>
                    @endif
                </div>

                @if($leaveRequest->lampiran)
                <div class="mt-3">
                    <strong class="text-muted d-block mb-1" style="font-size:.85rem;">Lampiran</strong>
                    <a href="{{ asset('storage/' . $leaveRequest->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-warning"><i class="bi bi-download me-1"></i>Unduh Lampiran</a>
                </div>
                @endif

                @if($leaveRequest->approver)
                <hr>
                <div style="font-size:.85rem;">
                    <strong class="text-muted d-block">Diproses oleh</strong>{{ $leaveRequest->approver->nama }} &middot; {{ $leaveRequest->updated_at->translatedFormat('d F Y H:i') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions Panel --}}
    <div class="col-lg-4">
        @if($leaveRequest->status === 'pending')
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                <h6 class="fw-bold mb-0 text-warning" style="font-size:.9rem;"><i class="bi bi-exclamation-triangle me-2"></i>Menunggu Keputusan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-sekolah.izin.setujui', $leaveRequest) }}" method="POST" class="mb-2">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="admin_note" class="form-control" rows="2" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui permohonan ini?')"><i class="bi bi-check-circle me-1"></i>Setujui</button>
                </form>
                <form action="{{ route('kepala-sekolah.izin.tolak', $leaveRequest) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="admin_note" value="">
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Tolak permohonan ini?')"><i class="bi bi-x-circle me-1"></i>Tolak</button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="bi bi-{{ $leaveRequest->status === 'approved' ? 'check-circle text-success' : 'x-circle text-danger' }}" style="font-size:2.5rem;"></i>
                <h6 class="fw-bold mt-2">{{ $leaveRequest->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</h6>
                <p class="text-muted mb-0" style="font-size:.82rem;">Permohonan telah {{ $leaveRequest->status === 'approved' ? 'disetujui' : 'ditolak' }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
