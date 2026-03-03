@extends('layouts.staff')
@section('title', 'Agenda & Kegiatan')

@section('content')
<h4 class="mb-4"><i class="bi bi-calendar-event"></i> Agenda & Kegiatan</h4>

{{-- Upcoming Events Highlight --}}
@if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
<div class="mb-4">
    <h6 class="text-muted mb-3"><i class="bi bi-star"></i> Agenda Mendatang</h6>
    <div class="row g-3">
        @foreach($upcomingEvents as $upcoming)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="mb-1">{{ $upcoming->title }}</h6>
                    <p class="text-muted small mb-1">
                        <i class="bi bi-calendar3"></i> {{ $upcoming->event_date->format('d F Y') }}
                    </p>
                    <p class="text-muted small mb-1">
                        <i class="bi bi-clock"></i> {{ $upcoming->start_time }} - {{ $upcoming->end_time ?? 'Selesai' }}
                    </p>
                    @if($upcoming->location)
                    <p class="text-muted small mb-0">
                        <i class="bi bi-geo-alt"></i> {{ $upcoming->location }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tipe</label>
                <select name="type" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['rapat','kegiatan','upacara','pelatihan','lainnya'] as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($events as $event)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $event->title }}</h6>
                    @php
                        $statusColors = ['upcoming'=>'primary','ongoing'=>'success','completed'=>'secondary','cancelled'=>'danger'];
                        $typeColors = ['rapat'=>'info','kegiatan'=>'success','upacara'=>'warning','pelatihan'=>'primary','lainnya'=>'secondary'];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">{{ ucfirst($event->status) }}</span>
                </div>
                <span class="badge bg-{{ $typeColors[$event->type] ?? 'secondary' }} mb-2">{{ ucfirst($event->type) }}</span>
                <div class="text-muted small">
                    <p class="mb-1"><i class="bi bi-calendar3"></i> {{ $event->event_date->format('d F Y') }}</p>
                    <p class="mb-1"><i class="bi bi-clock"></i> {{ $event->start_time }} - {{ $event->end_time ?? 'Selesai' }}</p>
                    @if($event->location)
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $event->location }}</p>
                    @endif
                </div>
                <p class="text-muted small mt-2 mb-0">{{ Str::limit($event->description, 100) }}</p>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="{{ route('staff.event.show', $event) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x" style="font-size:3rem;"></i>
            <p class="mt-2">Belum ada agenda</p>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-3">{{ $events->links() }}</div>
@endsection
