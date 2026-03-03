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

    // Profile
    Route::get('/profile', [Staff\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Staff\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [Staff\ProfileController::class, 'changePassword'])->name('profile.password');
});
