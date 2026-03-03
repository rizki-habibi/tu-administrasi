@extends('layouts.admin')
@section('title', 'Kelola Notifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <form class="d-flex gap-2 flex-wrap" method="GET">
            <select name="type" class="form-select" style="width: auto;">
                <option value="">Semua Tipe</option>
                <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                <option value="danger" {{ request('type') == 'danger' ? 'selected' : '' }}>Danger</option>
            </select>
            <button class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
        </form>
    </div>
    <a href="{{ route('admin.notification.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Notifikasi</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
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
                    $typeColors = ['info'=>'info','warning'=>'warning','success'=>'success','danger'=>'danger'];
                @endphp
                @forelse($notifications as $i => $notif)
                <tr>
                    <td>{{ $notifications->firstItem() + $i }}</td>
                    <td><strong>{{ $notif->title }}</strong></td>
                    <td>{{ Str::limit($notif->message, 50) }}</td>
                    <td><span class="badge bg-{{ $typeColors[$notif->type] ?? 'secondary' }}">{{ ucfirst($notif->type) }}</span></td>
                    <td>{{ $notif->user->name ?? 'Semua' }}</td>
                    <td>
                        @if($notif->is_read)
                            <span class="badge bg-success"><i class="bi bi-check2"></i> Sudah</span>
                        @else
                            <span class="badge bg-secondary">Belum</span>
                        @endif
                    </td>
                    <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.notification.destroy', $notif) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus notifikasi ini?')">
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
