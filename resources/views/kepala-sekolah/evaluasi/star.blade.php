@extends('peran.kepala-sekolah.app')
@section('judul', 'Analisis STAR')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Analisis STAR</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Situation, Task, Action, Result</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" style="width:200px;" placeholder="Cari judul..." value="{{ request('search') }}">
            <select name="kategori" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Kategori</option>
                @foreach(['akademik','non_akademik','manajerial','pengembangan'] as $k)
                    <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$k)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Filter</button>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($analyses as $analysis)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0" style="font-size:.9rem;">{{ $analysis->judul }}</h6>
                    <span class="badge bg-warning bg-opacity-10 text-warning">{{ ucfirst(str_replace('_',' ',$analysis->kategori ?? '-')) }}</span>
                </div>
                <div class="mb-2" style="font-size:.82rem;">
                    <strong class="text-muted">Oleh:</strong> {{ $analysis->creator->nama ?? '-' }}
                </div>
                <div class="row g-2" style="font-size:.8rem;">
                    <div class="col-6">
                        <div class="p-2 rounded-2" style="background:#fef3c7;">
                            <strong class="d-block text-warning" style="font-size:.72rem;">SITUASI</strong>
                            {{ \Str::limit($analysis->situasi, 60) }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2" style="background:#dbeafe;">
                            <strong class="d-block text-primary" style="font-size:.72rem;">TUGAS</strong>
                            {{ \Str::limit($analysis->tugas, 60) }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2" style="background:#d1fae5;">
                            <strong class="d-block text-success" style="font-size:.72rem;">TINDAKAN</strong>
                            {{ \Str::limit($analysis->aksi, 60) }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2" style="background:#fce7f3;">
                            <strong class="d-block text-danger" style="font-size:.72rem;">HASIL</strong>
                            {{ \Str::limit($analysis->hasil, 60) }}
                        </div>
                    </div>
                </div>
                @if($analysis->path_file)
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $analysis->path_file) }}" target="_blank" class="btn btn-sm btn-outline-warning"><i class="bi bi-download me-1"></i>Dokumen</a>
                </div>
                @endif
                <div class="text-muted mt-2" style="font-size:.72rem;">{{ $analysis->created_at->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card"><div class="card-body text-center py-4 text-muted">Belum ada data analisis STAR</div></div>
    </div>
    @endforelse
</div>

@if($analyses instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $analyses->withQueryString()->links() }}</div>
@endif
@endsection
