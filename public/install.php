<?php
set_time_limit(600);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = dirname(__DIR__);
chdir($baseDir);

echo "<pre>";
echo "Working directory: " . getcwd() . "\n\n";

function runCommand(string $cmd): string {
    $handle = popen($cmd . ' 2>&1', 'r');
    if (!$handle) {
        return 'ERROR: popen failed';
    }
    $output = stream_get_contents($handle);
    pclose($handle);
    return $output;
}

if (!file_exists($baseDir . '/composer.phar')) {
    echo "Downloading composer.phar...\n";
    copy('https://getcomposer.org/composer-stable.phar', $baseDir . '/composer.phar');
    echo "Done.\n\n";
} else {
    echo "composer.phar already exists.\n\n";
}

echo "Running composer install...\n";
echo str_repeat('-', 60) . "\n";
echo runCommand('cd ' . $baseDir . ' && HOME=' . $baseDir . ' COMPOSER_HOME=' . $baseDir . '/.composer php composer.phar install --no-dev --optimize-autoloader --no-interaction');
echo "\n\n";

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
    echo runCommand('cd ' . $baseDir . ' && ' . $cmd);
    echo "\n\n";
}

echo "=== Installation complete ===\n";
echo "DELETE THIS FILE NOW for security!\n";
echo "</pre>";
