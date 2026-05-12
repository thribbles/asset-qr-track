<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\LoanController;

// QR Debugging Routes (remove after fixing)
Route::get('/qr-check', function () {
    return response()->json([
        'app_url' => config('app.url'),
        'app_public_url' => config('app.public_url'),
        'env_public_url' => env('APP_PUBLIC_URL', 'NOT SET - Add to .env file!'),
        'solution' => 'Add this to .env: APP_PUBLIC_URL=http://YOUR_SERVER_IP/assect/public',
    ]);
});
Route::get('/clear-all-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'All caches cleared! Refresh your page.';
});

// Session keep-alive route
Route::get('/ping', function () {
    return response()->noContent();
});

// Public routes (no login required)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/scan', [PublicController::class, 'scan'])->name('scan');
Route::get('/assets/public/{qr_token}', [PublicController::class, 'showAsset'])->name('assets.public');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::get('/manual', [PublicController::class, 'manual'])->name('manual');

// Authentication routes (Laravel Breeze/Fortify or custom)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Protected routes (require login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assets management
    Route::resource('assets', AssetController::class);
    Route::get('/assets/{asset}/qr', [AssetController::class, 'downloadQr'])->name('assets.qr');
    Route::post('/assets/bulk-import', [AssetController::class, 'bulkImport'])->name('assets.bulk-import');
    Route::get('/assets/{asset}/print-label', [AssetController::class, 'printLabel'])->name('assets.print-label');

    // Locations management
    Route::resource('locations', LocationController::class);

    // Inspections
    Route::resource('inspections', InspectionController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::get('/assets/{asset}/inspections/create', [InspectionController::class, 'createForAsset'])->name('assets.inspections.create');

    // Transfers
    Route::resource('transfers', TransferController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/assets/{asset}/transfers/create', [TransferController::class, 'createForAsset'])->name('assets.transfers.create');

    // Repairs
    Route::resource('repairs', RepairController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::get('/assets/{asset}/repairs/create', [RepairController::class, 'createForAsset'])->name('assets.repairs.create');

    // Loans (Borrow/Return)
    Route::resource('loans', LoanController::class);
    Route::get('/assets/{asset}/loans/create', [LoanController::class, 'create'])->name('assets.loans.create');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/assets', [ReportController::class, 'assets'])->name('reports.assets');
    Route::get('/reports/inspections', [ReportController::class, 'inspections'])->name('reports.inspections');
    Route::get('/reports/transfers', [ReportController::class, 'transfers'])->name('reports.transfers');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // Audit logs (admin & auditor only)
    Route::middleware(['role:admin|auditor'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});

// Admin only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});
