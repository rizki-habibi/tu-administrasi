@extends('layouts.dokumen')

@section('title', 'Saran & Masukan — Dokumen')

@section('content')
    <div class="mb-4">
        <h4 style="font-weight:700;color:#1e1b4b;margin-bottom:4px;">
            <i class="bi bi-chat-left-heart-fill me-2" style="color:var(--dk-primary);"></i>Saran & Masukan
        </h4>
        <p style="font-size:.82rem;color:#64748b;margin:0;">Berikan saran, kritik, atau masukan untuk peningkatan kinerja SMA Negeri 2 Jember.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card" style="border:none;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,.06);">
                <div class="card-body" style="padding:28px;">
                    <form action="{{ route('dokumen.saran.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.82rem;font-weight:500;">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required maxlength="100" style="border-radius:10px;font-size:.85rem;">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.82rem;font-weight:500;">Email <span style="color:#94a3b8;font-weight:400;">(opsional)</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" maxlength="150" style="border-radius:10px;font-size:.85rem;">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.82rem;font-weight:500;">Subjek <span class="text-danger">*</span></label>
                            <input type="text" name="subjek" class="form-control @error('subjek') is-invalid @enderror" value="{{ old('subjek') }}" required maxlength="200" style="border-radius:10px;font-size:.85rem;">
                            @error('subjek')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-size:.82rem;font-weight:500;">Pesan / Saran <span class="text-danger">*</span></label>
                            <textarea name="pesan" rows="5" class="form-control @error('pesan') is-invalid @enderror" required maxlength="2000" style="border-radius:10px;font-size:.85rem;">{{ old('pesan') }}</textarea>
                            @error('pesan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="border-radius:10px;font-size:.85rem;padding:8px 28px;">
                            <i class="bi bi-send me-2"></i>Kirim Saran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
