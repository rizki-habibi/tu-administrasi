@extends('peran.staf.app')
@section('judul', 'Detail Laporan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-eye"></i> Detail Laporan</h4>
    <div>
        @if($report->status == 'draft')
            <a href="{{ route('staf.laporan.edit', $report) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Ubah</a>
            <form action="{{ route('staf.laporan.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
            </form>
        @endif
        <a href="{{ route('staf.laporan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Judul</div>
            <div class="col-md-9">{{ $report->judul }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Kategori</div>
            <div class="col-md-9"><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$report->kategori)) }}</span></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Prioritas</div>
            <div class="col-md-9">
                @php $pc = ['rendah'=>'success','sedang'=>'warning','tinggi'=>'danger']; @endphp
                <span class="badge bg-{{ $pc[$report->prioritas] ?? 'secondary' }}">{{ ucfirst($report->prioritas) }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Status</div>
            <div class="col-md-9">
                @php $sc = ['draft'=>'secondary','submitted'=>'primary','reviewed'=>'info','completed'=>'success']; @endphp
                <span class="badge bg-{{ $sc[$report->status] ?? 'secondary' }}">{{ ucfirst($report->status) }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Tanggal Dibuat</div>
            <div class="col-md-9">{{ $report->created_at->format('d F Y H:i') }}</div>
        </div>
        @if($report->lampiran)
        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Lampiran</div>
            <div class="col-md-9"><a href="{{ asset('storage/' . $report->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download</a></div>
        </div>
        @endif
        <hr>
        <h6 class="fw-bold mb-3">Deskripsi</h6>
        <div class="bg-light p-3 rounded">{!! nl2br(e($report->deskripsi)) !!}</div>
    </div>
</div>
@endsection
