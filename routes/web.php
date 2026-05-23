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

// Temporary: populate template rich data
Route::get('/populate-templates-q3m9', function () {
    $data = [
        'CRW-003' => [
            'steps' => ['Tooth preparation and shade selection', 'Digital impression or scan', 'Temporary crown fitted', 'Zirconia crown milled and glazed (5-7 days)', 'Final crown cemented and bite adjusted'],
            'recovery' => 'Mild sensitivity to temperature for a few days. Avoid very hard foods for the first 48 hours.',
            'guarantee' => 'Zirconia crowns carry a 5-year guarantee against material failure.',
        ],
        'CRW-004' => [
            'steps' => ['Tooth preparation with minimal reduction', 'Digital shade matching', 'Impression or intraoral scan', 'Temporary crown placed', 'E.max crown bonded with adhesive cement'],
            'recovery' => 'Minor sensitivity possible for 1-2 days. Normal brushing and flossing from day one.',
            'guarantee' => 'E.max crowns carry a 5-year aesthetic and structural guarantee.',
        ],
        'CRW-001' => [
            'steps' => ['Tooth preparation', 'Impression and shade selection', 'Temporary crown fitted', 'Metal substructure try-in', 'Porcelain layered crown cemented'],
            'recovery' => 'Mild sensitivity for a few days after cementation. Avoid hard or sticky foods for 24 hours.',
            'guarantee' => 'Metal porcelain crowns carry a 5-year guarantee against material failure.',
        ],
        'CRW-002' => [
            'steps' => ['Tooth surface prepared and etched', 'Shade-matched composite applied in layers', 'Each layer cured with UV light', 'Shaped and polished to natural finish'],
            'recovery' => 'No recovery needed. Avoid staining foods and drinks for 48 hours.',
            'guarantee' => null,
        ],
        'CRW-005' => [
            'steps' => ['Smile design consultation and digital mock-up', 'Minimal tooth preparation (0.3-0.5mm)', 'Digital impression taken', 'E.max laminates fabricated in lab', 'Laminates bonded with adhesive resin cement'],
            'recovery' => 'No downtime. Mild sensitivity possible for 2-3 days. Avoid biting directly into very hard foods.',
            'guarantee' => 'E.max laminates carry a 5-year aesthetic guarantee.',
        ],
        'CRW-006' => [
            'steps' => ['Tooth preparation', 'Digital scan or impression', 'ZirCAD Prime block selected and milled', 'Crown stained and glazed for natural aesthetics', 'Final crown cemented'],
            'recovery' => 'Mild sensitivity for 1-2 days. Avoid very hard foods for 48 hours.',
            'guarantee' => 'ZirCAD Prime crowns carry a 5-year material guarantee.',
        ],
        'EXT-001' => [
            'steps' => ['Local anaesthetic administered', 'Tooth loosened and removed', 'Socket cleaned and checked', 'Gauze placed to control bleeding', 'Aftercare instructions provided'],
            'recovery' => 'Some discomfort for 1-3 days. Avoid hot drinks and vigorous rinsing for 24 hours. Gentle salt water rinses from day 2.',
            'guarantee' => null,
        ],
        'EXT-002' => [
            'steps' => ['Local anaesthetic or sedation', 'Gum tissue reflected', 'Bone removal if needed', 'Tooth sectioned and removed', 'Site cleaned and sutured', 'Review in 7-10 days'],
            'recovery' => 'Swelling and discomfort for 3-5 days. Soft diet recommended. Avoid smoking.',
            'guarantee' => null,
        ],
        'EXT-003' => [
            'steps' => ['Panoramic X-ray assessment', 'Local anaesthetic administered', 'Partially erupted tooth exposed', 'Tooth sectioned if needed and removed', 'Socket cleaned and sutured'],
            'recovery' => 'Swelling peaks at 48-72 hours. Soft diet for 5-7 days. Salt water rinses from 24 hours post-op.',
            'guarantee' => null,
        ],
        'EXT-004' => [
            'steps' => ['CBCT scan to assess position', 'Local anaesthetic or IV sedation', 'Gum flap raised and bone removed', 'Fully impacted tooth sectioned and extracted', 'Site irrigated and sutured', 'Review in 7-10 days'],
            'recovery' => 'Significant swelling for 3-5 days. Strict soft diet. Pain managed with prescribed medication. No smoking for minimum 5 days.',
            'guarantee' => null,
        ],
        'SUR-001' => [
            'steps' => ['CBCT scan to measure bone height', 'Local anaesthetic administered', 'Implant site prepared', 'Sinus membrane gently elevated through implant site', 'Bone graft material placed', 'Implant placed simultaneously if possible'],
            'recovery' => 'Avoid blowing nose for 2 weeks. Mild swelling for 3-5 days. Sneeze with mouth open. No flying for 7 days.',
            'guarantee' => null,
        ],
        'SUR-002' => [
            'steps' => ['CBCT scan assessment', 'Local anaesthetic administered', 'Lateral window created in sinus wall', 'Sinus membrane carefully elevated', 'Bone graft material packed', 'Collagen membrane placed over window', 'Site sutured closed', 'Healing: 6-9 months before implant placement'],
            'recovery' => 'Swelling and mild bruising for 5-7 days. Avoid blowing nose for 3 weeks. No straws or smoking. Antibiotics prescribed.',
            'guarantee' => null,
        ],
        'SUR-003' => [
            'steps' => ['Assessment of bone deficiency', 'Local anaesthetic administered', 'Recipient site prepared', 'Bone graft material placed and secured', 'Membrane placed if needed', 'Site sutured', 'Healing: 4-6 months'],
            'recovery' => 'Swelling for 3-5 days. Soft diet for 2 weeks. Avoid pressure on grafted area. Complete prescribed antibiotics.',
            'guarantee' => null,
        ],
        'SUR-004' => [
            'steps' => ['Treatment area assessed', 'Local anaesthetic if required', 'Fibre reinforcement placed and bonded', 'Composite resin applied over fibre', 'Shaped and polished'],
            'recovery' => 'No significant recovery needed.',
            'guarantee' => null,
        ],
        'SUR-005' => [
            'steps' => ['Tooth assessed for suitability', 'Root canal confirmed sealed', 'Bleaching agent placed inside tooth', 'Temporary filling placed', 'Reviewed and repeated if needed (2-3 sessions)', 'Final restoration placed'],
            'recovery' => 'No significant discomfort expected.',
            'guarantee' => null,
        ],
        'SUR-006' => [
            'steps' => ['Local anaesthetic administered', 'Frenum tissue excised or released', 'Area sutured if needed', 'Aftercare instructions provided'],
            'recovery' => 'Mild discomfort for 2-3 days. Soft diet for 24-48 hours.',
            'guarantee' => null,
        ],
        'SUR-007' => [
            'steps' => ['Consultation and facial assessment', 'Treatment areas marked', 'Botox injected into targeted muscles', 'Post-treatment instructions provided'],
            'recovery' => 'Avoid lying down for 4 hours. No rubbing the treated area for 24 hours. Results visible in 3-7 days.',
            'guarantee' => null,
        ],
        'IMP-105' => [
            'steps' => ['Comprehensive assessment with CBCT scan', 'Digital treatment plan designed', 'Local anaesthetic or sedation', 'Straumann BLX implant placed', 'Healing cap or temporary restoration fitted', 'Osseointegration: 6-12 weeks', 'Final abutment and crown placed'],
            'recovery' => 'Mild swelling for 3-5 days. Soft diet for 2 weeks around the implant. Excellent oral hygiene essential.',
            'guarantee' => 'Straumann implants carry a lifetime guarantee against fixture failure, subject to annual reviews.',
        ],
        'IMP-101' => [
            'steps' => ['CBCT scan and digital planning', 'Local anaesthetic administered', 'NTA Spure implant placed', 'Healing abutment fitted', 'Osseointegration: 3-4 months', 'Final prosthetic restoration'],
            'recovery' => 'Mild discomfort and swelling for 3-5 days. Soft diet for 2 weeks.',
            'guarantee' => 'NTA implants carry a 10-year guarantee against fixture failure.',
        ],
        'IMP-102' => [
            'steps' => ['CBCT scan and planning', 'Local anaesthetic', 'NTA implant placed', 'Healing abutment fitted', 'Osseointegration: 3-4 months', 'Final restoration'],
            'recovery' => 'Mild discomfort for 3-5 days. Soft diet for 2 weeks.',
            'guarantee' => 'NTA implants carry a 10-year guarantee against fixture failure.',
        ],
        'IMP-103' => [
            'steps' => ['Assessment for limited bone height', 'CBCT scan', 'Local anaesthetic', 'Shorter implant placed in available bone', 'Healing: 3-4 months', 'Final restoration'],
            'recovery' => 'Similar to standard implant. Mild discomfort for 3-5 days.',
            'guarantee' => 'NTA implants carry a 10-year guarantee against fixture failure.',
        ],
        'IMP-104' => [
            'steps' => ['CBCT scan and digital planning', 'Local anaesthetic', 'NTA Hybrid implant placed', 'Healing abutment fitted', 'Osseointegration: 3-4 months', 'Final restoration'],
            'recovery' => 'Mild discomfort for 3-5 days. Soft diet for 2 weeks.',
            'guarantee' => 'NTA Hybrid implants carry a 10-year guarantee.',
        ],
        'PKG-103' => [
            'steps' => ['Full assessment including CBCT scan and photos', 'Digital treatment plan designed', 'Remaining teeth extracted if needed', 'Four Straumann BLX implants placed per arch', 'Provisional fixed bridge attached same day', 'Healing: 3-6 months', 'Final zirconia bridge fabricated and fitted'],
            'recovery' => 'Swelling and bruising common for first week. Strictly soft diet for 8-12 weeks. Regular reviews essential.',
            'guarantee' => 'All-on-4 Straumann carries a lifetime implant guarantee and 5-year prosthesis guarantee, subject to annual reviews.',
        ],
        'PKG-101' => [
            'steps' => ['Full assessment with CBCT scan', 'Digital planning', 'Extractions if needed', 'Four NTA Spure implants placed per arch', 'Same-day provisional bridge fitted', 'Healing: 3-6 months', 'Final prosthesis fitted'],
            'recovery' => 'Swelling for 5-7 days. Soft diet for 8-12 weeks.',
            'guarantee' => 'All-on-4 NTA carries a 10-year implant guarantee and 5-year prosthesis guarantee.',
        ],
        'PKG-102' => [
            'steps' => ['Full assessment with CBCT scan', 'Digital planning', 'Extractions if needed', 'Four NTA implants placed per arch', 'Same-day provisional bridge', 'Healing: 3-6 months', 'Final prosthesis fitted'],
            'recovery' => 'Swelling for 5-7 days. Soft diet for 8-12 weeks.',
            'guarantee' => 'All-on-4 NTA carries a 10-year implant guarantee and 5-year prosthesis guarantee.',
        ],
        'PKG-104' => [
            'steps' => ['Full assessment with CBCT scan', 'Digital planning', 'Extractions if needed', 'Six NTA Spure implants placed per arch', 'Same-day provisional bridge', 'Healing: 3-6 months', 'Final prosthesis fitted'],
            'recovery' => 'Swelling for 5-7 days. Soft diet for 8-12 weeks.',
            'guarantee' => 'All-on-6 NTA carries a 10-year implant guarantee and 5-year prosthesis guarantee.',
        ],
        'PKG-105' => [
            'steps' => ['Full assessment with CBCT scan', 'Digital planning', 'Extractions if needed', 'Six NTA implants placed per arch', 'Same-day provisional bridge', 'Healing: 3-6 months', 'Final prosthesis fitted'],
            'recovery' => 'Swelling for 5-7 days. Soft diet for 8-12 weeks.',
            'guarantee' => 'All-on-6 NTA carries a 10-year implant guarantee and 5-year prosthesis guarantee.',
        ],
        'PKG-106' => [
            'steps' => ['Full assessment with CBCT scan', 'Digital planning', 'Extractions if needed', 'Six Straumann BLX implants placed per arch', 'Same-day provisional bridge', 'Healing: 3-6 months', 'Final zirconia prosthesis fitted'],
            'recovery' => 'Swelling for 5-7 days. Soft diet for 8-12 weeks.',
            'guarantee' => 'All-on-6 Straumann carries a lifetime implant guarantee and 5-year prosthesis guarantee.',
        ],
        'PRO-005' => [
            'steps' => ['Implant uncovered if submerged', 'Impression taken at implant level', 'Custom abutment designed and milled', 'Abutment torqued to specification', 'Final restoration placed'],
            'recovery' => 'No significant recovery. Gum settles around abutment within 1-2 weeks.',
            'guarantee' => null,
        ],
        'PRO-001' => [
            'steps' => ['Implant-level impressions taken', 'Trilor bar designed digitally (CAD/CAM)', 'Bar milled from Trilor material', 'Bar fitted and verified on implants', 'Final prosthesis attached to bar'],
            'recovery' => 'No additional recovery beyond implant healing.',
            'guarantee' => null,
        ],
        'PRO-002' => [
            'steps' => ['Implant-level impressions taken', 'Titanium bar designed and milled', 'Bar fitted and verified on implants', 'Final prosthesis attached to bar'],
            'recovery' => 'No additional recovery beyond implant healing.',
            'guarantee' => null,
        ],
        'PRO-003' => [
            'steps' => ['Implant-level impressions taken', 'Toronto-style bar designed', 'Bar milled from titanium with Trilor framework', 'Bar fitted on implants', 'Final prosthesis screwed to bar'],
            'recovery' => 'No additional recovery beyond implant healing.',
            'guarantee' => null,
        ],
        'PRO-004' => [
            'steps' => ['Implant exposed', 'NTA abutment selected and placed', 'Torqued to recommended specification', 'Restoration placed on abutment'],
            'recovery' => 'No significant recovery needed.',
            'guarantee' => null,
        ],
        'HYG-001' => [
            'steps' => ['Impressions taken from implant positions', 'Temporary denture designed in PMMA', 'Fitted and adjusted on surgery day', 'Adjusted as needed during healing'],
            'recovery' => 'May need adjustments as swelling subsides. Soft diet required. Remove for cleaning as instructed.',
            'guarantee' => null,
        ],
        'HYG-002' => [
            'steps' => ['Full mouth examination', 'Ultrasonic scaling to remove tarite', 'Hand scaling for detailed cleaning', 'Polishing with prophylaxis paste', 'Oral hygiene advice provided'],
            'recovery' => 'No recovery needed. Mild sensitivity possible for 24 hours.',
            'guarantee' => null,
        ],
        'HYG-003' => [
            'steps' => ['Ultrasonic scaling', 'Airflow powder jet cleaning to remove stains', 'Fine polishing', 'Fluoride application if needed'],
            'recovery' => 'No recovery needed. Avoid staining foods for 24 hours for best results.',
            'guarantee' => null,
        ],
        'HYG-004' => [
            'steps' => ['Full periodontal assessment', 'Local anaesthetic if needed', 'Ultrasonic and hand scaling below gumline', 'Root surfaces smoothed', 'Antimicrobial rinse applied', 'Review in 4-6 weeks'],
            'recovery' => 'Gums may be tender for 2-3 days. Some temperature sensitivity is normal. Continue gentle brushing.',
            'guarantee' => null,
        ],
        'HYG-005' => [
            'steps' => ['Impressions or digital scan of teeth', 'Custom night guard fabricated', 'Fitted and adjusted for comfort', 'Wear instructions provided'],
            'recovery' => 'No recovery. May take a few nights to adjust to wearing.',
            'guarantee' => null,
        ],
        'GEN-002' => [
            'steps' => ['Decay removed under local anaesthetic', 'Cavity prepared and etched', 'Composite resin placed in layers', 'Each layer cured with UV light', 'Shaped and polished to natural finish'],
            'recovery' => 'Numbness wears off in 2-3 hours. Avoid eating on that side until sensation returns.',
            'guarantee' => null,
        ],
        'GEN-003' => [
            'steps' => ['X-ray to assess root anatomy', 'Local anaesthetic administered', 'Infected pulp tissue removed', 'Root canals cleaned, shaped and disinfected', 'Canals sealed with biocompatible material', 'Crown recommended to protect the tooth'],
            'recovery' => 'Tenderness on biting for up to 2 weeks. Over-the-counter pain relief usually sufficient.',
            'guarantee' => null,
        ],
        'GEN-004' => [
            'steps' => ['Gum tissue assessed', 'Local anaesthetic administered', 'Excess gum tissue reshaped with laser or scalpel', 'Gumline contoured for symmetry', 'Healing reviewed in 1-2 weeks'],
            'recovery' => 'Mild discomfort for 2-3 days. Soft diet for a few days. Avoid spicy food.',
            'guarantee' => null,
        ],
    ];

    $updated = 0;
    foreach ($data as $code => $info) {
        $t = \App\Models\TreatmentTemplate::where('code', $code)->first();
        if ($t) {
            $t->update([
                'procedure_steps' => $info['steps'],
                'recovery_info' => $info['recovery'],
                'guarantee_text' => $info['guarantee'],
            ]);
            $updated++;
        }
    }
    return response("Updated {$updated} templates with rich data.", 200, ['Content-Type' => 'text/plain']);
});

require __DIR__.'/auth.php';
