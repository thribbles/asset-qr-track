<?php
// Add these routes to web.php temporarily for QR debugging and cache clearing

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// QR Configuration Check
Route::get('/qr-check', function () {
    return [
        'app_url' => config('app.url'),
        'app_public_url' => config('app.public_url'),
        'env_public_url' => env('APP_PUBLIC_URL', 'NOT SET'),
        'solution' => 'Add APP_PUBLIC_URL=http://YOUR_IP/assect/public to .env file',
    ];
});

// Clear all caches
Route::get('/clear-all-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'All caches cleared! Check QR code again.';
});
