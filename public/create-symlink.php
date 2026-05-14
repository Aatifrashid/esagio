<?php
header('Content-Type: text/plain');
$baseDir = dirname(__DIR__);
$link = $baseDir . '/public/storage';
$target = $baseDir . '/storage/app/public';

if (file_exists($link) || is_link($link)) {
    echo "Link already exists at: $link\n";
} else {
    $cmd = 'ln -s ' . $target . ' ' . $link . ' 2>&1';
    $handle = popen($cmd, 'r');
    if (!$handle) {
        echo "popen failed\n";
    } else {
        $output = stream_get_contents($handle);
        $status = pclose($handle);
        if ($status === 0) {
            echo "Symlink created successfully.\n";
            echo "$link -> $target\n";
        } else {
            echo "Failed. Output: $output\nExit: $status\n";
        }
    }
}
