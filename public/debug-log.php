<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "tooth_chart_conditions exists: " . (Schema::hasTable('tooth_chart_conditions') ? 'YES' : 'NO') . "\n";
echo "treatment_plan_tooth_charts exists: " . (Schema::hasTable('treatment_plan_tooth_charts') ? 'YES' : 'NO') . "\n";

if (Schema::hasTable('tooth_chart_conditions')) {
    echo "\ntooth_chart_conditions columns: " . implode(', ', Schema::getColumnListing('tooth_chart_conditions')) . "\n";
    echo "Row count: " . DB::table('tooth_chart_conditions')->count() . "\n";
}

if (Schema::hasTable('treatment_plan_tooth_charts')) {
    echo "\ntreatment_plan_tooth_charts columns: " . implode(', ', Schema::getColumnListing('treatment_plan_tooth_charts')) . "\n";
    echo "Row count: " . DB::table('treatment_plan_tooth_charts')->count() . "\n";
}
