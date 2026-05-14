<?php
header('Content-Type: text/plain');

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

// Check if plan ID 1 exists
$plan = \App\Models\TreatmentPlan::find(1);
echo "Plan #1 exists: " . ($plan ? 'YES - ' . $plan->title : 'NO') . "\n";

// List all plan IDs
$planIds = \App\Models\TreatmentPlan::pluck('id')->toArray();
echo "All plan IDs: " . implode(', ', $planIds) . "\n";

// Check auth
echo "Current user: " . (auth()->user() ? auth()->user()->email : 'NOT LOGGED IN') . "\n";
