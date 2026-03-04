@extends('admin.tata-letak.app')
@section('judul', 'Buat Notifikasi')

@section('konten')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-bell-fill"></i> Buat Notifikasi Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.notifikasi.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea name="pesan" class="form-control @error('pesan') is-invalid @enderror" rows="4" required>{{ old('pesan') }}</textarea>
                            @error('pesan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipe <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="">Pilih Tipe</option>
                                <option value="kehadiran" {{ old('jenis') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                                <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="event" {{ old('jenis') == 'event' ? 'selected' : '' }}>Event</option>
                                <option value="laporan" {{ old('jenis') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                                <option value="sistem" {{ old('jenis') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                                <option value="pengumuman" {{ old('jenis') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            </select>
                            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Penerima <span class="text-danger">*</span></label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="target" value="all" class="form-check-input" id="targetAll" {{ old('target', 'all') == 'all' ? 'checked' : '' }} onchange="document.getElementById('staffSelect').style.display='none'">
                                    <label class="form-check-label" for="targetAll">Semua Staff</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="target" value="specific" class="form-check-input" id="targetSpecific" {{ old('target') == 'specific' ? 'checked' : '' }} onchange="document.getElementById('staffSelect').style.display='block'">
                                    <label class="form-check-label" for="targetSpecific">Staff Tertentu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="staffSelect" style="display: {{ old('target') == 'specific' ? 'block' : 'none' }};">
                            <label class="form-label">Pilih Staff</label>
                            <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                                @foreach($staffs as $staff)
                                <div class="form-check">
                                    <input type="checkbox" name="user_ids[]" value="{{ $staff->id }}" class="form-check-input" id="staff{{ $staff->id }}"
                                        {{ is_array(old('user_ids')) && in_array($staff->id, old('user_ids')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="staff{{ $staff->id }}">
                                        {{ $staff->nama }} <small class="text-muted">({{ $staff->email }})</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('user_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Kirim Notifikasi</button>
                        <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
