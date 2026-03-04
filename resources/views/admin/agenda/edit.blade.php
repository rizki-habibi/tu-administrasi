@extends('admin.tata-letak.app')
@section('judul', 'Edit Event')

@section('konten')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Event</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.agenda.update', $event) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Judul Event <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $event->judul) }}" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Event <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" class="form-control @error('tanggal_acara') is-invalid @enderror" value="{{ old('tanggal_acara', $event->tanggal_acara->format('Y-m-d')) }}" required>
                            @error('tanggal_acara')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control @error('waktu_mulai') is-invalid @enderror" value="{{ old('waktu_mulai', $event->waktu_mulai) }}" required>
                            @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control @error('waktu_selesai') is-invalid @enderror" value="{{ old('waktu_selesai', $event->waktu_selesai) }}" required>
                            @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $event->lokasi) }}">
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipe Event <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="">Pilih Tipe</option>
                                <option value="rapat" {{ old('jenis', $event->jenis) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                <option value="kegiatan" {{ old('jenis', $event->jenis) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="upacara" {{ old('jenis', $event->jenis) == 'upacara' ? 'selected' : '' }}>Upacara</option>
                                <option value="pelatihan" {{ old('jenis', $event->jenis) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                <option value="lainnya" {{ old('jenis', $event->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="upcoming" {{ old('status', $event->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ old('status', $event->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                        <a href="{{ route('admin.agenda.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
