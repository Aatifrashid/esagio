<?php
header('Content-Type: text/plain');
$baseDir = dirname(__DIR__);

$commands = [
    'php artisan route:clear',
    'php artisan config:clear',
    'php artisan view:clear',
    'php artisan route:cache',
    'php artisan config:cache',
    'php artisan view:cache',
];

foreach ($commands as $cmd) {
    echo "Running: $cmd\n";
    $handle = popen('cd ' . $baseDir . ' && ' . $cmd . ' 2>&1', 'r');
    echo stream_get_contents($handle);
    pclose($handle);
    echo "\n";
}
echo "Done.\n";
