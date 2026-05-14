<?php
header('Content-Type: text/plain');
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "No log file.\n";
    exit;
}
$lines = file($logFile);
$last = array_slice($lines, -80);
echo implode('', $last);
