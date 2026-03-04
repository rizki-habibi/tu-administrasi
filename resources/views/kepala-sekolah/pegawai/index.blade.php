@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Data Staff')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Data Staff</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Daftar seluruh pegawai & staff</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" style="width:220px;" placeholder="Cari nama / NIP..." value="{{ request('search') }}">
            <select name="peran" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Role</option>
                @foreach(\App\Models\User::ROLES as $key => $label)
                    <option value="{{ $key }}" {{ request('peran') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non-aktif</option>
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
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Role</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($staffs as $i => $s)
                    <tr>
                        <td>{{ $staffs->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:32px;height:32px;font-size:.7rem;background:linear-gradient(135deg,#d97706,#ea580c);flex-shrink:0;">
                                    {{ strtoupper(substr($s->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $s->nama }}</div>
                                    <small class="text-muted">{{ $s->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $s->nip ?? '-' }}</td>
                        <td><span class="badge bg-warning bg-opacity-10 text-warning">{{ $s->role_label }}</span></td>
                        <td>{{ $s->jabatan ?? '-' }}</td>
                        <td>
                            @if($s->aktif)
                                <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">Non-aktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kepala-sekolah.pegawai.show', $s) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye me-1"></i>Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data staff</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">{{ $staffs->withQueryString()->links() }}</div>
@endsection
