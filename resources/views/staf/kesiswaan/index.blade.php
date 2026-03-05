@extends('peran.staf.app')
@section('judul', 'Data Kesiswaan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-people me-2"></i>Data Kesiswaan</h4>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari siswa (nama/NIS)..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="kelas" class="form-select form-select-sm">
                    <option value="">Semua Kelas</option>
                    @foreach(['X','XI','XII'] as $c)
                    <option value="{{ $c }}" {{ request('kelas')==$c?'selected':'' }}>Kelas {{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="tidak_aktif" {{ request('status')=='tidak_aktif'?'selected':'' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Siswa</th>
                    <th>NIS/NISN</th>
                    <th>Kelas</th>
                    <th>JK</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students ?? [] as $i => $s)
                <tr>
                    <td>{{ ($students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->firstItem() + $i : $i + 1) }}</td>
                    <td>
                        @if($s->foto)
                        <img src="{{ Storage::url($s->foto) }}" class="rounded-circle" width="35" height="35" style="object-fit:cover;">
                        @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:35px;height:35px;"><i class="bi bi-person text-muted"></i></div>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $s->nama }}</td>
                    <td><small>{{ $s->nis ?? '-' }} / {{ $s->nisn ?? '-' }}</small></td>
                    <td>{{ $s->kelas ?? '-' }}</td>
                    <td>{{ $s->jenis_kelamin == 'L' ? 'L' : 'P' }}</td>
                    <td>
                        <span class="badge {{ $s->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($s->status ?? 'aktif') }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('staf.kesiswaan.show', $s->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-people" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada data siswa</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students ?? false)
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $students->count() }} dari {{ $students->total() }} siswa</small>
        {{ $students->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
