@extends('peran.admin.app')
@section('judul', 'Saran & Masukan Pengunjung')

@section('konten')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--dark);">Saran & Masukan Pengunjung</h4>
        <p class="text-muted mb-0" style="font-size:.82rem;">Kelola saran dan masukan dari pengunjung halaman publik</p>
    </div>
    <a href="{{ route('admin.halaman-publik.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Filter --}}
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.halaman-publik.saran') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
        Semua
    </a>
    <a href="{{ route('admin.halaman-publik.saran', ['status' => 'baru']) }}" class="btn btn-sm {{ request('status') === 'baru' ? 'btn-danger' : 'btn-outline-secondary' }}">
        Baru @if($countBaru > 0) <span class="badge bg-white text-danger">{{ $countBaru }}</span> @endif
    </a>
    <a href="{{ route('admin.halaman-publik.saran', ['status' => 'ditanggapi']) }}" class="btn btn-sm {{ request('status') === 'ditanggapi' ? 'btn-success' : 'btn-outline-secondary' }}">
        Ditanggapi
    </a>
</div>

{{-- List --}}
<div class="row g-3">
    @forelse($saran as $item)
    <div class="col-12">
        <div class="card" style="border:none;border-radius:var(--card-radius);box-shadow:0 1px 4px rgba(0,0,0,.06);border-left:4px solid {{ $item->status === 'baru' ? '#ef4444' : ($item->status === 'ditanggapi' ? '#10b981' : '#6366f1') }};">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="fw-bold mb-1" style="font-size:.9rem;">{{ $item->subjek }}</h6>
                        <small class="text-muted"><i class="bi bi-person"></i> {{ $item->nama }} @if($item->email) &middot; {{ $item->email }} @endif &middot; {{ $item->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-{{ $item->status === 'baru' ? 'danger' : ($item->status === 'ditanggapi' ? 'success' : 'info') }}" style="font-size:.65rem;">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>

                <p style="font-size:.85rem;color:#475569;margin-bottom:10px;">{{ $item->pesan }}</p>

                @if($item->tanggapan)
                    <div style="background:#f0fdf4;border-radius:10px;padding:12px 16px;font-size:.82rem;border:1px solid #bbf7d0;">
                        <strong style="color:#166534;"><i class="bi bi-reply-fill"></i> Tanggapan:</strong>
                        <p class="mb-0 mt-1" style="color:#15803d;">{{ $item->tanggapan }}</p>
                        <small class="text-muted">Oleh {{ $item->penanggap->nama ?? '-' }} &middot; {{ $item->ditanggapi_pada?->diffForHumans() }}</small>
                    </div>
                @elseif($item->status === 'baru')
                    <form action="{{ route('admin.halaman-publik.saran.tanggapi', $item) }}" method="POST" class="mt-2">
                        @csrf @method('PATCH')
                        <div class="input-group input-group-sm">
                            <input type="text" name="tanggapan" class="form-control" placeholder="Tulis tanggapan..." required>
                            <button class="btn btn-success" type="submit"><i class="bi bi-reply-fill"></i> Tanggapi</button>
                        </div>
                    </form>
                @endif

                <div class="mt-2 text-end">
                    <form action="{{ route('admin.halaman-publik.saran.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:.7rem;" data-confirm="Hapus saran ini?">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-chat-square-text" style="font-size:2.5rem;color:#94a3b8;"></i>
        <p class="text-muted mt-2">Belum ada saran dari pengunjung</p>
    </div>
    @endforelse
</div>

@if($saran->hasPages())
    <div class="mt-4">{{ $saran->links() }}</div>
@endif
@endsection
