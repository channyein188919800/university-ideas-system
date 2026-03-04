<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\TermsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\QaManager\DashboardController as QaManagerDashboardController;
use App\Http\Controllers\QaManager\CategoryController;
use App\Http\Controllers\QaManager\ReportController;
use App\Http\Controllers\QaCoordinator\DashboardController as QaCoordinatorDashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Terms and Conditions
Route::middleware(['auth'])->group(function () {
    Route::get('/terms', [TermsController::class, 'show'])->name('terms.show');
    Route::post('/terms', [TermsController::class, 'accept'])->name('terms.accept');
});

/*
|--------------------------------------------------------------------------
| Idea Routes (Public - Viewing)
|--------------------------------------------------------------------------
*/

Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');
Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('ideas.show')->whereNumber('idea');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'terms'])->group(function () {
    
    // Idea Submission
    Route::get('/ideas/create', [IdeaController::class, 'create'])->name('ideas.create');
    Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store');
    
    // Voting
    Route::post('/ideas/{idea}/vote', [IdeaController::class, 'vote'])->name('ideas.vote');
    
    // Comments
    Route::post('/ideas/{idea}/comments', [CommentController::class, 'store'])->name('comments.store');
    
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'terms', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', AdminUserController::class);
    
    // Department Management
    Route::resource('departments', AdminDepartmentController::class);
    
    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Idea Moderation
    Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('ideas.destroy');

    // Audit Logs
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [AdminAuditLogController::class, 'export'])->name('audit-logs.export');
    
});

/*
|--------------------------------------------------------------------------
| QA Manager Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'terms', 'role:qa_manager'])
    ->prefix('qa-manager')
    ->name('qa-manager.')
    ->group(function () {
        
    Route::get('/dashboard', [QaManagerDashboardController::class, 'index'])->name('dashboard');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Reports
    Route::get('/reports/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');
    Route::get('/reports/exceptions', [ReportController::class, 'exceptionReports'])->name('reports.exceptions');
    Route::get('/reports/download-csv', [ReportController::class, 'downloadCsv'])->name('reports.download-csv');
    Route::get('/reports/download-documents', [ReportController::class, 'downloadDocuments'])->name('reports.download-documents');
    
});

/*
|--------------------------------------------------------------------------
| QA Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'terms', 'role:qa_coordinator'])
    ->prefix('qa-coordinator')
    ->name('qa-coordinator.')
    ->group(function () {
        
    Route::get('/dashboard', [QaCoordinatorDashboardController::class, 'index'])->name('dashboard');
    
});

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'terms', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
});

/*
|--------------------------------------------------------------------------
| QA Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'terms', 'role:qa_coordinator'])
    ->prefix('qa-coordinator')
    ->name('qa-coordinator.')
    ->group(function () {
        
    // Dashboard
    Route::get('/dashboard', [QaCoordinatorDashboardController::class, 'index'])->name('dashboard');
    
    // Reminder endpoints (for AJAX calls in dashboard)
    Route::post('/remind-all', [QaCoordinatorDashboardController::class, 'remindAll'])->name('remind-all');
    Route::post('/remind-staff/{user}', [QaCoordinatorDashboardController::class, 'remindStaff'])->name('remind-staff');
    
    // Chart data endpoint (for period filtering)
    Route::get('/chart-data', [QaCoordinatorDashboardController::class, 'getChartData'])->name('chart-data');
    
    // Statistics page
    Route::get('/statistics', [QaCoordinatorDashboardController::class, 'statistics'])->name('statistics');
    
    // Exception reports
    Route::get('/reports/exceptions', [QaCoordinatorDashboardController::class, 'exceptions'])->name('reports.exceptions');
    
    // Notifications
    Route::get('/notifications', [QaCoordinatorDashboardController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [QaCoordinatorDashboardController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [QaCoordinatorDashboardController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Staff management
    Route::get('/staff', [QaCoordinatorDashboardController::class, 'staffList'])->name('staff.index');
    
});
