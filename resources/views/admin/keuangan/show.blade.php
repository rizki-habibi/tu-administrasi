@extends('admin.tata-letak.app')
@section('judul', 'Detail Transaksi Keuangan')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.keuangan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Transaksi</h4>
    <div class="ms-auto">
        @if($keuangan->status == 'draft')
        <form action="{{ route('admin.keuangan.verifikasi', $keuangan->id) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle me-1"></i> Verifikasi</button>
        </form>
        @endif
        <form action="{{ route('admin.keuangan.destroy', $keuangan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Hapus</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge {{ $keuangan->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }} me-2" style="font-size:0.85rem;">
                        {{ ucfirst($keuangan->jenis) }}
                    </span>
                    @if($keuangan->status == 'approved')
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Disetujui</span>
                    @elseif($keuangan->status == 'verified')
                    <span class="badge bg-info"><i class="bi bi-check-circle me-1"></i> Terverifikasi</span>
                    @else
                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Draft</span>
                    @endif
                </div>
                <h5 class="fw-bold mb-3">{{ $keuangan->uraian }}</h5>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="180">Nominal</td><td class="fw-bold fs-5">Rp {{ number_format($keuangan->jumlah, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Kategori</td><td>{{ ucfirst($keuangan->kategori ?? '-') }}</td></tr>
                    <tr><td class="text-muted">Tanggal Transaksi</td><td>{{ $keuangan->tanggal ? \Carbon\Carbon::parse($keuangan->tanggal)->translatedFormat('d F Y') : '-' }}</td></tr>
                    <tr><td class="text-muted">Kode Transaksi</td><td><code>{{ $keuangan->kode_transaksi ?? '-' }}</code></td></tr>
                    <tr><td class="text-muted">Dicatat Oleh</td><td>{{ $keuangan->creator->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal Dibuat</td><td>{{ $keuangan->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text me-1"></i> Bukti / Lampiran</h6>
            </div>
            <div class="card-body">
                @if($keuangan->bukti_path)
                <p class="small text-muted mb-2">{{ $keuangan->bukti_nama }}</p>
                <a href="{{ Storage::url($keuangan->bukti_path) }}" class="btn btn-outline-primary w-100" target="_blank">
                    <i class="bi bi-download me-1"></i> Unduh Bukti
                </a>
                @else
                <p class="text-muted text-center mb-0">Tidak ada lampiran</p>
                @endif
            </div>
        </div>

        @if($keuangan->keterangan)
        <div class="card mt-3">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-chat-left-text me-1"></i> Keterangan</h6></div>
            <div class="card-body"><p class="mb-0">{{ $keuangan->keterangan }}</p></div>
        </div>
        @endif
    </div>
</div>
@endsection
