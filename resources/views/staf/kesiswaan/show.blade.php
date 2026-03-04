@extends('staf.tata-letak.app')
@section('judul', 'Detail Siswa')

@section('konten')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('staf.kesiswaan.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:#1e293b;">Profil Siswa</h4>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body p-4">
                @if($student->foto)
                <img src="{{ Storage::url($student->foto) }}" class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">
                @else
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width:120px;height:120px;"><i class="bi bi-person" style="font-size:3rem;color:#94a3b8;"></i></div>
                @endif
                <h5 class="fw-bold mb-1">{{ $student->nama }}</h5>
                <p class="text-muted mb-2">NIS: {{ $student->nis ?? '-' }}</p>
                <span class="badge {{ $student->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($student->status ?? 'aktif') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold">Informasi Lengkap</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" width="200">Nama Lengkap</td><td>{{ $student->nama }}</td></tr>
                    <tr><td class="text-muted">NIS / NISN</td><td>{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kelas</td><td>{{ $student->class ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Jenis Kelamin</td><td>{{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="text-muted">Tempat, Tanggal Lahir</td><td>{{ $student->tempat_lahir ?? '-' }}, {{ $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>{{ $student->alamat ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Nama Orang Tua/Wali</td><td>{{ $student->nama_orang_tua ?? '-' }}</td></tr>
                    <tr><td class="text-muted">No. Telepon Ortu</td><td>{{ $student->telepon_orang_tua ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tahun Masuk</td><td>{{ $student->tanggal_masuk ? \Carbon\Carbon::parse($student->tanggal_masuk)->format('Y') : '-' }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Prestasi --}}
        <div class="card mt-3">
            <div class="card-header bg-transparent"><h6 class="mb-0 fw-semibold"><i class="bi bi-trophy me-1"></i> Prestasi</h6></div>
            <div class="card-body">
                @forelse($student->achievements ?? [] as $a)
                <div class="d-flex align-items-start mb-2">
                    <span class="badge bg-warning text-dark me-2">{{ $a->tingkat ?? '' }}</span>
                    <div>
                        <div class="fw-semibold">{{ $a->judul }}</div>
                        <small class="text-muted">{{ $a->date ? \Carbon\Carbon::parse($a->date)->translatedFormat('d F Y') : '' }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">Belum ada data prestasi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
