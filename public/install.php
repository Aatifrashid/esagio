<?php
set_time_limit(600);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = dirname(__DIR__);
chdir($baseDir);

echo "<pre>";
echo "Working directory: " . getcwd() . "\n\n";

// Check which execution functions are available
$funcs = ['exec', 'shell_exec', 'passthru', 'system', 'proc_open', 'popen'];
echo "Available functions:\n";
$disabled = explode(',', ini_get('disable_functions'));
$disabled = array_map('trim', $disabled);
foreach ($funcs as $f) {
    $available = !in_array($f, $disabled) && function_exists($f);
    echo "  {$f}: " . ($available ? 'YES' : 'NO') . "\n";
}
echo "\n";

// Try to run composer via proc_open or popen
function runCommand(string $cmd): array {
    $disabled = array_map('trim', explode(',', ini_get('disable_functions')));

    if (!in_array('proc_open', $disabled) && function_exists('proc_open')) {
        $desc = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $proc = proc_open($cmd, $desc, $pipes);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);
        return ['output' => $stdout . $stderr, 'code' => $code];
    }

    if (!in_array('popen', $disabled) && function_exists('popen')) {
        $handle = popen($cmd . ' 2>&1', 'r');
        $output = stream_get_contents($handle);
        $code = pclose($handle);
        return ['output' => $output, 'code' => $code];
    }

    if (!in_array('shell_exec', $disabled) && function_exists('shell_exec')) {
        $output = shell_exec($cmd . ' 2>&1');
        return ['output' => $output ?? '', 'code' => 0];
    }

    if (!in_array('system', $disabled) && function_exists('system')) {
        ob_start();
        system($cmd . ' 2>&1', $code);
        $output = ob_get_clean();
        return ['output' => $output, 'code' => $code];
    }

    if (!in_array('passthru', $disabled) && function_exists('passthru')) {
        ob_start();
        passthru($cmd . ' 2>&1', $code);
        $output = ob_get_clean();
        return ['output' => $output, 'code' => $code];
    }

    return ['output' => 'ERROR: No execution function available', 'code' => 1];
}

// Step 1: Download composer if not present
if (!file_exists($baseDir . '/composer.phar')) {
    echo "Downloading composer.phar...\n";
    copy('https://getcomposer.org/composer-stable.phar', $baseDir . '/composer.phar');
    echo "Done.\n\n";
} else {
    echo "composer.phar already exists.\n\n";
}

// Step 2: Run composer install
echo "Running composer install...\n";
echo str_repeat('-', 60) . "\n";
$result = runCommand('cd ' . escapeshellarg($baseDir) . ' && php composer.phar install --no-dev --optimize-autoloader --no-interaction');
echo $result['output'] . "\n";
echo "Exit code: {$result['code']}\n\n";

if ($result['code'] !== 0) {
    echo "Composer install failed. Stopping.\n</pre>";
    exit;
}

// Step 3: Run artisan commands
$commands = [
    'php artisan storage:link',
    'php artisan migrate --force',
    'php artisan db:seed --force',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
];

foreach ($commands as $cmd) {
    echo "Running: {$cmd}\n";
    $result = runCommand('cd ' . escapeshellarg($baseDir) . ' && ' . $cmd);
    echo $result['output'] . "\n";
    echo "Exit code: {$result['code']}\n\n";
}

echo "=== Installation complete ===\n";
echo "DELETE THIS FILE NOW for security!\n";
echo "</pre>";
