@extends('staf.tata-letak.app')
@section('judul', 'Kurikulum & Dokumen')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-book me-2"></i>Kurikulum & Dokumen</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-upload me-1"></i> Unggah Dokumen
    </button>
</div>

{{-- Search & Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari dokumen..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="rpp" {{ request('jenis')=='rpp'?'selected':'' }}>RPP/Modul Ajar</option>
                    <option value="silabus" {{ request('jenis')=='silabus'?'selected':'' }}>Silabus/ATP</option>
                    <option value="jadwal" {{ request('jenis')=='jadwal'?'selected':'' }}>Jadwal Pelajaran</option>
                    <option value="kalender" {{ request('jenis')=='kalender'?'selected':'' }}>Kalender Pendidikan</option>
                    <option value="lainnya" {{ request('jenis')=='lainnya'?'selected':'' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>
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
                    <th>Judul Dokumen</th>
                    <th>Jenis</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents ?? [] as $i => $doc)
                <tr>
                    <td>{{ ($documents instanceof \Illuminate\Pagination\LengthAwarePaginator ? $documents->firstItem() + $i : $i + 1) }}</td>
                    <td>
                        <div class="fw-semibold">{{ $doc->judul }}</div>
                        <small class="text-muted">{{ Str::limit($doc->deskripsi, 50) }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ ucfirst($doc->jenis ?? '-') }}</span></td>
                    <td>{{ $doc->mata_pelajaran ?? '-' }}</td>
                    <td>
                        @if($doc->status == 'active')
                        <span class="badge bg-success">Aktif</span>
                        @elseif($doc->status == 'archived')
                        <span class="badge bg-warning text-dark">Diarsipkan</span>
                        @else
                        <span class="badge bg-secondary">Draf</span>
                        @endif
                    </td>
                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('staf.kurikulum.show', $doc->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada dokumen kurikulum</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documents ?? false)
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $documents->count() }} dari {{ $documents->total() }} dokumen</small>
        {{ $documents->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staf.kurikulum.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Unggah Dokumen Kurikulum</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select">
                                <option value="rpp">RPP/Modul Ajar</option>
                                <option value="silabus">Silabus/ATP</option>
                                <option value="jadwal">Jadwal</option>
                                <option value="kalender">Kalender</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" required>
                    </div>
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
