<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

header('Content-Type: text/plain');

try {
    $user = \App\Models\User::first();
    \Illuminate\Support\Facades\Auth::login($user);

    $widget = new \App\Filament\Clinic\Widgets\RevenueForecastWidget();
    $data = (new ReflectionMethod($widget, 'getViewData'))->invoke($widget);

    echo "Widget data retrieved OK\n";
    echo "Stages: " . $data['stages']->count() . "\n";
    echo "Total weighted: " . $data['totalWeighted'] . "\n";
    echo "Total unweighted: " . $data['totalUnweighted'] . "\n";
    echo "Won revenue: " . $data['wonRevenue'] . "\n";
    echo "Monthly target: " . $data['monthlyTarget'] . "\n";

    foreach ($data['stages'] as $s) {
        echo "  {$s['name']}: £" . number_format($s['total_value']) . " x {$s['probability']}% = £" . number_format($s['weighted_value']) . " ({$s['deal_count']} deals)\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\n" . $e->getTraceAsString();
}
