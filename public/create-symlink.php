<?php
header('Content-Type: text/plain');
$baseDir = dirname(__DIR__);
$link = $baseDir . '/public/storage';
$target = $baseDir . '/storage/app/public';

if (file_exists($link) || is_link($link)) {
    echo "Link already exists at: $link\n";
    echo "Points to: " . (is_link($link) ? readlink($link) : 'not a symlink') . "\n";
} else {
    $cmd = 'ln -s ' . escapeshellarg($target) . ' ' . escapeshellarg($link) . ' 2>&1';
    $handle = popen($cmd, 'r');
    $output = stream_get_contents($handle);
    $status = pclose($handle);
    if ($status === 0) {
        echo "Symlink created successfully.\n";
        echo "$link -> $target\n";
    } else {
        echo "Failed to create symlink.\n";
        echo "Command: $cmd\n";
        echo "Output: $output\n";
        echo "Exit code: $status\n";
    }
}
