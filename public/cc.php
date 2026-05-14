<?php
$dir = dirname(__DIR__) . '/storage/framework/views';
$files = glob($dir . '/*.php');
$count = 0;
foreach ($files as $f) {
    if (unlink($f)) $count++;
}
if (function_exists('opcache_reset')) opcache_reset();
header('Content-Type: text/plain');
echo "Deleted $count compiled views";
