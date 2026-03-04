@extends('admin.tata-letak.app')
@section('judul', 'Detail Dokumen Akreditasi')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Dokumen Akreditasi</h4>
    <div class="ms-auto">
        <form action="{{ route('admin.akreditasi.destroy', $akreditasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Hapus</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                @php
                $standarLabels = [
                    'standar_isi' => 'Standar Isi',
                    'standar_proses' => 'Standar Proses',
                    'standar_kompetensi_lulusan' => 'Standar Kompetensi Lulusan',
                    'standar_pendidik' => 'Standar Pendidik & Tenaga Kependidikan',
                    'standar_sarpras' => 'Standar Sarana & Prasarana',
                    'standar_pengelolaan' => 'Standar Pengelolaan',
                    'standar_pembiayaan' => 'Standar Pembiayaan',
                    'standar_penilaian' => 'Standar Penilaian',
                ];
                @endphp
                <div class="mb-3">
                    <span class="badge bg-primary" style="font-size:0.85rem;">{{ ucwords(str_replace('_', ' ', $akreditasi->standar)) }}</span>
                    <span class="text-muted ms-2">{{ $standarLabels[$akreditasi->standar] ?? '' }}</span>
                </div>
                <h5 class="fw-bold mb-3">{{ $akreditasi->komponen }}</h5>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="200">Indikator</td><td>{{ $akreditasi->indikator ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        @if($akreditasi->status=='diverifikasi')
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Diverifikasi</span>
                        @elseif($akreditasi->status=='lengkap')
                        <span class="badge bg-info"><i class="bi bi-check me-1"></i> Lengkap</span>
                        @else
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Belum Lengkap</span>
                        @endif
                    </td></tr>
                    <tr><td class="text-muted">Diunggah Oleh</td><td>{{ $akreditasi->uploader->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal Unggah</td><td>{{ $akreditasi->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                </table>
                @if($akreditasi->deskripsi)
                <hr>
                <h6 class="fw-semibold">Deskripsi</h6>
                <p>{{ $akreditasi->deskripsi }}</p>
                @endif
                @if($akreditasi->catatan)
                <h6 class="fw-semibold">Catatan</h6>
                <p>{{ $akreditasi->catatan }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-file-earmark me-1"></i> File Dokumen</h6>
            </div>
            <div class="card-body">
                @if($akreditasi->path_file)
                <div class="text-center">
                    <i class="bi bi-file-earmark-pdf" style="font-size:3rem; color:#dc3545;"></i>
                    <p class="mt-2 mb-2 text-muted small">{{ $akreditasi->nama_file }}</p>
                    <a href="{{ asset('storage/'.$akreditasi->path_file) }}" class="btn btn-outline-primary w-100" target="_blank">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>
                </div>
                @else
                <p class="text-muted text-center mb-0">Tidak ada file</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-info-circle me-1"></i> Info Standar</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 small text-muted">{{ $standarLabels[$akreditasi->standar] ?? ucwords(str_replace('_', ' ', $akreditasi->standar)) }} merupakan salah satu dari 8 Standar Nasional Pendidikan yang dinilai dalam proses akreditasi sekolah.</p>
            </div>
        </div>
    </div>
</div>
@endsection
