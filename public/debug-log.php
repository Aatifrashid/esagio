<?php
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
header('Content-Type: text/plain');
if (file_exists($logFile)) {
    $lines = file($logFile);
    echo implode('', array_slice($lines, -100));
} else {
    echo "No log file found at: $logFile\n";
    echo "Files in storage/logs:\n";
    $dir = dirname(__DIR__) . '/storage/logs/';
    if (is_dir($dir)) {
        foreach (scandir($dir) as $f) echo "  $f\n";
    } else {
        echo "  Directory does not exist\n";
    }
}
