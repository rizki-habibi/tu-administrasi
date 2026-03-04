@extends('peran.kepala-sekolah.app')
@section('judul', 'Bukti Fisik')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Bukti Fisik</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Daftar dokumen bukti fisik</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" style="width:200px;" placeholder="Cari judul..." value="{{ request('search') }}">
            <select name="kategori" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Kategori</option>
                @foreach(['perencanaan','pelaksanaan','evaluasi','pengembangan'] as $k)
                    <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Deskripsi</th><th>Pengunggah</th><th>Ukuran</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($evidences as $i => $ev)
                    <tr>
                        <td>{{ $evidences instanceof \Illuminate\Pagination\LengthAwarePaginator ? $evidences->firstItem() + $i : $i + 1 }}</td>
                        <td class="fw-semibold">{{ $ev->judul }}</td>
                        <td><span class="badge bg-warning bg-opacity-10 text-warning">{{ ucfirst($ev->kategori ?? '-') }}</span></td>
                        <td style="max-width:200px;font-size:.8rem;">{{ \Str::limit($ev->deskripsi, 50) }}</td>
                        <td>{{ $ev->uploader->nama ?? '-' }}</td>
                        <td style="font-size:.8rem;">{{ $ev->file_size_formatted }}</td>
                        <td style="font-size:.8rem;">{{ $ev->created_at->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($ev->path_file)
                                <a href="{{ asset('storage/' . $ev->path_file) }}" target="_blank" class="btn btn-sm btn-outline-warning" title="Unduh"><i class="bi bi-download"></i></a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data bukti fisik</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($evidences instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $evidences->withQueryString()->links() }}</div>
@endif
@endsection
