@extends('peran.admin.app')
@section('judul', 'Kelola Notifikasi')

@section('konten')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <form class="d-flex gap-2 flex-wrap" method="GET">
            <select name="jenis" class="form-select" style="width: auto;">
                <option value="">Semua Tipe</option>
                <option value="kehadiran" {{ request('jenis') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                <option value="izin" {{ request('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="event" {{ request('jenis') == 'event' ? 'selected' : '' }}>Event</option>
                <option value="laporan" {{ request('jenis') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                <option value="sistem" {{ request('jenis') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                <option value="pengumuman" {{ request('jenis') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
            </select>
            <button class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
        </form>
    </div>
    <a href="{{ route('admin.notifikasi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Notifikasi</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pesan</th>
                    <th>Tipe</th>
                    <th>Penerima</th>
                    <th>Dibaca</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $typeColors = ['kehadiran'=>'success','izin'=>'info','event'=>'primary','laporan'=>'warning','sistem'=>'danger','pengumuman'=>'dark'];
                @endphp
                @forelse($notifications as $i => $notif)
                <tr>
                    <td>{{ $notifications->firstItem() + $i }}</td>
                    <td><strong>{{ $notif->judul }}</strong></td>
                    <td>{{ Str::limit($notif->pesan, 50) }}</td>
                    <td><span class="badge bg-{{ $typeColors[$notif->jenis] ?? 'secondary' }}">{{ ucfirst($notif->jenis) }}</span></td>
                    <td>{{ $notif->user->nama ?? 'Semua' }}</td>
                    <td>
                        @if($notif->sudah_dibaca)
                            <span class="badge bg-success"><i class="bi bi-check2"></i> Sudah</span>
                        @else
                            <span class="badge bg-secondary">Belum</span>
                        @endif
                    </td>
                    <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.notifikasi.destroy', $notif) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus notifikasi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada notifikasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
