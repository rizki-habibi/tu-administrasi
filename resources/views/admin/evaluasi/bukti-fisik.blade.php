@extends('layouts.admin')
@section('title', 'Bukti Fisik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Bukti Fisik</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola bukti fisik dokumen akreditasi & evaluasi</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEvidenceModal"><i class="bi bi-plus-lg me-1"></i> Upload Bukti</button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Standar</th><th>File</th><th>Pengunggah</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($evidences as $ev)
                    <tr>
                        <td>{{ $loop->iteration + ($evidences->currentPage()-1)*$evidences->perPage() }}</td>
                        <td class="fw-semibold">{{ $ev->title }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($ev->category ?? '-') }}</span></td>
                        <td>{{ $ev->standar ?? '-' }}</td>
                        <td>
                            @if($ev->file_path)
                            <a href="{{ asset('storage/'.$ev->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download me-1"></i>{{ Str::limit($ev->file_name, 20) }}</a>
                            @else - @endif
                        </td>
                        <td>{{ $ev->uploader->name ?? '-' }}</td>
                        <td>{{ $ev->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.evaluasi.bukti-fisik.destroy', $ev) }}" method="POST">@csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus bukti fisik ini?"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada bukti fisik</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($evidences->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $evidences->withQueryString()->links() }}</div>
    @endif
</div>

<!-- Upload Modal -->
<div class="modal fade" id="addEvidenceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.evaluasi.bukti-fisik.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Upload Bukti Fisik</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Judul <span class="text-danger">*</span></label><input name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="akademik">Akademik</option>
                            <option value="non_akademik">Non Akademik</option>
                            <option value="sarana">Sarana Prasarana</option>
                            <option value="administrasi">Administrasi</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Standar</label>
                        <select name="standar" class="form-select">
                            <option value="">Pilih Standar (opsional)</option>
                            @for($i=1;$i<=8;$i++)<option value="Standar {{ $i }}">Standar {{ $i }}</option>@endfor
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">File <span class="text-danger">*</span></label><input type="file" name="file" class="form-control" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
