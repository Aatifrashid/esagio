<?php
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines, -80);
    echo '<pre>' . htmlspecialchars(implode('', $last)) . '</pre>';
} else {
    echo 'No log file found';
}
