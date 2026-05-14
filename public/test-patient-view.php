<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Actually handle the request to /dashboard/patients/9
$request = \Illuminate\Http\Request::create('/dashboard/patients/9', 'GET');
$request->setLaravelSession($app->make('session.store'));

// Auth the user
$kernel->bootstrap();

try {
    $user = \App\Models\User::find(4);
    auth()->login($user);

    echo "User: {$user->email}, clinic_id: {$user->clinic_id}\n\n";

    // Try to load patient 9
    $patient = \App\Models\Patient::find(9);
    echo "Patient (no scope): " . ($patient ? "{$patient->first_name} {$patient->last_name}, clinic_id: {$patient->clinic_id}" : 'NOT FOUND') . "\n";

    // With scope
    $patientScoped = \App\Models\Patient::where('id', 9)->first();
    echo "Patient (with scope): " . ($patientScoped ? "{$patientScoped->first_name}" : 'NOT FOUND (scoped out)') . "\n";

    // Check all patients for this clinic
    $patients = \App\Models\Patient::all();
    echo "Total patients for clinic: " . $patients->count() . "\n";
    foreach ($patients as $p) {
        echo "  ID {$p->id}: {$p->first_name} {$p->last_name} (clinic_id: {$p->clinic_id})\n";
    }

    // Try the form relations
    echo "\nTesting CrmPipelineStage query...\n";
    $stages = \App\Models\CrmPipelineStage::with('pipeline')->get();
    echo "Pipeline stages: " . $stages->count() . "\n";

    echo "\nTesting User query...\n";
    $users = \App\Models\User::pluck('name', 'id');
    echo "Users: " . $users->count() . "\n";

} catch (\Throwable $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . substr($e->getTraceAsString(), 0, 2000) . "\n";
    if ($e->getPrevious()) {
        echo "\nPrevious: " . $e->getPrevious()->getMessage() . "\n";
    }
}
