@extends('peran.staf.app')
@section('judul', 'Detail Dokumen Kepegawaian')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Detail Dokumen Kepegawaian</h4>
    <div>
        <a href="{{ route('staf.dok-kepegawaian.edit', $dokumen) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('staf.dok-kepegawaian.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tr><th width="200">Pegawai</th><td>{{ $dokumen->pengguna->nama ?? '-' }}</td></tr>
            <tr><th>Judul</th><td>{{ $dokumen->judul }}</td></tr>
            <tr>
                <th>Kategori</th>
                <td><span class="badge bg-info text-dark">{{ \App\Models\DokumenKepegawaian::KATEGORI[$dokumen->kategori] ?? $dokumen->kategori }}</span></td>
            </tr>
            <tr><th>Nomor Dokumen</th><td>{{ $dokumen->nomor_dokumen ?? '-' }}</td></tr>
            <tr><th>Tanggal Dokumen</th><td>{{ $dokumen->tanggal_dokumen ? $dokumen->tanggal_dokumen->format('d F Y') : '-' }}</td></tr>
            <tr><th>Keterangan</th><td>{{ $dokumen->keterangan ?? '-' }}</td></tr>
            <tr>
                <th>File</th>
                <td>
                    @if($dokumen->file_path)
                        <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> Unduh ({{ number_format(($dokumen->file_size ?? 0) / 1024, 1) }} KB)
                        </a>
                        <br><small class="text-muted">Tipe: {{ $dokumen->file_type ?? '-' }}</small>
                    @else
                        <span class="text-muted">Tidak ada file</span>
                    @endif
                </td>
            </tr>
            <tr><th>Dibuat</th><td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><th>Diperbarui</th><td>{{ $dokumen->updated_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>
</div>
@endsection
