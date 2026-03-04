@extends('peran.staf.app')
@section('judul', 'Asesmen P5')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:#1e293b;"><i class="bi bi-star me-2"></i>Projek Penguatan Profil Pelajar Pancasila (P5)</h4>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-1"></i> Daftar projek P5 yang tersedia di sekolah. Anda dapat melihat detail dan berpartisipasi.
</div>

{{-- Dimension Cards --}}
<div class="row g-3 mb-4">
    @php
    $dimensions = [
        ['Beriman & Bertaqwa', 'bi-moon-stars', '#6366f1'],
        ['Berkebhinekaan Global', 'bi-globe', '#ec4899'],
        ['Bergotong Royong', 'bi-people', '#f59e0b'],
        ['Mandiri', 'bi-lightning', '#10b981'],
        ['Bernalar Kritis', 'bi-lightbulb', '#3b82f6'],
        ['Kreatif', 'bi-palette', '#8b5cf6'],
    ];
    @endphp
    @foreach($dimensions as $d)
    <div class="col-md-2 col-4">
        <div class="card text-center border-0 h-100" style="background:{{ $d[2] }}15;">
            <div class="card-body py-3">
                <i class="bi {{ $d[1] }}" style="font-size:1.5rem; color:{{ $d[2] }};"></i>
                <p class="mb-0 mt-1 small fw-semibold" style="font-size:0.7rem;">{{ $d[0] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul Projek</th>
                    <th>Dimensi</th>
                    <th>Tema</th>
                    <th>Kelas</th>
                    <th>Periode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments ?? [] as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ $a->judul_projek }}</div>
                        <small class="text-muted">{{ Str::limit($a->deskripsi, 50) }}</small>
                    </td>
                    <td><span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $a->dimensi ?? '-')) }}</span></td>
                    <td>{{ $a->tema ?? '-' }}</td>
                    <td>{{ $a->kelas ?? '-' }}</td>
                    <td>{{ $a->tahun_ajaran ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-star" style="font-size:2rem;"></i><p class="mt-2 mb-0">Belum ada projek P5</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
