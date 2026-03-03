@extends('layouts.admin')
@section('title', 'Detail Laporan')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5>{{ $report->title }}</h5>
                <div class="d-flex gap-2 mb-3">
                    @php $pColors=['rendah'=>'success','sedang'=>'warning','tinggi'=>'danger']; $sColors=['draft'=>'secondary','submitted'=>'primary','reviewed'=>'warning','completed'=>'success']; @endphp
                    <span class="badge bg-{{ $pColors[$report->priority] ?? 'secondary' }}">{{ ucfirst($report->priority) }}</span>
                    <span class="badge bg-{{ $sColors[$report->status] ?? 'secondary' }}">{{ ucfirst($report->status) }}</span>
                    <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$report->category)) }}</span>
                </div>
                <p>{{ $report->description }}</p>
                @if($report->attachment)
                    <a href="{{ asset('storage/' . $report->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-paperclip"></i> Lampiran</a>
                @endif
                <hr>
                <small class="text-muted">Dibuat oleh {{ $report->user->name ?? '-' }} pada {{ $report->created_at->format('d M Y H:i') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Ubah Status</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.report.update-status', $report) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach(['draft','submitted','reviewed','completed'] as $s)
                            <option value="{{ $s }}" {{ $report->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="mt-3"><a href="{{ route('admin.report.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a></div>
@endsection
