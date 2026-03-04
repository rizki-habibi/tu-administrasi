@extends('staf.tata-letak.app')
@section('judul', 'Bukti Fisik')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-file-earmark-check me-2"></i>Bukti Fisik Pembelajaran</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-upload me-1"></i> Unggah Bukti
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>File</th>
                    <th>Tanggal Unggah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evidences ?? [] as $i => $e)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ $e->judul }}</div>
                        <small class="text-muted">{{ Str::limit($e->deskripsi, 50) }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $e->kategori ?? '-')) }}</span></td>
                    <td>
                        @if($e->path_file)
                        <a href="{{ asset('storage/'.$e->path_file) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i> Unduh</a>
                        @else
                        <small class="text-muted">-</small>
                        @endif
                    </td>
                    <td>{{ $e->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-file-earmark-check" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada bukti fisik. Klik "Unggah Bukti" untuk memulai.</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staf.evaluasi.bukti-fisik.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Unggah Bukti Fisik</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Judul <span class="text-danger">*</span></label><input type="text" name="judul" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="pembelajaran">Pembelajaran</option>
                            <option value="administrasi">Administrasi</option>
                            <option value="kegiatan">Kegiatan</option>
                            <option value="pengembangan_diri">Pengembangan Diri</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">File <span class="text-danger">*</span></label><input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
