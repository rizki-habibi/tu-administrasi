@extends('peran.admin.app')
@section('judul', 'Dokumen Kurikulum')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Dokumen Kurikulum</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola RPP, Silabus, Jadwal, Kalender Pendidikan</p>
    </div>
    <a href="{{ route('admin.kurikulum.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Dokumen</a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
        $stats = [
            ['label' => 'RPP / Modul Ajar', 'count' => $documents->where('jenis','rpp')->count(), 'bg' => 'linear-gradient(135deg,#6366f1,#818cf8)', 'icon' => 'bi-file-earmark-text'],
            ['label' => 'Silabus / ATP', 'count' => $documents->where('jenis','silabus')->count(), 'bg' => 'linear-gradient(135deg,#8b5cf6,#a78bfa)', 'icon' => 'bi-file-earmark-ruled'],
            ['label' => 'Jadwal Pelajaran', 'count' => $documents->where('jenis','jadwal')->count(), 'bg' => 'linear-gradient(135deg,#06b6d4,#22d3ee)', 'icon' => 'bi-calendar-week'],
            ['label' => 'Kalender', 'count' => $documents->where('jenis','kalender')->count(), 'bg' => 'linear-gradient(135deg,#10b981,#34d399)', 'icon' => 'bi-calendar-event'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:{{ $s['bg'] }}">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="icon-box"><i class="bi {{ $s['icon'] }}"></i></div></div>
            </div>
            <h3>{{ $s['count'] }}</h3>
            <p>{{ $s['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="rpp" {{ request('jenis')=='rpp'?'selected':'' }}>RPP / Modul Ajar</option>
                    <option value="silabus" {{ request('jenis')=='silabus'?'selected':'' }}>Silabus / ATP</option>
                    <option value="jadwal" {{ request('jenis')=='jadwal'?'selected':'' }}>Jadwal Pelajaran</option>
                    <option value="kalender" {{ request('jenis')=='kalender'?'selected':'' }}>Kalender Pendidikan</option>
                    <option value="kisi_kisi" {{ request('jenis')=='kisi_kisi'?'selected':'' }}>Kisi-kisi</option>
                    <option value="prota" {{ request('jenis')=='prota'?'selected':'' }}>Prota / Promes</option>
                    <option value="lainnya" {{ request('jenis')=='lainnya'?'selected':'' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draf</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                    <option value="archived" {{ request('status')=='archived'?'selected':'' }}>Diarsipkan</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Judul Dokumen</th>
                        <th>Jenis</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $loop->iteration + ($documents->currentPage()-1) * $documents->perPage() }}</td>
                        <td>
                            <div class="fw-semibold">{{ $doc->judul }}</div>
                            @if($doc->nama_file)<small class="text-muted"><i class="bi bi-paperclip"></i> {{ $doc->nama_file }}</small>@endif
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ strtoupper(str_replace('_',' ',$doc->jenis)) }}</span></td>
                        <td>{{ $doc->mata_pelajaran ?? '-' }}</td>
                        <td>{{ $doc->tingkat_kelas ?? '-' }}</td>
                        <td>{!! $doc->status_badge !!}</td>
                        <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.kurikulum.show', $doc) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.kurikulum.edit', $doc) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.kurikulum.destroy', $doc) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus dokumen ini?"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada dokumen kurikulum</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($documents->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">
        {{ $documents->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
