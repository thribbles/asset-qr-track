<?php
// Diagnostic script to check QR URL configuration
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== QR URL Configuration Check ===\n\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "APP_PUBLIC_URL: " . config('app.public_url') . "\n\n";

// Check if APP_PUBLIC_URL is set in .env
$envPublicUrl = env('APP_PUBLIC_URL');
echo "env('APP_PUBLIC_URL'): " . ($envPublicUrl ?: 'NOT SET') . "\n";

echo "\n=== To fix QR Code URL ===\n";
echo "1. Open .env file\n";
echo "2. Add this line (replace with your server IP):\n";
echo "   APP_PUBLIC_URL=http://192.168.1.100/assect/public\n";
echo "3. Run: php artisan config:clear\n";
echo "4. Refresh the page\n";
