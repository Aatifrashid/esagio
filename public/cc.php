<?php
// Clear compiled Blade views
$viewPath = __DIR__ . '/../storage/framework/views';
$files = glob($viewPath . '/*');
$count = 0;
foreach ($files as $file) {
    if (is_file($file)) { unlink($file); $count++; }
}
if (function_exists('opcache_reset')) { opcache_reset(); }
echo "Cleared {$count} compiled views. OPcache reset.";
