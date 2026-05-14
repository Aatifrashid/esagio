<?php
if (function_exists('opcache_reset')) { opcache_reset(); echo "OPcache cleared\n"; }
$viewCachePath = dirname(__DIR__) . '/storage/framework/views';
$files = glob($viewCachePath . '/*');
$count = 0;
foreach ($files as $file) { if (is_file($file)) { unlink($file); $count++; } }
echo "Cleared $count compiled views\n";
echo "Done " . date('H:i:s');
