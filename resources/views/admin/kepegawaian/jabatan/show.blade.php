@extends('peran.admin.app')
@section('judul', 'Detail Riwayat Jabatan')

@section('konten')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Detail Riwayat Jabatan</h5>
        <p class="text-muted mb-0" style="font-size:.82rem;">{{ $jabatan->nama_jabatan }} — {{ $jabatan->pengguna->nama ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.kepegawaian.jabatan.edit', $jabatan) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('admin.kepegawaian.jabatan.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Pegawai</span>
                <span class="fw-medium">{{ $jabatan->pengguna->nama ?? '-' }}</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Nama Jabatan</span>
                <span class="fw-medium">{{ $jabatan->nama_jabatan }}</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Unit Kerja</span>
                <span class="fw-medium">{{ $jabatan->unit_kerja ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted d-block mb-1">TMT Jabatan</span>
                <span class="fw-medium">{{ $jabatan->tmt_jabatan->format('d M Y') }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted d-block mb-1">TMT Selesai</span>
                <span class="fw-medium">{{ $jabatan->tmt_selesai ? $jabatan->tmt_selesai->format('d M Y') : 'Masih Aktif' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Nomor SK</span>
                <span class="fw-medium">{{ $jabatan->nomor_sk ?? '-' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Tanggal SK</span>
                <span class="fw-medium">{{ $jabatan->tanggal_sk ? $jabatan->tanggal_sk->format('d M Y') : '-' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block mb-1">Pejabat Penetap</span>
                <span class="fw-medium">{{ $jabatan->pejabat_penetap ?? '-' }}</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">File SK</span>
                @if($jabatan->file_sk)
                    <a href="{{ Storage::url($jabatan->file_sk) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i>Unduh / Lihat</a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </div>
            <div class="col-md-6">
                <span class="text-muted d-block mb-1">Keterangan</span>
                <span class="fw-medium">{{ $jabatan->keterangan ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
