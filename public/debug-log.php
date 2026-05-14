<?php
header('Content-Type: text/plain');
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$plans = \App\Models\TreatmentPlan::with('items.material')->get();
foreach ($plans as $p) {
    $matCount = $p->items->filter(fn($i) => $i->material)->count();
    echo "Plan #{$p->id} '{$p->title}' token={$p->public_token} items={$p->items->count()} with_materials={$matCount}\n";
}
