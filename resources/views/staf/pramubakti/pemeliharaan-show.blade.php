@extends('peran.staf.app')
@section('judul', 'Detail Laporan Pemeliharaan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Detail Laporan</h4>
    <div>
        <a href="{{ route('staf.pemeliharaan.edit', $laporan) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('staf.pemeliharaan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tr><th width="180">Judul</th><td>{{ $laporan->judul }}</td></tr>
            <tr>
                <th>Prioritas</th>
                <td>
                    @php $pBadge = match($laporan->prioritas) { 'tinggi' => 'danger', 'sedang' => 'warning', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $pBadge }}">{{ ucfirst($laporan->prioritas) }}</span>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-{{ $laporan->status_badge }}">
                        {{ match($laporan->status) { 'submitted' => 'Diajukan', 'reviewed' => 'Ditinjau', 'completed' => 'Selesai', default => ucfirst($laporan->status) } }}
                    </span>
                </td>
            </tr>
            <tr><th>Deskripsi</th><td>{!! nl2br(e($laporan->deskripsi)) !!}</td></tr>
            <tr>
                <th>Lampiran</th>
                <td>
                    @if($laporan->lampiran)
                        <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Unduh</a>
                    @else
                        <span class="text-muted">Tidak ada lampiran</span>
                    @endif
                </td>
            </tr>
            <tr><th>Dibuat</th><td>{{ $laporan->created_at->format('d F Y H:i') }}</td></tr>
            <tr><th>Diperbarui</th><td>{{ $laporan->updated_at->format('d F Y H:i') }}</td></tr>
        </table>
    </div>
</div>
@endsection
