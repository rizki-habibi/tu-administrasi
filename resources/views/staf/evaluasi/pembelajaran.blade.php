@extends('staf.tata-letak.app')
@section('judul', 'Model Pembelajaran')

@section('konten')
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
                    $typeIcon = match($m->jenis ?? '') {
                        'model_pembelajaran' => 'bi-boxes',
                        'teknologi_pembelajaran' => 'bi-laptop',
                        'media_pembelajaran' => 'bi-easel',
                        default => 'bi-book'
                    };
                    $typeColor = match($m->jenis ?? '') {
                        'model_pembelajaran' => '#6366f1',
                        'teknologi_pembelajaran' => '#3b82f6',
                        'media_pembelajaran' => '#10b981',
                        default => '#64748b'
                    };
                    @endphp
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;background:{{ $typeColor }}15;">
                        <i class="bi {{ $typeIcon }}" style="color:{{ $typeColor }};"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">{{ $m->nama_metode }}</h6>
                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $m->jenis ?? '-')) }}</small>
                    </div>
                </div>
                <p class="small text-muted mb-2">{{ Str::limit($m->deskripsi, 100) }}</p>
                @if($m->mata_pelajaran)
                <span class="badge bg-light text-dark me-1">{{ $m->mata_pelajaran }}</span>
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
            <form action="{{ route('staf.evaluasi.pembelajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Model Pembelajaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select">
                                <option value="model_pembelajaran">Model Pembelajaran</option>
                                <option value="teknologi_pembelajaran">Teknologi Pembelajaran</option>
                                <option value="media_pembelajaran">Media Pembelajaran</option>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Mata Pelajaran</label><input type="text" name="mata_pelajaran" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Deskripsi <span class="text-danger">*</span></label><textarea name="deskripsi" class="form-control" rows="3" required></textarea></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label">Kelebihan</label><textarea name="benefits" class="form-control" rows="2"></textarea></div>
                        <div class="col-6"><label class="form-label">Kekurangan</label><textarea name="challenges" class="form-control" rows="2"></textarea></div>
                    </div>
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
