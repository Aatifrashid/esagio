<?php
header('Content-Type: text/plain');
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "No log file.\n";
    exit;
}
$content = file_get_contents($logFile);
// Find all error entries, skip the highlight_file ones
preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \w+\.ERROR: (.+?)(?=\[\d{4}-\d{2}-\d{2}|\Z)/s', $content, $matches);
$errors = $matches[0] ?? [];
// Show last 5 unique error messages (skip highlight_file duplicates)
$unique = [];
foreach (array_reverse($errors) as $err) {
    if (str_contains($err, 'highlight_file')) continue;
    $firstLine = strtok($err, "\n");
    if (!in_array($firstLine, array_column($unique, 'key'))) {
        $unique[] = ['key' => $firstLine, 'full' => substr($err, 0, 2000)];
    }
    if (count($unique) >= 5) break;
}
if (empty($unique)) {
    echo "No non-highlight_file errors found. Showing last 3000 chars:\n\n";
    echo substr($content, -3000);
} else {
    foreach ($unique as $i => $u) {
        echo "=== Error " . ($i + 1) . " ===\n";
        echo $u['full'] . "\n\n";
    }
}
