@extends('layouts.staff')
@section('title', 'Model Pembelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-mortarboard me-2"></i>Model & Metode Pembelajaran</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Model
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-3">
    @forelse($methods ?? [] as $m)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    @php
                    $typeIcon = match($m->type ?? '') {
                        'model' => 'bi-boxes',
                        'metode' => 'bi-gear',
                        'teknologi' => 'bi-laptop',
                        'pendekatan' => 'bi-signpost-split',
                        default => 'bi-book'
                    };
                    $typeColor = match($m->type ?? '') {
                        'model' => '#6366f1',
                        'metode' => '#10b981',
                        'teknologi' => '#3b82f6',
                        'pendekatan' => '#f59e0b',
                        default => '#64748b'
                    };
                    @endphp
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;background:{{ $typeColor }}15;">
                        <i class="bi {{ $typeIcon }}" style="color:{{ $typeColor }};"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">{{ $m->name }}</h6>
                        <small class="text-muted">{{ ucfirst($m->type ?? '-') }}</small>
                    </div>
                </div>
                <p class="small text-muted mb-2">{{ Str::limit($m->description, 100) }}</p>
                @if($m->subject)
                <span class="badge bg-light text-dark me-1">{{ $m->subject }}</span>
                @endif
                @if($m->class_level)
                <span class="badge bg-light text-dark">Kelas {{ $m->class_level }}</span>
                @endif
            </div>
            <div class="card-footer bg-white small text-muted">
                <i class="bi bi-calendar me-1"></i> {{ $m->created_at->format('d/m/Y') }}
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card"><div class="card-body text-center py-5 text-muted"><i class="bi bi-mortarboard" style="font-size:3rem;"></i><p class="mt-2 mb-0">Belum ada model pembelajaran. Klik "Tambah Model" untuk memulai.</p></div></div>
    </div>
    @endforelse
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staff.evaluasi.learning.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Model Pembelajaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Jenis</label>
                            <select name="type" class="form-select">
                                <option value="model">Model</option>
                                <option value="metode">Metode</option>
                                <option value="teknologi">Teknologi</option>
                                <option value="pendekatan">Pendekatan</option>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Mata Pelajaran</label><input type="text" name="subject" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Kelas</label><input type="text" name="class_level" class="form-control" placeholder="X, XI, XII"></div>
                    <div class="mb-3"><label class="form-label">Deskripsi <span class="text-danger">*</span></label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Alat / Teknologi</label><input type="text" name="tools_used" class="form-control" placeholder="Google Classroom, Quizziz, dll"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
