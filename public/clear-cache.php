<?php
if (function_exists('opcache_reset')) { opcache_reset(); echo "opcache cleared\n"; }
$viewPath = __DIR__ . '/../storage/framework/views';
$count = 0;
foreach (glob($viewPath . '/*.php') as $f) { unlink($f); $count++; }
echo "cleared $count compiled views\n";
