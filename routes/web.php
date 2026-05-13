<?php

use App\Http\Controllers\Patient\PlanViewController;
use App\Livewire\PlanBuilder\Builder;
use Illuminate\Support\Facades\Route;

// Patient-facing public plan routes
Route::prefix('p/{token}')->name('patient.plan.')->group(function () {
    Route::get('/', [PlanViewController::class, 'show'])->name('show');
    Route::get('/password', [PlanViewController::class, 'showPasswordGate'])->name('password');
    Route::post('/password', [PlanViewController::class, 'submitPassword'])->name('password.submit');
    Route::post('/accept', [PlanViewController::class, 'accept'])->name('accept');
    Route::post('/decline', [PlanViewController::class, 'decline'])->name('decline');
    Route::post('/comment', [PlanViewController::class, 'addComment'])->name('comment');
    Route::post('/toggle-item', [PlanViewController::class, 'toggleOptionalItem'])->name('toggle-item');
    Route::get('/pdf', [PlanViewController::class, 'downloadPdf'])->name('pdf');
});

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/plans/{plan}/builder', Builder::class)->name('plan.builder');
    Route::get('/dashboard/crm/pipeline/{pipeline}', \App\Livewire\Crm\PipelineKanban::class)->name('crm.pipeline');
});

require __DIR__.'/auth.php';
