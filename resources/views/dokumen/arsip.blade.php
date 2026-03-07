@extends('layouts.dokumen')

@section('title', 'Arsip & Unduhan — Dokumen')

@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 style="font-weight:700;color:#1e1b4b;margin-bottom:4px;">
                <i class="bi bi-archive-fill me-2" style="color:var(--dk-primary);"></i>Arsip & Unduhan
            </h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">
                Semua file dokumen yang tersedia untuk diunduh. Total {{ $items->total() }} file.
            </p>
        </div>
        <form action="{{ route('dokumen.arsip') }}" method="GET" class="d-flex gap-2" style="max-width:300px;">
            <div class="input-group input-group-sm">
                <input type="text" name="cari" class="form-control" placeholder="Cari file..." value="{{ $cari }}" style="border-radius:8px 0 0 8px;font-size:.8rem;">
                <button class="btn btn-primary" style="border-radius:0 8px 8px 0;font-size:.8rem;"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    @if($items->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:.82rem;">
                <thead style="background:#f1f5f9;">
                    <tr>
                        <th style="font-weight:600;color:#475569;">#</th>
                        <th style="font-weight:600;color:#475569;">Judul</th>
                        <th style="font-weight:600;color:#475569;">Kategori</th>
                        <th style="font-weight:600;color:#475569;">Nama File</th>
                        <th style="font-weight:600;color:#475569;">Ukuran</th>
                        <th style="font-weight:600;color:#475569;">Tanggal</th>
                        <th style="font-weight:600;color:#475569;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route('dokumen.show', $item->id) }}" style="color:#1e293b;text-decoration:none;font-weight:500;">
                                    {{ Str::limit($item->judul, 50) }}
                                </a>
                            </td>
                            <td><span class="badge-kategori">{{ ucfirst(str_replace('_', ' ', $item->kategori)) }}</span></td>
                            <td style="color:#64748b;">{{ $item->nama_file ?? '-' }}</td>
                            <td style="color:#64748b;">
                                @if($item->ukuran_file)
                                    {{ number_format($item->ukuran_file / 1024, 0) }} KB
                                @else
                                    -
                                @endif
                            </td>
                            <td style="color:#64748b;">{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $item->path_file) }}" class="btn btn-sm btn-primary" style="border-radius:8px;font-size:.7rem;padding:4px 12px;" target="_blank">
                                    <i class="bi bi-download me-1"></i>Unduh
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $items->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-archive" style="font-size:3rem;color:#cbd5e1;"></i>
            <h5 style="color:#94a3b8;margin-top:12px;font-size:1rem;">Belum ada file arsip</h5>
            <p style="font-size:.82rem;color:#94a3b8;">
                @if($cari)
                    Tidak ditemukan file untuk "{{ $cari }}".
                    <a href="{{ route('dokumen.arsip') }}">Reset pencarian</a>
                @else
                    File akan tersedia setelah admin mengunggah dokumen.
                @endif
            </p>
        </div>
    @endif
@endsection
