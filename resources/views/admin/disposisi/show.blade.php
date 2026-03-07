@extends('peran.admin.app')
@section('judul', 'Detail Disposisi')

@section('konten')
<div class="mb-4">
    <a href="{{ route('admin.disposisi.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-send-check me-2 text-primary"></i>Detail Disposisi #{{ $disposisi->id }}</h6>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Dari</small>
                        <strong>{{ $disposisi->dariPengguna->nama }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Kepada</small>
                        <strong>{{ $disposisi->kepadaPengguna->nama }}</strong>
                        <small class="text-muted d-block">{{ $disposisi->kepadaPengguna->jabatan ?? '' }}</small>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <small class="text-muted d-block">Instruksi</small>
                    <p>{{ $disposisi->instruksi }}</p>
                </div>
                @if($disposisi->catatan_tindakan)
                <div class="mb-3">
                    <small class="text-muted d-block">Catatan Tindakan</small>
                    <p class="bg-light rounded p-3">{{ $disposisi->catatan_tindakan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Info Disposisi</h6>
                <div class="mb-2"><small class="text-muted">Prioritas</small><br>
                    @php $pc = ['rendah'=>'success','sedang'=>'info','tinggi'=>'warning','urgent'=>'danger']; @endphp
                    <span class="badge bg-{{ $pc[$disposisi->prioritas] ?? 'secondary' }}">{{ ucfirst($disposisi->prioritas) }}</span>
                </div>
                <div class="mb-2"><small class="text-muted">Status</small><br>
                    @php $sc = ['belum_dibaca'=>'danger','dibaca'=>'info','diproses'=>'warning','selesai'=>'success']; @endphp
                    <span class="badge bg-{{ $sc[$disposisi->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$disposisi->status)) }}</span>
                </div>
                <div class="mb-2"><small class="text-muted">Tenggat</small><br>{{ $disposisi->tenggat?->format('d/m/Y') ?? '-' }}</div>
                <div class="mb-2"><small class="text-muted">Dibaca Pada</small><br>{{ $disposisi->dibaca_pada?->format('d/m/Y H:i') ?? '-' }}</div>
                <div class="mb-2"><small class="text-muted">Selesai Pada</small><br>{{ $disposisi->selesai_pada?->format('d/m/Y H:i') ?? '-' }}</div>
                <div><small class="text-muted">Dibuat</small><br>{{ $disposisi->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        @if($disposisi->surat)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Surat Terkait</h6>
                <div class="mb-2"><code class="text-primary">{{ $disposisi->surat->nomor_surat }}</code></div>
                <div class="mb-2"><strong>{{ $disposisi->surat->perihal }}</strong></div>
                <a href="{{ route('admin.surat.show', $disposisi->surat) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Lihat Surat</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
