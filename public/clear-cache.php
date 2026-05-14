<?php
header('Content-Type: text/plain');
$baseDir = dirname(__DIR__);

// Delete config cache file directly
$configCache = $baseDir . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    unlink($configCache);
    echo "Deleted config cache.\n";
} else {
    echo "No config cache file.\n";
}

// Clear view cache
$viewPath = $baseDir . '/storage/framework/views';
if (is_dir($viewPath)) {
    $files = glob($viewPath . '/*.php');
    foreach ($files as $f) unlink($f);
    echo "Cleared " . count($files) . " cached views.\n";
}

// Check env debug value
echo "APP_DEBUG from .env: " . (file_exists($baseDir . '/.env') ? 'exists' : 'missing') . "\n";
$env = file_get_contents($baseDir . '/.env');
preg_match('/APP_DEBUG=(.*)/', $env, $m);
echo "APP_DEBUG value: " . ($m[1] ?? 'not found') . "\n";

echo "Done.\n";
