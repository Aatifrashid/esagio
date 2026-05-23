<?php

use App\Http\Controllers\Api\WhatsappWebhookController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\GdprController;
use App\Http\Controllers\Marketing\PageController;
use App\Http\Controllers\Patient\PlanViewController;
use App\Livewire\Crm\PipelineKanban;
use App\Livewire\Onboarding\OnboardingWizard;
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

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware(['auth'])->group(function () {
    Route::get('/plans/{plan}/builder', Builder::class)->name('plan.builder');
    Route::get('/crm/pipeline/{pipeline}', PipelineKanban::class)->name('crm.pipeline');
});

// Billing routes
Route::middleware('auth')->prefix('billing')->name('billing.')->group(function () {
    Route::get('/portal', [BillingController::class, 'portal'])->name('portal');
    Route::post('/subscribe/{tier}', [BillingController::class, 'subscribe'])->name('subscribe');
    Route::get('/upgrade', fn () => view('dashboard.billing.upgrade'))->name('upgrade');
    Route::get('/suspended', fn () => view('dashboard.billing.suspended'))->name('suspended');
});

Route::post('/stripe/webhook', [BillingController::class, 'webhook'])->name('stripe.webhook');

// Marketing website routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/features', [PageController::class, 'features'])->name('features');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
Route::get('/vs-brightplans', [PageController::class, 'vsBrightplans'])->name('vs-brightplans');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogPost'])->name('blog.post');

// Demo route
Route::get('/demo', [DemoController::class, 'show'])->name('demo.show');

// Onboarding
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', OnboardingWizard::class)->name('onboarding');
});

// GDPR
Route::middleware('auth')->prefix('gdpr')->name('gdpr.')->group(function () {
    Route::get('/patient/{patient}/export', [GdprController::class, 'export'])->name('export');
    Route::delete('/patient/{patient}', [GdprController::class, 'delete'])->name('delete');
});

// WhatsApp Bridge Webhooks
Route::prefix('api/whatsapp/webhook')->name('whatsapp.webhook.')->middleware(\App\Http\Middleware\VerifyWhatsappBridgeSignature::class)->group(function () {
    Route::post('/message-received', [WhatsappWebhookController::class, 'messageReceived'])->name('message');
    Route::post('/status-update', [WhatsappWebhookController::class, 'statusUpdate'])->name('status');
    Route::post('/session-status', [WhatsappWebhookController::class, 'sessionStatus'])->name('session');
});

// Temporary debug route
Route::get('/debug-plan-items-x8k2', function () {
    $plan = \App\Models\TreatmentPlan::withoutGlobalScopes()->find(4);
    $items = $plan->items()->with('template')->get();
    $templates = \App\Models\TreatmentTemplate::all(['id', 'name', 'code']);
    $out = "=== PLAN ITEMS ===\n";
    foreach ($items as $i) {
        $out .= "{$i->name} => template_id: " . ($i->treatment_template_id ?? 'NULL') . " => " . ($i->template?->name ?? 'NO MATCH') . "\n";
    }
    $out .= "\n=== TEMPLATES ===\n";
    foreach ($templates as $t) {
        $out .= "{$t->id}: [{$t->code}] {$t->name} | steps: " . (is_array($t->procedure_steps) ? count($t->procedure_steps) : 'null') . "\n";
    }
    return response($out, 200, ['Content-Type' => 'text/plain']);
});

require __DIR__.'/auth.php';
