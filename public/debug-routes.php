<?php
header('Content-Type: text/plain');
$baseDir = dirname(__DIR__);

// Check route cache files
echo "=== Cache files ===\n";
$cacheFiles = glob($baseDir . '/bootstrap/cache/*.php');
foreach ($cacheFiles as $f) {
    echo basename($f) . " (" . filesize($f) . " bytes)\n";
}
if (empty($cacheFiles)) echo "none\n";

echo "\n=== Checking route file ===\n";
$routeFile = $baseDir . '/routes/web.php';
echo "web.php exists: " . (file_exists($routeFile) ? 'yes' : 'no') . "\n";

// Check if onboarding route is in the file
$content = file_get_contents($routeFile);
if (str_contains($content, 'onboarding')) {
    echo "Contains 'onboarding': yes\n";
} else {
    echo "Contains 'onboarding': NO!\n";
}

// Check OnboardingWizard class exists
$wizardFile = $baseDir . '/app/Livewire/Onboarding/OnboardingWizard.php';
echo "OnboardingWizard.php exists: " . (file_exists($wizardFile) ? 'yes' : 'no') . "\n";

// Check view exists
$viewFile = $baseDir . '/resources/views/livewire/onboarding/onboarding-wizard.blade.php';
echo "View exists: " . (file_exists($viewFile) ? 'yes' : 'no') . "\n";

// Check layout exists
$layoutFile = $baseDir . '/resources/views/layouts/app.blade.php';
echo "Layout exists: " . (file_exists($layoutFile) ? 'yes' : 'no') . "\n";

// Try booting Laravel and listing routes
echo "\n=== Booting Laravel ===\n";
try {
    require $baseDir . '/vendor/autoload.php';
    $app = require_once $baseDir . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    $request = \Illuminate\Http\Request::create('/onboarding', 'GET');
    $app->instance('request', $request);

    // Handle to boot everything
    $kernel->bootstrap();

    $router = $app->make('router');
    $routes = $router->getRoutes();

    echo "Total routes: " . $routes->count() . "\n\n";

    $onboardRoute = $routes->getByName('onboarding');
    if ($onboardRoute) {
        echo "Onboarding route FOUND:\n";
        echo "  URI: " . $onboardRoute->uri() . "\n";
        echo "  Action: " . $onboardRoute->getActionName() . "\n";
        echo "  Middleware: " . implode(', ', $onboardRoute->middleware()) . "\n";
    } else {
        echo "Onboarding route NOT FOUND by name!\n";
        echo "\nSearching all routes for 'onboard':\n";
        foreach ($routes as $route) {
            if (str_contains($route->uri(), 'onboard')) {
                echo "  " . implode('|', $route->methods()) . " " . $route->uri() . "\n";
            }
        }
    }

    // Try to match the route
    echo "\n=== Route matching /onboarding ===\n";
    try {
        $matched = $routes->match($request);
        echo "Matched: " . $matched->uri() . "\n";
        echo "Action: " . $matched->getActionName() . "\n";
    } catch (\Exception $e) {
        echo "Match failed: " . $e->getMessage() . "\n";
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nDone.\n";
