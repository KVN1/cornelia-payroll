<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// ── Auth (public) ──────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── All logged-in users ────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/',           [TimeLogController::class, 'index'])->name('index');
        Route::post('time-in',    [TimeLogController::class, 'timeIn'])->name('time-in');
        Route::post('break-out',  [TimeLogController::class, 'breakOut'])->name('break-out');
        Route::post('break-in',   [TimeLogController::class, 'breakIn'])->name('break-in');
        Route::post('time-out',   [TimeLogController::class, 'timeOut'])->name('time-out');
        Route::post('pin-clock',  [TimeLogController::class, 'pinClock'])->name('pin-clock');
    });

    // Password change request (staff)
    Route::get('/change-password',  [AuthController::class, 'showChangePassword'])->name('auth.change-password');
    Route::post('/change-password', [AuthController::class, 'requestPasswordChange'])->name('auth.request-password-change');

});

// ── Admin / HR / Manager only ──────────────────────────────
Route::middleware(['auth', 'role:admin,hr,manager'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/archive', [EmployeeController::class, 'archive'])->name('employees.archive');
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');

    // Payroll
    Route::resource('payroll', PayrollController::class)->except(['edit','update','destroy']);
    Route::post('payroll/{payroll}/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
    Route::post('payroll-semi-monthly', [PayrollController::class, 'storeSemiMonthly'])->name('payroll.store-semi-monthly');
    Route::get('payroll-record/{record}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');

    // Leaves
    Route::resource('leaves', LeaveController::class)->only(['index','create','store']);
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject',  [LeaveController::class, 'reject'])->name('leaves.reject');

    // Password requests management
    Route::get('/password-requests', [AuthController::class, 'passwordRequests'])->name('auth.password-requests');
    Route::post('/password-requests/{passwordRequest}/approve', [AuthController::class, 'approvePasswordChange'])->name('auth.approve-password');
    Route::post('/password-requests/{passwordRequest}/reject',  [AuthController::class, 'rejectPasswordChange'])->name('auth.reject-password');
    Route::get('/users/{user}/reset-password',  [AuthController::class, 'showResetPassword'])->name('auth.reset-password-form');
    Route::put('/users/{user}/reset-password',  [AuthController::class, 'resetPassword'])->name('auth.reset-password');

    // Settings
    Route::get('/settings',                              [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/users',                       [SettingsController::class, 'storeUser'])->name('settings.users.store');
    Route::put('/settings/users/{user}',                 [SettingsController::class, 'updateUser'])->name('settings.users.update');
    Route::delete('/settings/users/{user}',              [SettingsController::class, 'deleteUser'])->name('settings.users.delete');
    Route::put('/settings/users/{user}/reset-password',  [SettingsController::class, 'resetUserPassword'])->name('settings.users.reset-password');
    Route::post('/settings/update-username',             [SettingsController::class, 'updateUsername'])->name('settings.update-username');
    Route::post('/settings/change-password-direct',      [SettingsController::class, 'changePasswordDirect'])->name('settings.change-password-direct');
    Route::post('/settings/deductions',                  [SettingsController::class, 'updateDeductions'])->name('settings.deductions.update');

});
