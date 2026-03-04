$basePath = "c:\laragon\www\ut adminisitrasi"

# Get all PHP files EXCLUDING vendor, node_modules, migrations, and this script
$files = Get-ChildItem -Path $basePath -Include "*.php" -Recurse | Where-Object {
    $_.FullName -notlike "*\vendor\*" -and
    $_.FullName -notlike "*\node_modules\*" -and
    $_.FullName -notlike "*\database\migrations\*" -and
    $_.FullName -notlike "*rename_kolom*"
}

$count = 0

# ============================================
# FASE 1: Ganti string literal (quoted strings)
# Aman karena hanya mengganti string dalam tanda kutip
# ============================================

$quotedMap = [ordered]@{
    # === Multi-word (longest first) ===
    "'late_tolerance_minutes'" = "'toleransi_terlambat_menit'"
    "'max_distance_meters'" = "'jarak_maksimal_meter'"
    "'office_latitude'" = "'lat_kantor'"
    "'office_longitude'" = "'lng_kantor'"
    "'clock_in_time'" = "'jam_masuk'"
    "'clock_out_time'" = "'jam_pulang'"
    "'recurring_type'" = "'jenis_pengulangan'"
    "'reminder_time'" = "'waktu_pengingat'"
    "'is_recurring'" = "'berulang'"
    "'is_completed'" = "'selesai'"
    "'is_notified'" = "'sudah_diberitahu'"
    "'is_active'" = "'aktif'"
    "'is_shared'" = "'dibagikan'"
    "'is_read'" = "'sudah_dibaca'"
    "'parent_phone'" = "'telepon_orang_tua'"
    "'parent_name'" = "'nama_orang_tua'"
    "'place_of_birth'" = "'tempat_lahir'"
    "'date_of_birth'" = "'tanggal_lahir'"
    "'class_level'" = "'tingkat_kelas'"
    "'academic_year'" = "'tahun_ajaran'"
    "'start_date'" = "'tanggal_mulai'"
    "'end_date'" = "'tanggal_selesai'"
    "'start_time'" = "'waktu_mulai'"
    "'end_time'" = "'waktu_selesai'"
    "'event_date'" = "'tanggal_acara'"
    "'entry_date'" = "'tanggal_masuk'"
    "'exit_date'" = "'tanggal_keluar'"
    "'admin_note'" = "'catatan_admin'"
    "'action_taken'" = "'tindakan'"
    "'address_in'" = "'alamat_masuk'"
    "'address_out'" = "'alamat_pulang'"
    "'latitude_in'" = "'lat_masuk'"
    "'longitude_in'" = "'lng_masuk'"
    "'latitude_out'" = "'lat_pulang'"
    "'longitude_out'" = "'lng_pulang'"
    "'photo_in'" = "'foto_masuk'"
    "'photo_out'" = "'foto_pulang'"
    "'clock_in'" = "'jam_masuk'"
    "'clock_out'" = "'jam_pulang'"
    "'file_path'" = "'path_file'"
    "'file_name'" = "'nama_file'"
    "'file_type'" = "'tipe_file'"
    "'file_size'" = "'ukuran_file'"
    "'due_date'" = "'tenggat'"
    "'ai_prompt'" = "'prompt_ai'"
    "'approved_by'" = "'disetujui_oleh'"
    "'approved_at'" = "'disetujui_pada'"
    "'created_by'" = "'dibuat_oleh'"
    "'uploaded_by'" = "'diunggah_oleh'"
    "'verified_by'" = "'diverifikasi_oleh'"
    "'evaluated_by'" = "'dievaluasi_oleh'"
    "'reported_by'" = "'dilaporkan_oleh'"
    "'student_id'" = "'siswa_id'"
    "'user_id'" = "'pengguna_id'"
    # === Single-word ===
    "'name'" = "'nama'"
    "'title'" = "'judul'"
    "'description'" = "'deskripsi'"
    "'category'" = "'kategori'"
    "'priority'" = "'prioritas'"
    "'attachment'" = "'lampiran'"
    "'location'" = "'lokasi'"
    "'content'" = "'konten'"
    "'message'" = "'pesan'"
    "'subject'" = "'mata_pelajaran'"
    "'gender'" = "'jenis_kelamin'"
    "'religion'" = "'agama'"
    "'organizer'" = "'penyelenggara'"
    "'situation'" = "'situasi'"
    "'result'" = "'hasil'"
    "'level'" = "'tingkat'"
    "'note'" = "'catatan'"
    "'link'" = "'tautan'"
    "'role'" = "'peran'"
    "'phone'" = "'telepon'"
    "'position'" = "'jabatan'"
    "'address'" = "'alamat'"
    "'photo'" = "'foto'"
    "'code'" = "'kode'"
    "'fields'" = "'kolom_isian'"
    "'template'" = "'templat'"
    "'type'" = "'jenis'"
    "'task'" = "'tugas'"
    "'action'" = "'aksi'"
}

foreach ($file in $files) {
    $content = [System.IO.File]::ReadAllText($file.FullName)
    $original = $content

    foreach ($key in $quotedMap.Keys) {
        $content = $content.Replace($key, $quotedMap[$key])
    }

    # Also handle double-quoted equivalents
    $content = $content.Replace('"user_id"', '"pengguna_id"')
    $content = $content.Replace('"created_by"', '"dibuat_oleh"')
    $content = $content.Replace('"uploaded_by"', '"diunggah_oleh"')
    $content = $content.Replace('"approved_by"', '"disetujui_oleh"')
    $content = $content.Replace('"name"', '"nama"')
    $content = $content.Replace('"title"', '"judul"')
    $content = $content.Replace('"role"', '"peran"')
    $content = $content.Replace('"file_path"', '"path_file"')
    $content = $content.Replace('"file_name"', '"nama_file"')

    if ($content -ne $original) {
        [System.IO.File]::WriteAllText($file.FullName, $content)
        $count++
        Write-Host "  [string] $($file.Name)"
    }
}
Write-Host "`n=== Fase 1 selesai: $count file diperbarui ==="

# ============================================
# FASE 2: Ganti property access (->column)
# Menggunakan (?!\w|\() agar tidak mengganti method calls
# ============================================
$count2 = 0

# Build regex replacement pairs: pattern => replacement
# Pattern uses (?!\w|\() to avoid matching ->name_something or ->name()
$propPairs = @(
    @('->late_tolerance_minutes(?!\w|\()', '->toleransi_terlambat_menit'),
    @('->max_distance_meters(?!\w|\()', '->jarak_maksimal_meter'),
    @('->office_latitude(?!\w|\()', '->lat_kantor'),
    @('->office_longitude(?!\w|\()', '->lng_kantor'),
    @('->clock_in_time(?!\w|\()', '->jam_masuk'),
    @('->clock_out_time(?!\w|\()', '->jam_pulang'),
    @('->recurring_type(?!\w|\()', '->jenis_pengulangan'),
    @('->reminder_time(?!\w|\()', '->waktu_pengingat'),
    @('->is_recurring(?!\w|\()', '->berulang'),
    @('->is_completed(?!\w|\()', '->selesai'),
    @('->is_notified(?!\w|\()', '->sudah_diberitahu'),
    @('->is_active(?!\w|\()', '->aktif'),
    @('->is_shared(?!\w|\()', '->dibagikan'),
    @('->is_read(?!\w|\()', '->sudah_dibaca'),
    @('->parent_phone(?!\w|\()', '->telepon_orang_tua'),
    @('->parent_name(?!\w|\()', '->nama_orang_tua'),
    @('->place_of_birth(?!\w|\()', '->tempat_lahir'),
    @('->date_of_birth(?!\w|\()', '->tanggal_lahir'),
    @('->class_level(?!\w|\()', '->tingkat_kelas'),
    @('->academic_year(?!\w|\()', '->tahun_ajaran'),
    @('->start_date(?!\w|\()', '->tanggal_mulai'),
    @('->end_date(?!\w|\()', '->tanggal_selesai'),
    @('->start_time(?!\w|\()', '->waktu_mulai'),
    @('->end_time(?!\w|\()', '->waktu_selesai'),
    @('->event_date(?!\w|\()', '->tanggal_acara'),
    @('->entry_date(?!\w|\()', '->tanggal_masuk'),
    @('->exit_date(?!\w|\()', '->tanggal_keluar'),
    @('->admin_note(?!\w|\()', '->catatan_admin'),
    @('->action_taken(?!\w|\()', '->tindakan'),
    @('->address_in(?!\w|\()', '->alamat_masuk'),
    @('->address_out(?!\w|\()', '->alamat_pulang'),
    @('->latitude_in(?!\w|\()', '->lat_masuk'),
    @('->longitude_in(?!\w|\()', '->lng_masuk'),
    @('->latitude_out(?!\w|\()', '->lat_pulang'),
    @('->longitude_out(?!\w|\()', '->lng_pulang'),
    @('->photo_in(?!\w|\()', '->foto_masuk'),
    @('->photo_out(?!\w|\()', '->foto_pulang'),
    @('->clock_in(?!\w|\()', '->jam_masuk'),
    @('->clock_out(?!\w|\()', '->jam_pulang'),
    @('->file_path(?!\w|\()', '->path_file'),
    @('->file_name(?!\w|\()', '->nama_file'),
    @('->file_type(?!\w|\()', '->tipe_file'),
    @('->file_size(?!\w|\()', '->ukuran_file'),
    @('->due_date(?!\w|\()', '->tenggat'),
    @('->ai_prompt(?!\w|\()', '->prompt_ai'),
    @('->approved_by(?!\w|\()', '->disetujui_oleh'),
    @('->approved_at(?!\w|\()', '->disetujui_pada'),
    @('->created_by(?!\w|\()', '->dibuat_oleh'),
    @('->uploaded_by(?!\w|\()', '->diunggah_oleh'),
    @('->verified_by(?!\w|\()', '->diverifikasi_oleh'),
    @('->evaluated_by(?!\w|\()', '->dievaluasi_oleh'),
    @('->reported_by(?!\w|\()', '->dilaporkan_oleh'),
    @('->student_id(?!\w|\()', '->siswa_id'),
    @('->user_id(?!\w|\()', '->pengguna_id'),
    # Single-word (order: longer first already done above)
    @('->name(?!\w|\()', '->nama'),
    @('->title(?!\w|\()', '->judul'),
    @('->description(?!\w|\()', '->deskripsi'),
    @('->category(?!\w|\()', '->kategori'),
    @('->priority(?!\w|\()', '->prioritas'),
    @('->attachment(?!\w|\()', '->lampiran'),
    @('->location(?!\w|\()', '->lokasi'),
    @('->content(?!\w|\()', '->konten'),
    @('->message(?!\w|\()', '->pesan'),
    @('->subject(?!\w|\()', '->mata_pelajaran'),
    @('->gender(?!\w|\()', '->jenis_kelamin'),
    @('->religion(?!\w|\()', '->agama'),
    @('->organizer(?!\w|\()', '->penyelenggara'),
    @('->situation(?!\w|\()', '->situasi'),
    @('->result(?!\w|\()', '->hasil'),
    @('->level(?!\w|\()', '->tingkat'),
    @('->link(?!\w|\()', '->tautan'),
    @('->role(?!\w|\()', '->peran'),
    @('->phone(?!\w|\()', '->telepon'),
    @('->position(?!\w|\()', '->jabatan'),
    @('->address(?!\w|\()', '->alamat'),
    @('->photo(?!\w|\()', '->foto'),
    @('->code(?!\w|\()', '->kode'),
    @('->fields(?!\w|\()', '->kolom_isian'),
    @('->template(?!\w|\()', '->templat'),
    @('->type(?!\w|\()', '->jenis'),
    @('->task(?!\w|\()', '->tugas'),
    @('->action(?!\w|\()', '->aksi'),
    @('->note(?!\w|\()', '->catatan'),
    @('->notes(?!\w|\()', '->catatan')
)

foreach ($file in $files) {
    $content = [System.IO.File]::ReadAllText($file.FullName)
    $original = $content

    foreach ($pair in $propPairs) {
        $content = [regex]::Replace($content, $pair[0], $pair[1])
    }

    if ($content -ne $original) {
        [System.IO.File]::WriteAllText($file.FullName, $content)
        $count2++
        Write-Host "  [prop] $($file.Name)"
    }
}
Write-Host "`n=== Fase 2 selesai: $count2 file diperbarui ==="

# ============================================
# FASE 3: Fix cast values yang salah terganti
# ============================================
$count3 = 0
foreach ($file in $files) {
    $content = [System.IO.File]::ReadAllText($file.FullName)
    $original = $content

    # Fix: => 'tanggal' seharusnya => 'date' (tipe cast Laravel)
    $content = [regex]::Replace($content, "=>\s*'tanggal'", "=> 'date'")
    # Fix: => 'catatan' seharusnya => 'text' (jika ada)
    # Hanya fix jika ini di dalam casts array (aman karena cast values memang English)

    if ($content -ne $original) {
        [System.IO.File]::WriteAllText($file.FullName, $content)
        $count3++
        Write-Host "  [fix-cast] $($file.Name)"
    }
}
Write-Host "`n=== Fase 3 selesai: $count3 file diperbaiki ==="

Write-Host "`n=== SELESAI: Semua rename kolom selesai ==="
