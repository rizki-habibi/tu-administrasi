@extends('peran.admin.app')
@section('judul', 'Detail Siswa')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.kesiswaan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Detail Siswa</h4>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body p-4">
                @if($kesiswaan->foto)
                    <img src="{{ asset('storage/'.$kesiswaan->foto) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover;">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;background:linear-gradient(135deg,#6366f1,#818cf8);color:#fff;font-size:2rem;font-weight:700;">{{ strtoupper(substr($kesiswaan->nama,0,2)) }}</div>
                @endif
                <h5 class="fw-bold">{{ $kesiswaan->nama }}</h5>
                <p class="text-muted mb-1">NIS: {{ $kesiswaan->nis }}</p>
                <p class="text-muted mb-2">NISN: {{ $kesiswaan->nisn ?? '-' }}</p>
                @if($kesiswaan->status == 'aktif')<span class="badge bg-success">Aktif</span>
                @elseif(in_array($kesiswaan->status, ['mutasi_masuk','mutasi_keluar']))<span class="badge bg-warning text-dark">{{ $kesiswaan->status == 'mutasi_masuk' ? 'Mutasi Masuk' : 'Mutasi Keluar' }}</span>
                @elseif($kesiswaan->status == 'lulus')<span class="badge bg-primary">Lulus</span>
                @else<span class="badge bg-danger">Drop Out</span>@endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-1 text-primary"></i> Informasi Lengkap</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label text-muted">Kelas</label><p class="fw-semibold">{{ $kesiswaan->kelas }}</p></div>
                    <div class="col-md-6"><label class="form-label text-muted">Jenis Kelamin</label><p>{{ $kesiswaan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p></div>
                    <div class="col-md-6"><label class="form-label text-muted">Tempat, Tanggal Lahir</label><p>{{ $kesiswaan->tempat_lahir ?? '' }}{{ $kesiswaan->tanggal_lahir ? ', '.\Carbon\Carbon::parse($kesiswaan->tanggal_lahir)->format('d F Y') : '' }}</p></div>
                    <div class="col-md-6"><label class="form-label text-muted">Agama</label><p>{{ $kesiswaan->agama ?? '-' }}</p></div>
                    <div class="col-md-6"><label class="form-label text-muted">Nama Orang Tua/Wali</label><p>{{ $kesiswaan->nama_orang_tua ?? '-' }}</p></div>
                    <div class="col-md-6"><label class="form-label text-muted">No. HP Orang Tua/Wali</label><p>{{ $kesiswaan->telepon_orang_tua ?? '-' }}</p></div>
                    <div class="col-12"><label class="form-label text-muted">Alamat</label><p>{{ $kesiswaan->alamat ?? '-' }}</p></div>
                </div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="card mt-3">
            <div class="card-header bg-transparent d-flex justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-trophy me-1 text-warning"></i> Prestasi</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Prestasi</th><th>Tingkat</th><th>Tahun</th></tr></thead>
                        <tbody>
                            @forelse($kesiswaan->achievements ?? [] as $a)
                            <tr><td>{{ $a->judul }}</td><td><span class="badge bg-info bg-opacity-10 text-info">{{ $a->tingkat }}</span></td><td>{{ $a->date ? \Carbon\Carbon::parse($a->date)->format('Y') : '-' }}</td></tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Belum ada prestasi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Violations -->
        <div class="card mt-3">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-exclamation-triangle me-1 text-danger"></i> Pelanggaran</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Pelanggaran</th><th>Tingkat</th><th>Tanggal</th><th>Sanksi</th></tr></thead>
                        <tbody>
                            @forelse($kesiswaan->violations ?? [] as $v)
                            <tr><td>{{ $v->deskripsi }}</td><td><span class="badge bg-{{ $v->jenis=='ringan'?'warning':'danger' }}">{{ ucfirst($v->jenis) }}</span></td><td>{{ $v->date ? \Carbon\Carbon::parse($v->date)->format('d/m/Y') : '-' }}</td><td>{{ $v->tindakan ?? '-' }}</td></tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada pelanggaran</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <a href="{{ route('admin.kesiswaan.edit', $kesiswaan) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
    <form action="{{ route('admin.kesiswaan.destroy', $kesiswaan) }}" method="POST">@csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger" data-confirm="Hapus data siswa {{ $kesiswaan->nama }}?"><i class="bi bi-trash me-1"></i> Hapus</button>
    </form>
</div>
@endsection
