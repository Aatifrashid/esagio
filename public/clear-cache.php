<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(Illuminate\Http\Request::capture());
array_map('unlink', glob(__DIR__.'/../storage/framework/views/*.php'));
if (function_exists('opcache_reset')) opcache_reset();
echo "Cache cleared.";
