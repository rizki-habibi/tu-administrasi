@extends('peran.admin.app')
@section('judul', 'Metode STAR')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Analisis Metode STAR</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Situation - Task - Action - Result</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.evaluasi.star.trash') }}" class="btn btn-outline-secondary"><i class="bi bi-trash3 me-1"></i> Sampah</a>
        <a href="{{ route('admin.evaluasi.star.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
    </div>
</div>

<!-- STAR Explanation Cards -->
<div class="row g-3 mb-4">
    @php $starCards = [
        ['S - Situation', 'Menjelaskan situasi atau konteks yang dihadapi', 'bi-geo-alt', '#6366f1'],
        ['T - Task', 'Tugas atau tantangan yang harus diselesaikan', 'bi-list-task', '#f59e0b'],
        ['A - Action', 'Langkah-langkah atau tindakan yang diambil', 'bi-lightning', '#10b981'],
        ['R - Result', 'Hasil atau dampak dari tindakan yang diambil', 'bi-trophy', '#ec4899'],
    ]; @endphp
    @foreach($starCards as $sc)
    <div class="col-md-3">
        <div class="card text-center py-3 px-2">
            <i class="bi {{ $sc[2] }}" style="font-size:1.8rem;color:{{ $sc[3] }}"></i>
            <h6 class="fw-bold mt-2 mb-1" style="font-size:.85rem;">{{ $sc[0] }}</h6>
            <small class="text-muted" style="font-size:.72rem;">{{ $sc[1] }}</small>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>No</th><th>Judul</th><th>Kategori</th><th>Pembuat</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($analyses as $a)
                    <tr>
                        <td>{{ $loop->iteration + ($analyses->currentPage()-1)*$analyses->perPage() }}</td>
                        <td class="fw-semibold">{{ $a->judul }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($a->kategori ?? '-') }}</span></td>
                        <td>{{ $a->creator->nama ?? 'N/A' }}</td>
                        <td>{{ $a->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.evaluasi.star.show', $a) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.evaluasi.star.edit', $a) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.evaluasi.star.destroy', $a) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Pindahkan analisis ini ke sampah?" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>Belum ada analisis STAR</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($analyses->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $analyses->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
