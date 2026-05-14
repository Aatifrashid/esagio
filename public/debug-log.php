<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

header('Content-Type: text/plain');

try {
    $kernel->handle(Illuminate\Http\Request::capture());

    // Try rendering the revenue forecast widget
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        echo "Logged in as: {$user->email}\n";
        echo "Clinic: " . ($user->clinic ? $user->clinic->name : 'NULL') . "\n";

        // Check if CrmPipelineStage has pipeline relationship
        $stages = \App\Models\CrmPipelineStage::with('pipeline')->get();
        echo "Pipeline stages: " . $stages->count() . "\n";

        foreach ($stages as $stage) {
            echo "  - {$stage->name} (pipeline: " . ($stage->pipeline ? $stage->pipeline->name : 'NULL') . ", clinic: " . ($stage->pipeline->clinic_id ?? 'N/A') . ")\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString();
}
