<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user, true);
header('Location: /dashboard/plans/1/builder');
exit;
