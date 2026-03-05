@extends('peran.admin.app')
@section('judul', 'Buat Notifikasi')

@push('styles')
<style>
    .staff-dropdown { position: relative; }
    .staff-dropdown .dropdown-toggle-staff {
        width: 100%; padding: 9px 14px; border: 1px solid #e2e8f0; border-radius: 8px;
        background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: space-between;
        font-size: .82rem; color: #475569; transition: all .2s;
    }
    .staff-dropdown .dropdown-toggle-staff:hover { border-color: #818cf8; }
    .staff-dropdown .dropdown-toggle-staff.active { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    .staff-dropdown-menu {
        display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 100;
        background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,.12); margin-top: 4px; max-height: 300px; overflow: hidden;
    }
    .staff-dropdown-menu.show { display: block; }
    .staff-search { padding: 10px; border-bottom: 1px solid #f1f5f9; }
    .staff-search input { width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: .82rem; }
    .staff-search input:focus { outline: none; border-color: #818cf8; }
    .staff-list { max-height: 220px; overflow-y: auto; padding: 4px; }
    .staff-item {
        display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px;
        cursor: pointer; transition: all .15s; font-size: .82rem;
    }
    .staff-item:hover { background: #f0f2f8; }
    .staff-item.selected { background: #eef2ff; }
    .staff-item .avatar-mini {
        width: 32px; height: 32px; border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
        display: flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 600; flex-shrink: 0;
    }
    .staff-item .info { overflow: hidden; }
    .staff-item .info .name { font-weight: 500; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .staff-item .info .meta { font-size: .72rem; color: #94a3b8; }
    .staff-item .check-icon { margin-left: auto; color: #6366f1; display: none; }
    .staff-item.selected .check-icon { display: block; }
    .selected-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .selected-tag {
        display: inline-flex; align-items: center; gap: 4px; background: #eef2ff; color: #4338ca;
        padding: 4px 10px; border-radius: 6px; font-size: .75rem; font-weight: 500;
    }
    .selected-tag .remove { cursor: pointer; font-size: .85rem; opacity: .7; }
    .selected-tag .remove:hover { opacity: 1; }
    .staff-actions { padding: 8px 12px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; }
    .staff-actions a { font-size: .75rem; color: #6366f1; cursor: pointer; text-decoration: none; }
    .staff-actions a:hover { text-decoration: underline; }
</style>
@endpush

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
                                    <input type="radio" name="target" value="all" class="form-check-input" id="targetAll" {{ old('target', 'all') == 'all' ? 'checked' : '' }} onchange="toggleStaffSelect(false)">
                                    <label class="form-check-label" for="targetAll">Semua Staf</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="target" value="specific" class="form-check-input" id="targetSpecific" {{ old('target') == 'specific' ? 'checked' : '' }} onchange="toggleStaffSelect(true)">
                                    <label class="form-check-label" for="targetSpecific">Staf Tertentu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="staffSelect" style="display: {{ old('target') == 'specific' ? 'block' : 'none' }};">
                            <label class="form-label">Pilih Staf</label>
                            <div class="staff-dropdown" id="staffDropdown">
                                <div class="dropdown-toggle-staff" onclick="toggleDropdown()">
                                    <span id="dropdownLabel">Klik untuk memilih staf...</span>
                                    <i class="bi bi-chevron-down" style="font-size:.75rem;"></i>
                                </div>
                                <div class="staff-dropdown-menu" id="staffDropdownMenu">
                                    <div class="staff-search">
                                        <input type="text" id="staffSearchInput" placeholder="Cari nama atau email staf..." oninput="filterStaff(this.value)">
                                    </div>
                                    <div class="staff-actions">
                                        <a href="javascript:void(0)" onclick="selectAllStaff()">Pilih Semua</a>
                                        <a href="javascript:void(0)" onclick="deselectAllStaff()">Hapus Semua</a>
                                    </div>
                                    <div class="staff-list" id="staffList">
                                        @foreach($staffs as $staff)
                                        <div class="staff-item {{ is_array(old('user_ids')) && in_array($staff->id, old('user_ids')) ? 'selected' : '' }}"
                                             data-id="{{ $staff->id }}" data-name="{{ $staff->nama }}" data-email="{{ $staff->email }}"
                                             data-jabatan="{{ $staff->jabatan ?? '' }}" onclick="toggleStaffItem(this)">
                                            <div class="avatar-mini">{{ strtoupper(substr($staff->nama, 0, 2)) }}</div>
                                            <div class="info">
                                                <div class="name">{{ $staff->nama }}</div>
                                                <div class="meta">{{ $staff->email }} &middot; {{ $staff->jabatan ?? '-' }}</div>
                                            </div>
                                            <i class="bi bi-check-circle-fill check-icon"></i>
                                            <input type="checkbox" name="user_ids[]" value="{{ $staff->id }}" class="d-none"
                                                {{ is_array(old('user_ids')) && in_array($staff->id, old('user_ids')) ? 'checked' : '' }}>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="selected-tags" id="selectedTags"></div>
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

@push('scripts')
<script>
function toggleStaffSelect(show) {
    document.getElementById('staffSelect').style.display = show ? 'block' : 'none';
}

function toggleDropdown() {
    const menu = document.getElementById('staffDropdownMenu');
    const toggle = document.querySelector('.dropdown-toggle-staff');
    menu.classList.toggle('show');
    toggle.classList.toggle('active');
    if (menu.classList.contains('show')) {
        document.getElementById('staffSearchInput').focus();
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('staffDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.getElementById('staffDropdownMenu').classList.remove('show');
        document.querySelector('.dropdown-toggle-staff').classList.remove('active');
    }
});

function filterStaff(query) {
    const items = document.querySelectorAll('.staff-item');
    const q = query.toLowerCase();
    items.forEach(item => {
        const name = item.dataset.name.toLowerCase();
        const email = item.dataset.email.toLowerCase();
        const jabatan = item.dataset.jabatan.toLowerCase();
        item.style.display = (name.includes(q) || email.includes(q) || jabatan.includes(q)) ? '' : 'none';
    });
}

function toggleStaffItem(el) {
    el.classList.toggle('selected');
    const cb = el.querySelector('input[type=checkbox]');
    cb.checked = el.classList.contains('selected');
    updateSelectedTags();
}

function selectAllStaff() {
    document.querySelectorAll('.staff-item').forEach(item => {
        if (item.style.display !== 'none') {
            item.classList.add('selected');
            item.querySelector('input[type=checkbox]').checked = true;
        }
    });
    updateSelectedTags();
}

function deselectAllStaff() {
    document.querySelectorAll('.staff-item').forEach(item => {
        item.classList.remove('selected');
        item.querySelector('input[type=checkbox]').checked = false;
    });
    updateSelectedTags();
}

function removeStaffTag(id) {
    const item = document.querySelector(`.staff-item[data-id="${id}"]`);
    if (item) {
        item.classList.remove('selected');
        item.querySelector('input[type=checkbox]').checked = false;
    }
    updateSelectedTags();
}

function updateSelectedTags() {
    const container = document.getElementById('selectedTags');
    const selected = document.querySelectorAll('.staff-item.selected');
    const label = document.getElementById('dropdownLabel');

    if (selected.length === 0) {
        container.innerHTML = '';
        label.textContent = 'Klik untuk memilih staf...';
        return;
    }

    label.textContent = selected.length + ' staf dipilih';
    let html = '';
    selected.forEach(item => {
        html += `<span class="selected-tag">
            ${item.dataset.name}
            <span class="remove" onclick="event.stopPropagation(); removeStaffTag(${item.dataset.id})">&times;</span>
        </span>`;
    });
    container.innerHTML = html;
}

// Init on page load
document.addEventListener('DOMContentLoaded', updateSelectedTags);
</script>
@endpush
