<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

Illuminate\Support\Facades\Artisan::call('route:clear');
Illuminate\Support\Facades\Artisan::call('config:clear');
Illuminate\Support\Facades\Artisan::call('view:clear');
Illuminate\Support\Facades\Artisan::call('cache:clear');

if (function_exists('opcache_reset')) opcache_reset();

echo "All caches cleared (route, config, view, cache, opcache).";
