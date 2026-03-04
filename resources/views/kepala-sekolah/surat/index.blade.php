@extends('kepala-sekolah.tata-letak.app')
@section('judul', 'Persuratan')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Persuratan</h5>
        <p class="text-muted mb-0" style="font-size:.85rem;">Daftar surat masuk & keluar</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form class="d-flex gap-2 align-items-center flex-wrap" method="GET">
            <select name="jenis" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Jenis</option>
                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
            </select>
            <select name="status" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                @foreach(['draft','diproses','dikirim','diterima','diarsipkan'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control form-control-sm" style="width:200px;" placeholder="Cari perihal / nomor..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-funnel"></i> Saring</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Nomor Surat</th><th>Jenis</th><th>Perihal</th><th>Tujuan/Asal</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($surats as $i => $surat)
                    <tr>
                        <td>{{ $surats instanceof \Illuminate\Pagination\LengthAwarePaginator ? $surats->firstItem() + $i : $i + 1 }}</td>
                        <td class="fw-semibold" style="font-size:.8rem;">{{ $surat->nomor_surat ?? '-' }}</td>
                        <td>
                            @if($surat->jenis === 'masuk')
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-box-arrow-in-down me-1"></i>Masuk</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-box-arrow-up me-1"></i>Keluar</span>
                            @endif
                        </td>
                        <td style="max-width:200px;">{{ \Str::limit($surat->perihal, 40) }}</td>
                        <td style="font-size:.8rem;">{{ $surat->jenis === 'masuk' ? ($surat->asal ?? '-') : ($surat->tujuan ?? '-') }}</td>
                        <td style="font-size:.8rem;">{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}</td>
                        <td>{!! $surat->status_badge !!}</td>
                        <td>
                            <a href="{{ route('kepala-sekolah.surat.show', $surat) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data surat</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($surats instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-3 d-flex justify-content-center">{{ $surats->withQueryString()->links() }}</div>
@endif
@endsection
