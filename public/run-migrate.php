<?php

/**
 * One-time migration runner — DELETE THIS FILE after use.
 * Protected by a secret token to prevent unauthorized access.
 */

$secret = 'esa_migrate_2026_x9k4m';

if (($_GET['token'] ?? '') !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>\n";
echo "Running migrations...\n\n";

try {
    Artisan::call('migrate', ['--force' => true]);
    echo Artisan::output();
    echo "\n✅ Migrations completed successfully.\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n⚠️  DELETE this file immediately: public/run-migrate.php\n";
echo "</pre>";
