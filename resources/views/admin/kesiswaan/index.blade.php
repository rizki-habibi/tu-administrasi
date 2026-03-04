@extends('admin.tata-letak.app')
@section('judul', 'Data Kesiswaan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Data Siswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Kelola data kesiswaan SMA Negeri 2 Jember</p>
    </div>
    <a href="{{ route('admin.kesiswaan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Siswa</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
            <div class="icon-box"><i class="bi bi-people-fill"></i></div>
            <h3>{{ $students->total() }}</h3>
            <p>Total Siswa</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#34d399)">
            <div class="icon-box"><i class="bi bi-person-check-fill"></i></div>
            <h3>{{ $aktifCount ?? 0 }}</h3>
            <p>Siswa Aktif</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)">
            <div class="icon-box"><i class="bi bi-gender-male"></i></div>
            <h3>{{ $lakiCount ?? 0 }}</h3>
            <p>Laki-laki</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#ec4899,#f472b6)">
            <div class="icon-box"><i class="bi bi-gender-female"></i></div>
            <h3>{{ $perempuanCount ?? 0 }}</h3>
            <p>Perempuan</p>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / NIS / NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="kelas" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k }}" {{ request('class')==$k?'selected':'' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="mutasi_masuk" {{ request('status')=='mutasi_masuk'?'selected':'' }}>Mutasi Masuk</option>
                    <option value="mutasi_keluar" {{ request('status')=='mutasi_keluar'?'selected':'' }}>Mutasi Keluar</option>
                    <option value="lulus" {{ request('status')=='lulus'?'selected':'' }}>Lulus</option>
                    <option value="do" {{ request('status')=='do'?'selected':'' }}>Drop Out</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                    <tr>
                        <td>{{ $loop->iteration + ($students->currentPage()-1) * $students->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($s->foto)
                                    <img src="{{ asset('storage/'.$s->foto) }}" class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#e0e7ff;color:#6366f1;font-size:.75rem;font-weight:600;">{{ strtoupper(substr($s->nama,0,2)) }}</div>
                                @endif
                                <span class="fw-semibold">{{ $s->nama }}</span>
                            </div>
                        </td>
                        <td>{{ $s->nis }}</td>
                        <td>{{ $s->nisn ?? '-' }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ $s->class }}</span></td>
                        <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>
                            @if($s->status == 'aktif')<span class="badge bg-success">Aktif</span>
                            @elseif(in_array($s->status, ['mutasi_masuk','mutasi_keluar']))<span class="badge bg-warning text-dark">{{ $s->status == 'mutasi_masuk' ? 'Mutasi Masuk' : 'Mutasi Keluar' }}</span>
                            @elseif($s->status == 'lulus')<span class="badge bg-primary">Lulus</span>
                            @else<span class="badge bg-danger">Drop Out</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.kesiswaan.show', $s) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.kesiswaan.edit', $s) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.kesiswaan.destroy', $s) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus data siswa {{ $s->nama }}?"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada data siswa</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($students->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $students->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
