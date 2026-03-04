@extends('kepala-sekolah.tata-letak.app')

@section('judul', 'Ulang Tahun Pegawai')

@push('styles')
<style>
    .birthday-header {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        color: #fff;
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 24px;
    }
    .birthday-header h1 { margin: 0; font-size: 1.4rem; font-weight: 700; }
    .birthday-header p { margin: 6px 0 0; opacity: .85; font-size: .85rem; }

    .birthday-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
        transition: transform .2s, box-shadow .2s;
    }
    .birthday-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }

    .birthday-avatar {
        width: 56px; height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; font-weight: 700; color: #fff;
    }

    .date-divider {
        font-size: .82rem;
        font-weight: 600;
        color: #f59e0b;
        padding: 6px 16px;
        background: #fffbeb;
        border-radius: 8px;
        display: inline-block;
        margin-bottom: 14px;
    }

    .ucapan-card {
        border-left: 4px solid #f59e0b;
        border-radius: 0 10px 10px 0;
        padding: 14px 18px;
        margin-bottom: 10px;
        background: #fffbeb;
    }

    .btn-ucapan {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: .78rem;
        font-weight: 600;
    }
    .btn-ucapan:hover { background: linear-gradient(135deg, #d97706, #ea580c); color: #fff; }
</style>
@endpush

@section('konten')
<div class="birthday-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-gift me-2"></i>Ulang Tahun Pegawai</h1>
            <p>Daftar pegawai yang berulang tahun dalam 7 hari ke depan</p>
        </div>
        <a href="{{ route('kepala-sekolah.beranda') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-heart text-warning me-2"></i>Ulang Tahun Mendatang</h6>
            </div>
            <div class="card-body">
                @forelse($users as $date => $group)
                    <div class="date-divider">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        @if(\Carbon\Carbon::parse($date)->isToday())
                            <span class="badge bg-danger ms-1">Hari ini!</span>
                        @endif
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach($group as $user)
                            <div class="col-md-6">
                                <div class="card birthday-card p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="birthday-avatar">
                                            {{ strtoupper(substr($user->nama_lengkap ?? $user->nama, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-bold" style="font-size:.88rem;">{{ $user->nama_lengkap ?? $user->nama }}</h6>
                                            <small class="text-muted">{{ $user->jabatan ?? $user->peran }}</small>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="bi bi-cake2 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') }}
                                                    ({{ \Carbon\Carbon::parse($user->tanggal_lahir)->age + (\Carbon\Carbon::parse($user->upcoming_birthday)->isFuture() ? 1 : 0) }} tahun)
                                                </small>
                                            </div>
                                        </div>
                                        <button class="btn-ucapan" onclick="kirimUcapan({{ $user->id }}, '{{ addslashes($user->nama_lengkap ?? $user->nama) }}')" title="Kirim Ucapan">
                                            <i class="bi bi-envelope-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-emoji-smile" style="font-size:3rem;color:#cbd5e1;"></i>
                        <p class="text-muted mt-2">Tidak ada pegawai yang berulang tahun dalam 7 hari ke depan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Ucapan Dikirim --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-send text-success me-2"></i>Ucapan Terkirim</h6>
            </div>
            <div class="card-body">
                @forelse($ucapanDikirim as $ucapan)
                    <div class="ucapan-card">
                        <small class="fw-bold">Kepada: {{ $ucapan->penerima->nama_lengkap ?? $ucapan->penerima->nama }}</small>
                        <p class="mb-1 mt-1" style="font-size:.82rem;">{{ $ucapan->pesan }}</p>
                        <small class="text-muted">{{ $ucapan->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted text-center small">Belum ada ucapan terkirim.</p>
                @endforelse
            </div>
        </div>

        {{-- Ucapan Diterima --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-envelope-open text-primary me-2"></i>Ucapan Diterima</h6>
            </div>
            <div class="card-body">
                @forelse($ucapanDiterima as $ucapan)
                    <div class="ucapan-card" style="border-left-color:#3b82f6; background:#eff6ff;">
                        <small class="fw-bold">Dari: {{ $ucapan->pengirim->nama_lengkap ?? $ucapan->pengirim->nama }}</small>
                        <p class="mb-1 mt-1" style="font-size:.82rem;">{{ $ucapan->pesan }}</p>
                        <small class="text-muted">{{ $ucapan->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted text-center small">Belum ada ucapan diterima.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal Kirim Ucapan --}}
<div class="modal fade" id="modalUcapan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;">
                <h5 class="modal-title"><i class="bi bi-envelope-heart me-2"></i>Kirim Ucapan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ucapanPenerimaId">
                <div class="mb-3">
                    <label class="form-label fw-bold">Kepada:</label>
                    <p id="ucapanPenerimaNama" class="mb-0"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Pesan Ucapan:</label>
                    <textarea id="ucapanPesan" class="form-control" rows="4" placeholder="Tulis ucapan selamat ulang tahun..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-ucapan" onclick="submitUcapan()">
                    <i class="bi bi-send me-1"></i> Kirim
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function kirimUcapan(id, nama) {
    document.getElementById('ucapanPenerimaId').value = id;
    document.getElementById('ucapanPenerimaNama').textContent = nama;
    document.getElementById('ucapanPesan').value = '';
    new bootstrap.Modal(document.getElementById('modalUcapan')).show();
}

function submitUcapan() {
    const penerimaId = document.getElementById('ucapanPenerimaId').value;
    const pesan = document.getElementById('ucapanPesan').value;

    if (!pesan.trim()) {
        alert('Pesan ucapan tidak boleh kosong!');
        return;
    }

    fetch('{{ route("kepala-sekolah.ulang-tahun.ucapan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ penerima_id: penerimaId, pesan: pesan })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalUcapan')).hide();
            location.reload();
        } else {
            alert('Gagal mengirim ucapan.');
        }
    })
    .catch(() => alert('Terjadi kesalahan.'));
}
</script>
@endpush
