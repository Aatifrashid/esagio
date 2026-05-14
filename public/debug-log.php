<?php
if (isset($_GET['clear'])) {
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo 'opcache cleared<br>';
    }
    // Clear compiled views
    $viewPath = __DIR__ . '/../storage/framework/views';
    foreach (glob($viewPath . '/*.php') as $f) { unlink($f); }
    echo 'compiled views cleared<br>';
    exit;
}

$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    preg_match_all('/\[\d{4}-\d{2}-\d{2}.*?\] \w+\.\w+: (.+?)(?=\n\[|\z)/s', $content, $matches);
    $errors = $matches[0];
    $last3 = array_slice($errors, -3);
    foreach ($last3 as $e) {
        $short = substr($e, 0, 500);
        echo '<pre>' . htmlspecialchars($short) . '</pre><hr>';
    }
} else {
    echo 'No log file found';
}
