<?php

use App\Livewire\PlanBuilder\Builder;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/plans/{plan}/builder', Builder::class)->name('plan.builder');
});

require __DIR__.'/auth.php';
