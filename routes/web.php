<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Staff;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Staff Management
    Route::resource('staff', Admin\StaffController::class);
    Route::patch('staff/{staff}/toggle-status', [Admin\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');

    // Attendance Management
    Route::get('/attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/report', [Admin\AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/settings', [Admin\AttendanceController::class, 'settings'])->name('attendance.settings');
    Route::put('/attendance/settings', [Admin\AttendanceController::class, 'updateSettings'])->name('attendance.settings.update');
    Route::get('/attendance/{attendance}', [Admin\AttendanceController::class, 'show'])->name('attendance.show');

    // Leave Request Management
    Route::get('/leave', [Admin\LeaveRequestController::class, 'index'])->name('leave.index');
    Route::get('/leave/{leaveRequest}', [Admin\LeaveRequestController::class, 'show'])->name('leave.show');
    Route::patch('/leave/{leaveRequest}/approve', [Admin\LeaveRequestController::class, 'approve'])->name('leave.approve');
    Route::patch('/leave/{leaveRequest}/reject', [Admin\LeaveRequestController::class, 'reject'])->name('leave.reject');

    // Report Management
    Route::get('/report', [Admin\ReportController::class, 'index'])->name('report.index');
    Route::get('/report/{report}', [Admin\ReportController::class, 'show'])->name('report.show');
    Route::patch('/report/{report}/status', [Admin\ReportController::class, 'updateStatus'])->name('report.update-status');

    // Event Management
    Route::resource('event', Admin\EventController::class);

    // Notification Management
    Route::get('/notification', [Admin\NotificationController::class, 'index'])->name('notification.index');
    Route::get('/notification/create', [Admin\NotificationController::class, 'create'])->name('notification.create');
    Route::post('/notification', [Admin\NotificationController::class, 'store'])->name('notification.store');
    Route::delete('/notification/{notification}', [Admin\NotificationController::class, 'destroy'])->name('notification.destroy');

    // Surat Management (Official Letters)
    Route::resource('surat', Admin\SuratController::class);
    Route::patch('/surat/{surat}/status', [Admin\SuratController::class, 'updateStatus'])->name('surat.update-status');

    // Document Management
    Route::resource('document', Admin\DocumentController::class);
    Route::get('/document-export', [Admin\DocumentController::class, 'export'])->name('document.export');

    // Export routes
    Route::get('/staff-export', [Admin\StaffController::class, 'export'])->name('staff.export');
    Route::get('/attendance-export', [Admin\AttendanceController::class, 'export'])->name('attendance.export');

    // ============================================================
    // NEW FEATURES - Kurikulum & Akademik
    // ============================================================
    Route::resource('kurikulum', Admin\CurriculumController::class);

    // Kesiswaan (Data Siswa)
    Route::resource('kesiswaan', Admin\StudentController::class);

    // Inventaris / Sarana Prasarana
    Route::resource('inventaris', Admin\InventarisController::class);

    // Keuangan
    Route::get('/keuangan', [Admin\FinanceController::class, 'index'])->name('keuangan.index');
    Route::get('/keuangan/create', [Admin\FinanceController::class, 'create'])->name('keuangan.create');
    Route::post('/keuangan', [Admin\FinanceController::class, 'store'])->name('keuangan.store');
    Route::get('/keuangan/budget', [Admin\FinanceController::class, 'budgetIndex'])->name('keuangan.budget');
    Route::post('/keuangan/budget', [Admin\FinanceController::class, 'budgetStore'])->name('keuangan.budget.store');
    Route::get('/keuangan/{keuangan}', [Admin\FinanceController::class, 'show'])->name('keuangan.show');
    Route::patch('/keuangan/{keuangan}/verify', [Admin\FinanceController::class, 'verify'])->name('keuangan.verify');
    Route::delete('/keuangan/{keuangan}', [Admin\FinanceController::class, 'destroy'])->name('keuangan.destroy');

    // Evaluasi Kinerja / PKG / BKD
    Route::get('/evaluasi/pkg', [Admin\EvaluasiController::class, 'pkgIndex'])->name('evaluasi.pkg');
    Route::get('/evaluasi/pkg/create', [Admin\EvaluasiController::class, 'pkgCreate'])->name('evaluasi.pkg.create');
    Route::post('/evaluasi/pkg', [Admin\EvaluasiController::class, 'pkgStore'])->name('evaluasi.pkg.store');

    // Asesmen P5 (Projek Penguatan Profil Pelajar Pancasila)
    Route::get('/evaluasi/p5', [Admin\EvaluasiController::class, 'p5Index'])->name('evaluasi.p5');
    Route::get('/evaluasi/p5/create', [Admin\EvaluasiController::class, 'p5Create'])->name('evaluasi.p5.create');
    Route::post('/evaluasi/p5', [Admin\EvaluasiController::class, 'p5Store'])->name('evaluasi.p5.store');

    // Metode STAR Analysis
    Route::get('/evaluasi/star', [Admin\EvaluasiController::class, 'starIndex'])->name('evaluasi.star');
    Route::get('/evaluasi/star/create', [Admin\EvaluasiController::class, 'starCreate'])->name('evaluasi.star.create');
    Route::post('/evaluasi/star', [Admin\EvaluasiController::class, 'starStore'])->name('evaluasi.star.store');

    // Bukti Fisik
    Route::get('/evaluasi/bukti-fisik', [Admin\EvaluasiController::class, 'buktiFisikIndex'])->name('evaluasi.bukti-fisik');
    Route::post('/evaluasi/bukti-fisik', [Admin\EvaluasiController::class, 'buktiFisikStore'])->name('evaluasi.bukti-fisik.store');
    Route::delete('/evaluasi/bukti-fisik/{evidence}', [Admin\EvaluasiController::class, 'buktiFisikDestroy'])->name('evaluasi.bukti-fisik.destroy');

    // Model Pembelajaran / Metode Teknologi
    Route::get('/evaluasi/learning', [Admin\EvaluasiController::class, 'learningIndex'])->name('evaluasi.learning');
    Route::get('/evaluasi/learning/create', [Admin\EvaluasiController::class, 'learningCreate'])->name('evaluasi.learning.create');
    Route::post('/evaluasi/learning', [Admin\EvaluasiController::class, 'learningStore'])->name('evaluasi.learning.store');

    // Akreditasi
    Route::get('/akreditasi', [Admin\AccreditationController::class, 'index'])->name('akreditasi.index');
    Route::get('/akreditasi/create', [Admin\AccreditationController::class, 'create'])->name('akreditasi.create');
    Route::post('/akreditasi', [Admin\AccreditationController::class, 'store'])->name('akreditasi.store');
    Route::get('/akreditasi/eds', [Admin\AccreditationController::class, 'edsIndex'])->name('akreditasi.eds');
    Route::post('/akreditasi/eds', [Admin\AccreditationController::class, 'edsStore'])->name('akreditasi.eds.store');
    Route::get('/akreditasi/{akreditasi}', [Admin\AccreditationController::class, 'show'])->name('akreditasi.show');
    Route::delete('/akreditasi/{akreditasi}', [Admin\AccreditationController::class, 'destroy'])->name('akreditasi.destroy');

    // Pengingat / Reminder
    Route::get('/reminder', [Admin\ReminderController::class, 'index'])->name('reminder.index');
    Route::get('/reminder/create', [Admin\ReminderController::class, 'create'])->name('reminder.create');
    Route::post('/reminder', [Admin\ReminderController::class, 'store'])->name('reminder.store');
    Route::patch('/reminder/{reminder}/toggle', [Admin\ReminderController::class, 'toggleComplete'])->name('reminder.toggle');
    Route::delete('/reminder/{reminder}', [Admin\ReminderController::class, 'destroy'])->name('reminder.destroy');
});

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {

    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');

    // Attendance
    Route::get('/attendance', [Staff\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [Staff\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [Staff\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendance/{attendance}', [Staff\AttendanceController::class, 'show'])->name('attendance.show');
    Route::patch('/attendance/{attendance}/note', [Staff\AttendanceController::class, 'updateNote'])->name('attendance.note');

    // Leave Request
    Route::get('/leave', [Staff\LeaveRequestController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [Staff\LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('/leave', [Staff\LeaveRequestController::class, 'store'])->name('leave.store');
    Route::get('/leave/{leaveRequest}', [Staff\LeaveRequestController::class, 'show'])->name('leave.show');
    Route::delete('/leave/{leaveRequest}', [Staff\LeaveRequestController::class, 'destroy'])->name('leave.destroy');

    // Report
    Route::resource('report', Staff\ReportController::class);

    // Event
    Route::get('/event', [Staff\EventController::class, 'index'])->name('event.index');
    Route::get('/event/{event}', [Staff\EventController::class, 'show'])->name('event.show');

    // Notification
    Route::get('/notification', [Staff\NotificationController::class, 'index'])->name('notification.index');
    Route::patch('/notification/{notification}/read', [Staff\NotificationController::class, 'markAsRead'])->name('notification.read');
    Route::patch('/notification/read-all', [Staff\NotificationController::class, 'markAllAsRead'])->name('notification.read-all');

    // Surat (Official Letters)
    Route::get('/surat', [Staff\SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/create', [Staff\SuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [Staff\SuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{surat}', [Staff\SuratController::class, 'show'])->name('surat.show');

    // Document
    Route::get('/document', [Staff\DocumentController::class, 'index'])->name('document.index');
    Route::get('/document/{document}', [Staff\DocumentController::class, 'show'])->name('document.show');
    Route::post('/document', [Staff\DocumentController::class, 'upload'])->name('document.upload');

    // ============================================================
    // NEW FEATURES for Staff
    // ============================================================

    // Kurikulum (read-only for staff)
    Route::get('/kurikulum', [Staff\CurriculumController::class, 'index'])->name('kurikulum.index');
    Route::get('/kurikulum/{kurikulum}', [Staff\CurriculumController::class, 'show'])->name('kurikulum.show');
    Route::post('/kurikulum', [Staff\CurriculumController::class, 'store'])->name('kurikulum.store');

    // Kesiswaan (staff can view/add)
    Route::get('/kesiswaan', [Staff\StudentController::class, 'index'])->name('kesiswaan.index');
    Route::get('/kesiswaan/{kesiswaan}', [Staff\StudentController::class, 'show'])->name('kesiswaan.show');

    // Inventaris (view & report damage)
    Route::get('/inventaris', [Staff\InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/{inventaris}', [Staff\InventarisController::class, 'show'])->name('inventaris.show');
    Route::post('/inventaris/damage', [Staff\InventarisController::class, 'reportDamage'])->name('inventaris.damage');

    // Evaluasi - PKG/BKD (staff can view their own)
    Route::get('/evaluasi/pkg', [Staff\EvaluasiController::class, 'pkgIndex'])->name('evaluasi.pkg');
    Route::get('/evaluasi/p5', [Staff\EvaluasiController::class, 'p5Index'])->name('evaluasi.p5');
    Route::get('/evaluasi/star', [Staff\EvaluasiController::class, 'starIndex'])->name('evaluasi.star');
    Route::post('/evaluasi/star', [Staff\EvaluasiController::class, 'starStore'])->name('evaluasi.star.store');
    Route::get('/evaluasi/bukti-fisik', [Staff\EvaluasiController::class, 'buktiFisikIndex'])->name('evaluasi.bukti-fisik');
    Route::post('/evaluasi/bukti-fisik', [Staff\EvaluasiController::class, 'buktiFisikStore'])->name('evaluasi.bukti-fisik.store');
    Route::get('/evaluasi/learning', [Staff\EvaluasiController::class, 'learningIndex'])->name('evaluasi.learning');
    Route::post('/evaluasi/learning', [Staff\EvaluasiController::class, 'learningStore'])->name('evaluasi.learning.store');

    // Reminder (staff can view their reminders)
    Route::get('/reminder', [Staff\ReminderController::class, 'index'])->name('reminder.index');
    Route::patch('/reminder/{reminder}/complete', [Staff\ReminderController::class, 'markComplete'])->name('reminder.complete');

    // Profile
    Route::get('/profile', [Staff\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Staff\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [Staff\ProfileController::class, 'changePassword'])->name('profile.password');
});
