<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\TermsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\UsageReportController as AdminUsageReportController;
use App\Http\Controllers\QaManager\DashboardController as QaManagerDashboardController;
use App\Http\Controllers\QaManager\CategoryController;
use App\Http\Controllers\QaManager\DepartmentController as QaManagerDepartmentController;
use App\Http\Controllers\QaManager\StaffController as QaManagerStaffController;
use App\Http\Controllers\QaManager\SettingController as QaManagerSettingController;
use App\Http\Controllers\QaManager\AuditLogController as QaManagerAuditLogController;
use App\Http\Controllers\QaManager\BacklogController as QaManagerBacklogController;
use App\Http\Controllers\QaManager\ReportController as QaManagerReportController;
use App\Http\Controllers\QaCoordinator\DashboardController as QaCoordinatorDashboardController;
use App\Http\Controllers\QaCoordinator\IdeaController as QaCoordinatorIdeaController; // ADD THIS LINE
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\AccountController as StaffAccountController;

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

    // Report Inappropriate Idea
    Route::post('/ideas/{idea}/report', [ReportController::class, 'store'])->name('ideas.report');

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

        // Usage Reports
        Route::get('/reports/usage', [AdminUsageReportController::class, 'index'])->name('reports.usage');

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

        // Idea Visibility
        Route::patch('/ideas/{idea}/toggle-hidden', [IdeaController::class, 'toggleHidden'])->name('ideas.toggle-hidden');
        Route::patch('/comments/{comment}/toggle-hidden', [CommentController::class, 'toggleHidden'])->name('comments.toggle-hidden');
        Route::patch('/users/{user}/toggle-status', [QaManagerDashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');

        // Category Management
        Route::resource('categories', CategoryController::class)->except(['show']);

        // QA Manager Admin Panel
        Route::resource('departments', QaManagerDepartmentController::class)->except(['show']);
        Route::resource('staff', QaManagerStaffController::class)->parameters(['staff' => 'staff'])->except(['show']);
        Route::get('/settings', [QaManagerSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [QaManagerSettingController::class, 'update'])->name('settings.update');
        Route::get('/audit-logs', [QaManagerAuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/export', [QaManagerAuditLogController::class, 'export'])->name('audit-logs.export');
        Route::get('/university-backlog', [QaManagerBacklogController::class, 'index'])->name('backlog.index');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\QaManager\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\QaManager\ProfileController::class, 'update'])->name('profile.update');

        // Reports
        Route::get('/reports/statistics', [QaManagerReportController::class, 'statistics'])->name('reports.statistics');
        Route::get('/reports/exceptions', [QaManagerReportController::class, 'exceptionReports'])->name('reports.exceptions');
        Route::get('/reports/download-csv', [QaManagerReportController::class, 'downloadCsv'])->name('reports.download-csv');
        Route::get('/reports/download-documents', [QaManagerReportController::class, 'downloadDocuments'])->name('reports.download-documents');

    });

/*
|--------------------------------------------------------------------------
| QA Coordinator Routes (First Group)
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
        Route::get('/account', [StaffAccountController::class, 'edit'])->name('account.edit');
        Route::put('/account', [StaffAccountController::class, 'update'])->name('account.update');

    });

/*
|--------------------------------------------------------------------------
| QA Coordinator Routes (Extended)
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

        // Profile
        Route::get('/profile', [\App\Http\Controllers\QaCoordinator\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\QaCoordinator\ProfileController::class, 'update'])->name('profile.update');

        // Staff management
        Route::get('/staff', [QaCoordinatorDashboardController::class, 'staffList'])->name('staff.index');

        // NEW: Idea view routes for QA Coordinator (ADD THESE ROUTES)
        Route::get('/department-ideas', [QaCoordinatorIdeaController::class, 'departmentIdeas'])->name('department.ideas');
        Route::get('/popular-ideas', [QaCoordinatorIdeaController::class, 'popularIdeas'])->name('popular.ideas');
        Route::get('/latest-ideas', [QaCoordinatorIdeaController::class, 'latestIdeas'])->name('latest.ideas');

    });