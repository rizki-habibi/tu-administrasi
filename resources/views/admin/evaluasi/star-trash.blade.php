@extends('peran.admin.app')
@section('judul', 'Sampah STAR')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;"><i class="bi bi-trash3 me-2"></i>Sampah Analisis STAR</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Item yang dihapus akan terhapus permanen setelah 30 hari.</p>
    </div>
    <a href="{{ route('admin.evaluasi.star') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>No</th><th>Judul</th><th>Kategori</th><th>Dihapus Pada</th><th>Sisa Waktu</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($analyses as $a)
                    @php $daysLeft = 30 - (int) now()->diffInDays($a->deleted_at); @endphp
                    <tr>
                        <td>{{ $loop->iteration + ($analyses->currentPage()-1)*$analyses->perPage() }}</td>
                        <td class="fw-semibold">{{ $a->judul }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($a->kategori ?? '-') }}</span></td>
                        <td>{{ $a->deleted_at->translatedFormat('d M Y H:i') }}</td>
                        <td>
                            @if($daysLeft > 7)
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $daysLeft }} hari</span>
                            @elseif($daysLeft > 0)
                                <span class="badge bg-warning bg-opacity-10 text-warning">{{ $daysLeft }} hari</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">Segera dihapus</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.evaluasi.star.restore', $a->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </form>
                                <form action="{{ route('admin.evaluasi.star.force-delete', $a->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Hapus permanen? Data tidak dapat dipulihkan." title="Hapus Permanen"><i class="bi bi-x-circle"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-trash3" style="font-size:2rem;"></i><br>Sampah kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($analyses->hasPages())
    <div class="card-footer bg-transparent border-0 d-flex justify-content-center py-3">{{ $analyses->links() }}</div>
    @endif
</div>
@endsection
