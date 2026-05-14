<?php
header('Content-Type: text/plain');
$logFile = __DIR__.'/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines, -80);
    echo implode('', $last);
} else {
    echo "No log file found.";
}
