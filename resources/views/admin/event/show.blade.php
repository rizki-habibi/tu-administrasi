@extends('layouts.admin')
@section('title', 'Detail Event')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.event.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

@php
    $typeColors = ['rapat'=>'primary','kegiatan'=>'success','upacara'=>'info','pelatihan'=>'warning','lainnya'=>'secondary'];
    $statusColors = ['upcoming'=>'info','ongoing'=>'primary','completed'=>'success','cancelled'=>'danger'];
@endphp

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-calendar-event"></i> {{ $event->title }}</h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $typeColors[$event->type] ?? 'secondary' }}">{{ ucfirst($event->type) }}</span>
                    <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">{{ ucfirst($event->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-4">
                    <tr>
                        <th width="150"><i class="bi bi-calendar3"></i> Tanggal</th>
                        <td>{{ $event->event_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-clock"></i> Waktu</th>
                        <td>{{ $event->start_time }} - {{ $event->end_time }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-geo-alt"></i> Lokasi</th>
                        <td>{{ $event->location ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-tag"></i> Tipe</th>
                        <td><span class="badge bg-{{ $typeColors[$event->type] ?? 'secondary' }}">{{ ucfirst($event->type) }}</span></td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-flag"></i> Status</th>
                        <td><span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">{{ ucfirst($event->status) }}</span></td>
                    </tr>
                </table>

                @if($event->description)
                <hr>
                <h6>Deskripsi</h6>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($event->description)) !!}
                </div>
                @endif
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="{{ route('admin.event.edit', $event) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                <form action="{{ route('admin.event.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin hapus event ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
