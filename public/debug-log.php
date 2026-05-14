<?php
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    preg_match_all('/\[\d{4}-\d{2}-\d{2}.*?\] \w+\.\w+: (.+?)(?=\n\[|\z)/s', $content, $matches);
    $last3 = array_slice($matches[0], -3);
    foreach ($last3 as $e) {
        echo '<pre>' . htmlspecialchars(substr($e, 0, 800)) . '</pre><hr>';
    }
} else {
    echo 'No log file found';
}
