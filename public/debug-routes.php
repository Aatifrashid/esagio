<?php
header('Content-Type: text/plain');

$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Boot the app
$request = \Illuminate\Http\Request::capture();
$app->instance('request', $request);
$app->boot();

// Check route cache
$routeCachePath = $baseDir . '/bootstrap/cache/routes-v7.php';
echo "Route cache exists: " . (file_exists($routeCachePath) ? 'YES' : 'no') . "\n";

// Check for any route cache files
$cacheFiles = glob($baseDir . '/bootstrap/cache/routes*.php');
echo "Route cache files: " . (empty($cacheFiles) ? 'none' : implode(', ', array_map('basename', $cacheFiles))) . "\n\n";

// List routes matching 'onboarding'
$router = $app->make('router');
$routes = $router->getRoutes();

echo "=== All registered routes ===\n";
foreach ($routes as $route) {
    $uri = $route->uri();
    if (str_contains($uri, 'onboard') || str_contains($uri, 'dashboard') || $uri === 'login') {
        $middleware = implode(', ', $route->middleware());
        echo sprintf("%-8s %-40s %s\n", implode('|', $route->methods()), $uri, $middleware);
    }
}

echo "\n=== Onboarding route specifically ===\n";
$onboardRoute = $routes->getByName('onboarding');
if ($onboardRoute) {
    echo "Found: " . $onboardRoute->uri() . "\n";
    echo "Action: " . ($onboardRoute->getActionName()) . "\n";
    echo "Middleware: " . implode(', ', $onboardRoute->middleware()) . "\n";
} else {
    echo "NOT FOUND by name!\n";
}

echo "\nDone.\n";
