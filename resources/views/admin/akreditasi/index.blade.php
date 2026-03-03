@extends('layouts.admin')
@section('title', 'Akreditasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Dokumen Akreditasi</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola dokumen 8 Standar Nasional Pendidikan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.akreditasi.eds') }}" class="btn btn-outline-primary"><i class="bi bi-clipboard-data me-1"></i> EDS</a>
        <a href="{{ route('admin.akreditasi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Dokumen</a>
    </div>
</div>

<!-- 8 Standar Cards -->
<div class="row g-3 mb-4">
    @php
    $standards = [
        ['standar_isi', 'Standar Isi', 'bi-book', '#6366f1'],
        ['standar_proses', 'Standar Proses', 'bi-gear', '#8b5cf6'],
        ['standar_kompetensi_lulusan', 'Standar Kompetensi Lulusan', 'bi-mortarboard', '#06b6d4'],
        ['standar_pendidik', 'Standar Pendidik', 'bi-person-workspace', '#10b981'],
        ['standar_sarpras', 'Standar Sarpras', 'bi-building', '#f59e0b'],
        ['standar_pengelolaan', 'Standar Pengelolaan', 'bi-diagram-3', '#ec4899'],
        ['standar_pembiayaan', 'Standar Pembiayaan', 'bi-cash-coin', '#ef4444'],
        ['standar_penilaian', 'Standar Penilaian', 'bi-clipboard-check', '#0ea5e9'],
    ];
    @endphp
    @foreach($standards as $std)
    <div class="col-6 col-lg-3">
        <div class="card text-center py-3 px-2 h-100">
            <i class="bi {{ $std[2] }}" style="font-size:1.5rem;color:{{ $std[3] }};"></i>
            <small class="fw-semibold mt-2" style="font-size:.72rem;line-height:1.2;">{{ $std[1] }}</small>
            @php $count = ($documents ?? collect())->where('standar', $std[0])->count(); @endphp
            <span class="badge bg-light text-dark mt-1" style="font-size:.7rem;">{{ $count }} dokumen</span>
        </div>
    </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="standar" class="form-select">
                    <option value="">Semua Standar</option>
                    @php $standarOpts = ['standar_isi'=>'Standar Isi','standar_proses'=>'Standar Proses','standar_kompetensi_lulusan'=>'Standar Kompetensi Lulusan','standar_pendidik'=>'Standar Pendidik','standar_sarpras'=>'Standar Sarpras','standar_pengelolaan'=>'Standar Pengelolaan','standar_pembiayaan'=>'Standar Pembiayaan','standar_penilaian'=>'Standar Penilaian']; @endphp
                    @foreach($standarOpts as $val => $label)
                    <option value="{{ $val }}" {{ request('standar')==$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="belum_lengkap" {{ request('status')=='belum_lengkap'?'selected':'' }}>Belum Lengkap</option>
                    <option value="lengkap" {{ request('status')=='lengkap'?'selected':'' }}>Lengkap</option>
                    <option value="diverifikasi" {{ request('status')=='diverifikasi'?'selected':'' }}>Diverifikasi</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Komponen</th><th>Standar</th><th>Status</th><th>File</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($documents as $d)
                    <tr>
                        <td>{{ $loop->iteration + ($documents->currentPage()-1)*$documents->perPage() }}</td>
                        <td class="fw-semibold">{{ $d->komponen }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucwords(str_replace('_', ' ', $d->standar)) }}</span></td>
                        <td>
                            @if($d->status=='diverifikasi')<span class="badge bg-success">Diverifikasi</span>
                            @elseif($d->status=='lengkap')<span class="badge bg-info">Lengkap</span>
                            @else<span class="badge bg-warning text-dark">Belum Lengkap</span>@endif
                        </td>
                        <td>@if($d->file_path)<a href="{{ asset('storage/'.$d->file_path) }}" target="_blank"><i class="bi bi-file-earmark-arrow-down text-primary"></i></a>@else - @endif</td>
                        <td>{{ $d->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.akreditasi.show', $d) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('admin.akreditasi.destroy', $d) }}" method="POST">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Hapus?"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada dokumen</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($documents->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $documents->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
