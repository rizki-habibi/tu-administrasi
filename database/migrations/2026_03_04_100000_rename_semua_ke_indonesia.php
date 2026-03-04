<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Matikan pengecekan foreign key selama proses rename
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // ================================================================
        // FASE 1: Hapus semua Foreign Key Constraints
        // ================================================================
        $this->hapusSemuaForeignKey();

        // ================================================================
        // FASE 2: Rename semua kolom per tabel
        // ================================================================
        $this->renameSemuaKolom();

        // ================================================================
        // FASE 3: Rename semua tabel
        // ================================================================
        $this->renameSemuaTabel();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ================================================================
        // FASE 4: Buat ulang Foreign Key Constraints
        // ================================================================
        $this->buatUlangForeignKey();
    }

    // ====================================================================
    // FASE 1: Hapus semua FK constraints
    // ====================================================================
    private function hapusSemuaForeignKey(): void
    {
        // attendances
        Schema::table('attendances', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
        });

        // leave_requests
        Schema::table('leave_requests', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropForeign(['approved_by']);
        });

        // reports
        Schema::table('reports', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
        });

        // events
        Schema::table('events', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // notifications
        Schema::table('notifications', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
        });

        // documents
        Schema::table('documents', function (Blueprint $t) {
            $t->dropForeign(['uploaded_by']);
        });

        // surats
        Schema::table('surats', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
            $t->dropForeign(['approved_by']);
        });

        // document_templates
        Schema::table('document_templates', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // word_documents
        Schema::table('word_documents', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
        });

        // curriculum_documents
        Schema::table('curriculum_documents', function (Blueprint $t) {
            $t->dropForeign(['uploaded_by']);
        });

        // student_records
        Schema::table('student_records', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // student_achievements
        Schema::table('student_achievements', function (Blueprint $t) {
            $t->dropForeign(['student_id']);
        });

        // student_violations
        Schema::table('student_violations', function (Blueprint $t) {
            $t->dropForeign(['student_id']);
            $t->dropForeign(['reported_by']);
        });

        // inventaris
        Schema::table('inventaris', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // damage_reports
        Schema::table('damage_reports', function (Blueprint $t) {
            $t->dropForeign(['inventaris_id']);
            $t->dropForeign(['reported_by']);
        });

        // finance_records
        Schema::table('finance_records', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
            $t->dropForeign(['verified_by']);
        });

        // budgets
        Schema::table('budgets', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // accreditation_documents
        Schema::table('accreditation_documents', function (Blueprint $t) {
            $t->dropForeign(['uploaded_by']);
        });

        // school_evaluations
        Schema::table('school_evaluations', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // teacher_evaluations
        Schema::table('teacher_evaluations', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropForeign(['evaluated_by']);
        });

        // p5_assessments
        Schema::table('p5_assessments', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // star_analyses
        Schema::table('star_analyses', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // physical_evidences
        Schema::table('physical_evidences', function (Blueprint $t) {
            $t->dropForeign(['uploaded_by']);
        });

        // learning_methods
        Schema::table('learning_methods', function (Blueprint $t) {
            $t->dropForeign(['created_by']);
        });

        // reminders
        Schema::table('reminders', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropForeign(['created_by']);
        });

        // skp
        Schema::table('skp', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropForeign(['approved_by']);
        });

        // ucapan_ulang_tahun
        Schema::table('ucapan_ulang_tahun', function (Blueprint $t) {
            $t->dropForeign(['pengirim_id']);
            $t->dropForeign(['penerima_id']);
        });

        // catatan_beranda
        Schema::table('catatan_beranda', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
        });
    }

    // ====================================================================
    // FASE 2: Rename semua kolom
    // ====================================================================
    private function renameSemuaKolom(): void
    {
        // ---- users (pengguna) ----
        Schema::table('users', function (Blueprint $t) {
            $t->renameColumn('name', 'nama');
            $t->renameColumn('role', 'peran');
            $t->renameColumn('phone', 'telepon');
            $t->renameColumn('position', 'jabatan');
            $t->renameColumn('photo', 'foto');
            $t->renameColumn('address', 'alamat');
            $t->renameColumn('is_active', 'aktif');
        });

        // ---- attendances (kehadiran) ----
        Schema::table('attendances', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('date', 'tanggal');
            $t->renameColumn('clock_in', 'jam_masuk');
            $t->renameColumn('clock_out', 'jam_pulang');
            $t->renameColumn('latitude_in', 'lat_masuk');
            $t->renameColumn('longitude_in', 'lng_masuk');
            $t->renameColumn('latitude_out', 'lat_pulang');
            $t->renameColumn('longitude_out', 'lng_pulang');
            $t->renameColumn('address_in', 'alamat_masuk');
            $t->renameColumn('address_out', 'alamat_pulang');
            $t->renameColumn('photo_in', 'foto_masuk');
            $t->renameColumn('photo_out', 'foto_pulang');
            $t->renameColumn('note', 'catatan');
        });

        // ---- leave_requests (pengajuan_izin) ----
        Schema::table('leave_requests', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('type', 'jenis');
            $t->renameColumn('start_date', 'tanggal_mulai');
            $t->renameColumn('end_date', 'tanggal_selesai');
            $t->renameColumn('reason', 'alasan');
            $t->renameColumn('attachment', 'lampiran');
            $t->renameColumn('approved_by', 'disetujui_oleh');
            $t->renameColumn('admin_note', 'catatan_admin');
        });

        // ---- reports (laporan) ----
        Schema::table('reports', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('title', 'judul');
            $t->renameColumn('description', 'deskripsi');
            $t->renameColumn('category', 'kategori');
            $t->renameColumn('priority', 'prioritas');
            $t->renameColumn('attachment', 'lampiran');
        });

        // ---- events (acara) ----
        Schema::table('events', function (Blueprint $t) {
            $t->renameColumn('created_by', 'dibuat_oleh');
            $t->renameColumn('title', 'judul');
            $t->renameColumn('description', 'deskripsi');
            $t->renameColumn('event_date', 'tanggal_acara');
            $t->renameColumn('start_time', 'waktu_mulai');
            $t->renameColumn('end_time', 'waktu_selesai');
            $t->renameColumn('location', 'lokasi');
            $t->renameColumn('type', 'jenis');
        });

        // ---- notifications (notifikasi) ----
        Schema::table('notifications', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('title', 'judul');
            $t->renameColumn('message', 'pesan');
            $t->renameColumn('type', 'jenis');
            $t->renameColumn('is_read', 'sudah_dibaca');
            $t->renameColumn('link', 'tautan');
        });

        // ---- attendance_settings (pengaturan_kehadiran) ----
        Schema::table('attendance_settings', function (Blueprint $t) {
            $t->renameColumn('clock_in_time', 'jam_masuk');
            $t->renameColumn('clock_out_time', 'jam_pulang');
            $t->renameColumn('late_tolerance_minutes', 'toleransi_terlambat_menit');
            $t->renameColumn('office_latitude', 'lat_kantor');
            $t->renameColumn('office_longitude', 'lng_kantor');
            $t->renameColumn('max_distance_meters', 'jarak_maksimal_meter');
        });

        // ---- documents (dokumen) ----
        Schema::table('documents', function (Blueprint $t) {
            $t->renameColumn('title', 'judul');
            $t->renameColumn('description', 'deskripsi');
            $t->renameColumn('category', 'kategori');
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('file_type', 'tipe_file');
            $t->renameColumn('file_size', 'ukuran_file');
            $t->renameColumn('uploaded_by', 'diunggah_oleh');
        });

        // ---- surats (surat) ----
        Schema::table('surats', function (Blueprint $t) {
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('created_by', 'dibuat_oleh');
            $t->renameColumn('approved_by', 'disetujui_oleh');
        });

        // ---- document_templates (template_dokumen) ----
        Schema::table('document_templates', function (Blueprint $t) {
            $t->renameColumn('name', 'nama');
            $t->renameColumn('code', 'kode');
            $t->renameColumn('category', 'kategori');
            $t->renameColumn('content', 'konten');
            $t->renameColumn('fields', 'kolom_isian');
            $t->renameColumn('is_active', 'aktif');
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- word_documents (dokumen_word) ----
        Schema::table('word_documents', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('title', 'judul');
            $t->renameColumn('category', 'kategori');
            $t->renameColumn('content', 'konten');
            $t->renameColumn('ai_prompt', 'prompt_ai');
            $t->renameColumn('template', 'templat');
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('is_shared', 'dibagikan');
        });

        // ---- curriculum_documents (dokumen_kurikulum) ----
        Schema::table('curriculum_documents', function (Blueprint $t) {
            $t->renameColumn('title', 'judul');
            $t->renameColumn('description', 'deskripsi');
            $t->renameColumn('type', 'jenis');
            $t->renameColumn('academic_year', 'tahun_ajaran');
            $t->renameColumn('subject', 'mata_pelajaran');
            $t->renameColumn('class_level', 'tingkat_kelas');
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('file_type', 'tipe_file');
            $t->renameColumn('file_size', 'ukuran_file');
            $t->renameColumn('uploaded_by', 'diunggah_oleh');
        });

        // ---- student_records (data_siswa) ----
        Schema::table('student_records', function (Blueprint $t) {
            $t->renameColumn('name', 'nama');
            $t->renameColumn('class', 'kelas');
            $t->renameColumn('academic_year', 'tahun_ajaran');
            $t->renameColumn('gender', 'jenis_kelamin');
            $t->renameColumn('place_of_birth', 'tempat_lahir');
            $t->renameColumn('date_of_birth', 'tanggal_lahir');
            $t->renameColumn('religion', 'agama');
            $t->renameColumn('address', 'alamat');
            $t->renameColumn('parent_name', 'nama_orang_tua');
            $t->renameColumn('parent_phone', 'telepon_orang_tua');
            $t->renameColumn('photo', 'foto');
            $t->renameColumn('entry_date', 'tanggal_masuk');
            $t->renameColumn('exit_date', 'tanggal_keluar');
            $t->renameColumn('notes', 'catatan');
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- student_achievements (prestasi_siswa) ----
        Schema::table('student_achievements', function (Blueprint $t) {
            $t->renameColumn('student_id', 'siswa_id');
            $t->renameColumn('title', 'judul');
            $t->renameColumn('level', 'tingkat');
            $t->renameColumn('type', 'jenis');
            $t->renameColumn('date', 'tanggal');
            $t->renameColumn('organizer', 'penyelenggara');
            $t->renameColumn('result', 'hasil');
            $t->renameColumn('file_path', 'path_file');
        });

        // ---- student_violations (pelanggaran_siswa) ----
        Schema::table('student_violations', function (Blueprint $t) {
            $t->renameColumn('student_id', 'siswa_id');
            $t->renameColumn('date', 'tanggal');
            $t->renameColumn('type', 'jenis');
            $t->renameColumn('description', 'deskripsi');
            $t->renameColumn('action_taken', 'tindakan');
            $t->renameColumn('reported_by', 'dilaporkan_oleh');
        });

        // ---- inventaris ----
        Schema::table('inventaris', function (Blueprint $t) {
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- damage_reports (laporan_kerusakan) ----
        Schema::table('damage_reports', function (Blueprint $t) {
            $t->renameColumn('reported_by', 'dilaporkan_oleh');
        });

        // ---- finance_records (catatan_keuangan) ----
        Schema::table('finance_records', function (Blueprint $t) {
            $t->renameColumn('created_by', 'dibuat_oleh');
            $t->renameColumn('verified_by', 'diverifikasi_oleh');
        });

        // ---- budgets (anggaran) ----
        Schema::table('budgets', function (Blueprint $t) {
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- accreditation_documents (dokumen_akreditasi) ----
        Schema::table('accreditation_documents', function (Blueprint $t) {
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('uploaded_by', 'diunggah_oleh');
        });

        // ---- school_evaluations (evaluasi_sekolah) ----
        Schema::table('school_evaluations', function (Blueprint $t) {
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- teacher_evaluations (evaluasi_guru) ----
        Schema::table('teacher_evaluations', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('evaluated_by', 'dievaluasi_oleh');
        });

        // ---- p5_assessments (penilaian_p5) ----
        Schema::table('p5_assessments', function (Blueprint $t) {
            $t->renameColumn('academic_year', 'tahun_ajaran');
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- star_analyses (analisis_star) ----
        Schema::table('star_analyses', function (Blueprint $t) {
            $t->renameColumn('situation', 'situasi');
            $t->renameColumn('task', 'tugas');
            $t->renameColumn('action', 'aksi');
            $t->renameColumn('result', 'hasil');
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- physical_evidences (bukti_fisik) ----
        Schema::table('physical_evidences', function (Blueprint $t) {
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('file_type', 'tipe_file');
            $t->renameColumn('file_size', 'ukuran_file');
            $t->renameColumn('uploaded_by', 'diunggah_oleh');
        });

        // ---- learning_methods (metode_pembelajaran) ----
        Schema::table('learning_methods', function (Blueprint $t) {
            $t->renameColumn('file_path', 'path_file');
            $t->renameColumn('file_name', 'nama_file');
            $t->renameColumn('created_by', 'dibuat_oleh');
        });

        // ---- reminders (pengingat) ----
        Schema::table('reminders', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('title', 'judul');
            $t->renameColumn('description', 'deskripsi');
            $t->renameColumn('type', 'jenis');
            $t->renameColumn('due_date', 'tenggat');
            $t->renameColumn('reminder_time', 'waktu_pengingat');
            $t->renameColumn('is_recurring', 'berulang');
            $t->renameColumn('recurring_type', 'jenis_pengulangan');
            $t->renameColumn('created_by', 'dibuat_oleh');
            $t->renameColumn('is_completed', 'selesai');
            $t->renameColumn('is_notified', 'sudah_diberitahu');
        });

        // ---- skp ----
        Schema::table('skp', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
            $t->renameColumn('approved_by', 'disetujui_oleh');
            $t->renameColumn('approved_at', 'disetujui_pada');
        });

        // ---- catatan_beranda ----
        Schema::table('catatan_beranda', function (Blueprint $t) {
            $t->renameColumn('user_id', 'pengguna_id');
        });
    }

    // ====================================================================
    // FASE 3: Rename semua tabel
    // ====================================================================
    private function renameSemuaTabel(): void
    {
        Schema::rename('users', 'pengguna');
        Schema::rename('attendances', 'kehadiran');
        Schema::rename('leave_requests', 'pengajuan_izin');
        Schema::rename('reports', 'laporan');
        Schema::rename('events', 'acara');
        Schema::rename('notifications', 'notifikasi');
        Schema::rename('attendance_settings', 'pengaturan_kehadiran');
        Schema::rename('documents', 'dokumen');
        Schema::rename('surats', 'surat');
        Schema::rename('document_templates', 'template_dokumen');
        Schema::rename('word_documents', 'dokumen_word');
        Schema::rename('curriculum_documents', 'dokumen_kurikulum');
        Schema::rename('student_records', 'data_siswa');
        Schema::rename('student_achievements', 'prestasi_siswa');
        Schema::rename('student_violations', 'pelanggaran_siswa');
        Schema::rename('damage_reports', 'laporan_kerusakan');
        Schema::rename('finance_records', 'catatan_keuangan');
        Schema::rename('budgets', 'anggaran');
        Schema::rename('accreditation_documents', 'dokumen_akreditasi');
        Schema::rename('school_evaluations', 'evaluasi_sekolah');
        Schema::rename('teacher_evaluations', 'evaluasi_guru');
        Schema::rename('p5_assessments', 'penilaian_p5');
        Schema::rename('star_analyses', 'analisis_star');
        Schema::rename('physical_evidences', 'bukti_fisik');
        Schema::rename('learning_methods', 'metode_pembelajaran');
        Schema::rename('reminders', 'pengingat');
        // inventaris, skp, ucapan_ulang_tahun, catatan_beranda → sudah Indonesia
    }

    // ====================================================================
    // FASE 4: Buat ulang semua FK constraints
    // ====================================================================
    private function buatUlangForeignKey(): void
    {
        // kehadiran
        Schema::table('kehadiran', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // pengajuan_izin
        Schema::table('pengajuan_izin', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('disetujui_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });

        // laporan
        Schema::table('laporan', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // acara
        Schema::table('acara', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // notifikasi
        Schema::table('notifikasi', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // dokumen
        Schema::table('dokumen', function (Blueprint $t) {
            $t->foreign('diunggah_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // surat
        Schema::table('surat', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('disetujui_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });

        // template_dokumen
        Schema::table('template_dokumen', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // dokumen_word
        Schema::table('dokumen_word', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // dokumen_kurikulum
        Schema::table('dokumen_kurikulum', function (Blueprint $t) {
            $t->foreign('diunggah_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // data_siswa
        Schema::table('data_siswa', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // prestasi_siswa
        Schema::table('prestasi_siswa', function (Blueprint $t) {
            $t->foreign('siswa_id')->references('id')->on('data_siswa')->onDelete('cascade');
        });

        // pelanggaran_siswa
        Schema::table('pelanggaran_siswa', function (Blueprint $t) {
            $t->foreign('siswa_id')->references('id')->on('data_siswa')->onDelete('cascade');
            $t->foreign('dilaporkan_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // inventaris
        Schema::table('inventaris', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // laporan_kerusakan
        Schema::table('laporan_kerusakan', function (Blueprint $t) {
            $t->foreign('inventaris_id')->references('id')->on('inventaris')->onDelete('cascade');
            $t->foreign('dilaporkan_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // catatan_keuangan
        Schema::table('catatan_keuangan', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('diverifikasi_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });

        // anggaran
        Schema::table('anggaran', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // dokumen_akreditasi
        Schema::table('dokumen_akreditasi', function (Blueprint $t) {
            $t->foreign('diunggah_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // evaluasi_sekolah
        Schema::table('evaluasi_sekolah', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // evaluasi_guru
        Schema::table('evaluasi_guru', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('dievaluasi_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });

        // penilaian_p5
        Schema::table('penilaian_p5', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // analisis_star
        Schema::table('analisis_star', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // bukti_fisik
        Schema::table('bukti_fisik', function (Blueprint $t) {
            $t->foreign('diunggah_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // metode_pembelajaran
        Schema::table('metode_pembelajaran', function (Blueprint $t) {
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // pengingat
        Schema::table('pengingat', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('dibuat_oleh')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // skp
        Schema::table('skp', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('disetujui_oleh')->references('id')->on('pengguna')->onDelete('set null');
        });

        // ucapan_ulang_tahun
        Schema::table('ucapan_ulang_tahun', function (Blueprint $t) {
            $t->foreign('pengirim_id')->references('id')->on('pengguna')->onDelete('cascade');
            $t->foreign('penerima_id')->references('id')->on('pengguna')->onDelete('cascade');
        });

        // catatan_beranda
        Schema::table('catatan_beranda', function (Blueprint $t) {
            $t->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Terlalu kompleks untuk reverse; gunakan migrate:fresh
    }
};
