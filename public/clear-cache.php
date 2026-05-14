<?php
header('Content-Type: text/plain');
$baseDir = dirname(__DIR__);

$commands = [
    'php artisan config:clear',
];

foreach ($commands as $cmd) {
    echo "Running: $cmd\n";
    $handle = popen('cd ' . $baseDir . ' && ' . $cmd . ' 2>&1', 'r');
    echo stream_get_contents($handle);
    pclose($handle);
    echo "\n";
}
echo "Done.\n";
