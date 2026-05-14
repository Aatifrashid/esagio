<?php
header('Content-Type: text/plain');

// Show routes
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$router = app('router');
$routes = collect($router->getRoutes())->map(function ($route) {
    return $route->methods()[0] . ' ' . $route->uri() . ' => ' . ($route->getName() ?: 'N/A');
})->filter(function ($r) {
    return str_contains($r, 'plan') || str_contains($r, 'builder') || str_contains($r, 'pipeline');
});

echo "=== Routes matching plan/builder/pipeline ===\n";
echo $routes->implode("\n");

echo "\n\n=== Last 30 log lines ===\n";
$logFile = __DIR__.'/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    echo implode('', array_slice($lines, -30));
}
