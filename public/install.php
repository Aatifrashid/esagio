<?php
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = dirname(__DIR__);
chdir($baseDir);

echo "<pre>";
echo "Working directory: " . getcwd() . "\n\n";

// Step 1: Download composer if not present
if (!file_exists($baseDir . '/composer.phar')) {
    echo "Downloading composer.phar...\n";
    copy('https://getcomposer.org/composer-stable.phar', $baseDir . '/composer.phar');
    echo "Done.\n\n";
}

// Step 2: Run composer install
echo "Running composer install --no-dev --optimize-autoloader...\n";
echo str_repeat('-', 60) . "\n";

putenv('COMPOSER_HOME=' . $baseDir . '/.composer');
$output = [];
$code = 0;
exec('php ' . escapeshellarg($baseDir . '/composer.phar') . ' install --no-dev --optimize-autoloader --no-interaction 2>&1', $output, $code);
echo implode("\n", $output) . "\n";
echo "Exit code: {$code}\n\n";

// Step 3: Run artisan commands
$commands = [
    'php artisan storage:link',
    'php artisan migrate --force',
    'php artisan db:seed --force',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
];

foreach ($commands as $cmd) {
    echo "Running: {$cmd}\n";
    $output = [];
    exec('cd ' . escapeshellarg($baseDir) . ' && ' . $cmd . ' 2>&1', $output, $code);
    echo implode("\n", $output) . "\n";
    echo "Exit code: {$code}\n\n";
}

echo "=== Installation complete ===\n";
echo "DELETE THIS FILE NOW for security!\n";
echo "</pre>";
