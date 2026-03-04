@extends('peran.staf.app')
@section('judul', 'Metode STAR')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-diagram-3 me-2"></i>Analisis Metode STAR</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#starModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Analisis
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- STAR Framework Info --}}
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-0 h-100" style="background:#6366f115;"><div class="card-body text-center"><span class="badge bg-primary mb-2" style="font-size:1.2rem;">S</span><h6 class="fw-bold mb-1">Situasi</h6><small class="text-muted">Situasi/konteks</small></div></div></div>
    <div class="col-md-3"><div class="card border-0 h-100" style="background:#f59e0b15;"><div class="card-body text-center"><span class="badge bg-warning text-dark mb-2" style="font-size:1.2rem;">T</span><h6 class="fw-bold mb-1">Tugas</h6><small class="text-muted">Tugas/tantangan</small></div></div></div>
    <div class="col-md-3"><div class="card border-0 h-100" style="background:#10b98115;"><div class="card-body text-center"><span class="badge bg-success mb-2" style="font-size:1.2rem;">A</span><h6 class="fw-bold mb-1">Tindakan</h6><small class="text-muted">Tindakan diambil</small></div></div></div>
    <div class="col-md-3"><div class="card border-0 h-100" style="background:#ef444415;"><div class="card-body text-center"><span class="badge bg-danger mb-2" style="font-size:1.2rem;">R</span><h6 class="fw-bold mb-1">Hasil</h6><small class="text-muted">Hasil/dampak</small></div></div></div>
</div>

{{-- Analyses List --}}
@forelse($analyses ?? [] as $a)
<div class="card mb-3">
    <div class="card-header bg-transparent d-flex justify-content-between">
        <h6 class="mb-0 fw-semibold">{{ $a->judul }}</h6>
        <small class="text-muted">{{ $a->created_at->translatedFormat('d F Y') }}</small>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3"><strong class="text-primary">Situasi:</strong><p class="mb-0 small mt-1">{{ Str::limit($a->situasi, 100) }}</p></div>
            <div class="col-md-3"><strong class="text-warning">Tugas:</strong><p class="mb-0 small mt-1">{{ Str::limit($a->tugas, 100) }}</p></div>
            <div class="col-md-3"><strong class="text-success">Tindakan:</strong><p class="mb-0 small mt-1">{{ Str::limit($a->aksi, 100) }}</p></div>
            <div class="col-md-3"><strong class="text-danger">Hasil:</strong><p class="mb-0 small mt-1">{{ Str::limit($a->hasil, 100) }}</p></div>
        </div>
    </div>
</div>
@empty
<div class="card"><div class="card-body text-center py-5 text-muted"><i class="bi bi-diagram-3" style="font-size:3rem;"></i><p class="mt-2 mb-0">Belum ada analisis STAR. Klik "Tambah Analisis" untuk memulai.</p></div></div>
@endforelse

{{-- Add STAR Modal --}}
<div class="modal fade" id="starModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('staf.evaluasi.star.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Analisis STAR</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Judul <span class="text-danger">*</span></label><input type="text" name="judul" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="pembelajaran">Pembelajaran</option>
                            <option value="administrasi">Administrasi</option>
                            <option value="manajemen">Manajemen</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label"><span class="badge bg-primary me-1">S</span> Situation <span class="text-danger">*</span></label><textarea name="situation" class="form-control" rows="2" required placeholder="Jelaskan situasi atau konteks..."></textarea></div>
                    <div class="mb-3"><label class="form-label"><span class="badge bg-warning text-dark me-1">T</span> Task <span class="text-danger">*</span></label><textarea name="task" class="form-control" rows="2" required placeholder="Tugas atau tantangan yang dihadapi..."></textarea></div>
                    <div class="mb-3"><label class="form-label"><span class="badge bg-success me-1">A</span> Action <span class="text-danger">*</span></label><textarea name="action" class="form-control" rows="2" required placeholder="Tindakan yang diambil..."></textarea></div>
                    <div class="mb-3"><label class="form-label"><span class="badge bg-danger me-1">R</span> Result <span class="text-danger">*</span></label><textarea name="result" class="form-control" rows="2" required placeholder="Hasil atau dampak..."></textarea></div>
                    <div class="mb-3"><label class="form-label">Refleksi</label><textarea name="reflection" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Tindak Lanjut</label><textarea name="tindak_lanjut" class="form-control" rows="2"></textarea></div>
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
