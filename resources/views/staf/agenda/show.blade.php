@extends('staf.tata-letak.app')
@section('judul', 'Detail Agenda')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-eye"></i> Detail Agenda</h4>
    <a href="{{ route('staf.agenda.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @php
            $statusColors = ['upcoming'=>'primary','ongoing'=>'success','completed'=>'secondary','cancelled'=>'danger'];
            $typeColors = ['rapat'=>'info','kegiatan'=>'success','upacara'=>'warning','pelatihan'=>'primary','lainnya'=>'secondary'];
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">{{ $event->judul }}</h5>
            <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }} fs-6">{{ ucfirst($event->status) }}</span>
        </div>

        <span class="badge bg-{{ $typeColors[$event->jenis] ?? 'secondary' }} mb-3">{{ ucfirst($event->jenis) }}</span>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="bg-light rounded p-3">
                    <small class="text-muted d-block"><i class="bi bi-calendar3"></i> Tanggal</small>
                    <strong>{{ $event->tanggal_acara->format('d F Y') }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded p-3">
                    <small class="text-muted d-block"><i class="bi bi-clock"></i> Waktu</small>
                    <strong>{{ $event->waktu_mulai }} - {{ $event->waktu_selesai ?? 'Selesai' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded p-3">
                    <small class="text-muted d-block"><i class="bi bi-geo-alt"></i> Lokasi</small>
                    <strong>{{ $event->lokasi ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <h6 class="fw-bold">Deskripsi</h6>
        <div class="bg-light p-3 rounded">{!! nl2br(e($event->deskripsi ?? 'Tidak ada deskripsi.')) !!}</div>

        <div class="text-muted small mt-3">
            Dibuat oleh: <strong>{{ $event->creator->nama ?? '-' }}</strong> pada {{ $event->created_at->format('d F Y H:i') }}
        </div>
    </div>
</div>
@endsection
