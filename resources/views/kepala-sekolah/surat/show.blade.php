@extends('peran.kepala-sekolah.app')
@section('judul', 'Detail Surat')

@section('konten')
<div class="mb-4">
    <a href="{{ route('kepala-sekolah.surat.index') }}" class="text-decoration-none text-warning" style="font-size:.85rem;"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;"><i class="bi bi-envelope-open text-warning me-2"></i>Detail Surat</h6>
                {!! $surat->status_badge !!}
            </div>
            <div class="card-body">
                <div class="row g-3" style="font-size:.85rem;">
                    <div class="col-md-6"><strong class="text-muted d-block">Nomor Surat</strong>{{ $surat->nomor_surat ?? '-' }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Jenis</strong>
                        @if($surat->jenis === 'masuk')
                            <span class="badge bg-success bg-opacity-10 text-success">Surat Masuk</span>
                        @else
                            <span class="badge bg-primary bg-opacity-10 text-primary">Surat Keluar</span>
                        @endif
                    </div>
                    <div class="col-md-6"><strong class="text-muted d-block">Kategori</strong>{{ ucfirst($surat->kategori ?? '-') }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Sifat</strong>{{ ucfirst($surat->sifat ?? '-') }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Tanggal Surat</strong>{{ $surat->tanggal_surat ? $surat->tanggal_surat->translatedFormat('d F Y') : '-' }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Tanggal Terima</strong>{{ $surat->tanggal_terima ? $surat->tanggal_terima->translatedFormat('d F Y') : '-' }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Tujuan</strong>{{ $surat->tujuan ?? '-' }}</div>
                    <div class="col-md-6"><strong class="text-muted d-block">Asal</strong>{{ $surat->asal ?? '-' }}</div>
                    <div class="col-12"><strong class="text-muted d-block">Perihal</strong>{{ $surat->perihal ?? '-' }}</div>
                    <div class="col-12"><strong class="text-muted d-block">Isi</strong>
                        <div class="p-3 rounded-3" style="background:#faf5f0;font-size:.82rem;">{!! nl2br(e($surat->isi ?? '-')) !!}</div>
                    </div>
                    @if($surat->catatan)
                    <div class="col-12"><strong class="text-muted d-block">Catatan</strong>{{ $surat->catatan }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;">Informasi</h6>
            </div>
            <div class="card-body" style="font-size:.85rem;">
                <div class="mb-3"><strong class="text-muted d-block">Dibuat oleh</strong>{{ $surat->creator->nama ?? '-' }}</div>
                @if($surat->approver)
                <div class="mb-3"><strong class="text-muted d-block">Disetujui oleh</strong>{{ $surat->approver->nama }}</div>
                @endif
                <div><strong class="text-muted d-block">Tanggal Input</strong>{{ $surat->created_at->translatedFormat('d F Y H:i') }}</div>
            </div>
        </div>

        @if($surat->path_file)
        <div class="card">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0" style="font-size:.9rem;">Lampiran</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#fef3c7;">
                    <i class="bi bi-file-earmark-text" style="font-size:1.5rem;color:#d97706;"></i>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold" style="font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $surat->nama_file ?? 'Dokumen' }}</div>
                    </div>
                    <a href="{{ asset('storage/' . $surat->path_file) }}" target="_blank" class="btn btn-sm btn-outline-warning"><i class="bi bi-download"></i></a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
