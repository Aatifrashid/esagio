<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ClinicTeamMember;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanComment;
use App\Models\TreatmentPlanEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PlanViewController extends Controller
{
    public function show(Request $request, string $publicToken): View|RedirectResponse
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->with([
                'patient',
                'clinic',
                'items.material',
                'items.template',
                'phases',
                'sections',
                'options.items.material',
                'toothCharts',
                'comments',
                'events',
            ])
            ->firstOrFail();

        // Password gate
        if ($plan->public_password) {
            $sessionKey = 'patient_plan_auth_'.$publicToken;
            if (! $request->session()->get($sessionKey)) {
                return redirect()->route('patient.plan.password', ['token' => $publicToken]);
            }
        }

        // Expired check done in view via $isExpired flag
        $isExpired = $plan->valid_until && $plan->valid_until->isPast();

        if (! $isExpired) {
            $plan->recordView(
                $request->ip(),
                $request->userAgent() ?? ''
            );

            TreatmentPlanEvent::create([
                'treatment_plan_id' => $plan->id,
                'event_type' => 'viewed',
                'patient_action' => true,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => null,
                'created_at' => now(),
            ]);
        }

        // Load clinic branding separately (no clinic scope since this is public)
        $branding = $plan->clinic->branding ?? null;

        try {
            $teamMembers = ClinicTeamMember::withoutGlobalScopes()
                ->where('clinic_id', $plan->clinic_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } catch (\Throwable) {
            $teamMembers = collect();
        }

        $freeTier = in_array($plan->clinic->plan_tier ?? 'free', ['free', null]);

        return view('patient.plan-show', compact('plan', 'isExpired', 'branding', 'teamMembers', 'freeTier'));
    }

    public function showPasswordGate(string $publicToken): View
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        return view('patient.plan-password', compact('plan', 'publicToken'));
    }

    public function submitPassword(Request $request, string $publicToken): RedirectResponse
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        $request->validate(['password' => 'required|string']);

        if (! Hash::check($request->password, $plan->public_password)) {
            return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
        }

        $request->session()->put('patient_plan_auth_'.$publicToken, true);

        return redirect()->route('patient.plan.show', ['token' => $publicToken]);
    }

    public function accept(Request $request, string $publicToken): RedirectResponse
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        $validated = $request->validate([
            'signature' => 'required|string',
            'full_name' => 'required|string|max:255',
        ]);

        // Validate signature is base64
        $signature = $validated['signature'];
        if (! str_starts_with($signature, 'data:image/')) {
            $decoded = base64_decode($signature, true);
            if ($decoded === false) {
                return back()->withErrors(['signature' => 'Invalid signature data.']);
            }
        }

        $plan->accept($signature, $request->ip(), $request->userAgent() ?? '');

        TreatmentPlanEvent::create([
            'treatment_plan_id' => $plan->id,
            'event_type' => 'accepted',
            'patient_action' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['full_name' => $validated['full_name']],
            'created_at' => now(),
        ]);

        return redirect()
            ->route('patient.plan.show', ['token' => $publicToken])
            ->with('success', 'Thank you! Your treatment plan has been accepted.');
    }

    public function decline(Request $request, string $publicToken): RedirectResponse
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        $validated = $request->validate([
            'decline_reason' => 'nullable|string|max:1000',
        ]);

        $plan->decline($validated['decline_reason'] ?? '');

        TreatmentPlanEvent::create([
            'treatment_plan_id' => $plan->id,
            'event_type' => 'declined',
            'patient_action' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['reason' => $validated['decline_reason'] ?? null],
            'created_at' => now(),
        ]);

        return redirect()
            ->route('patient.plan.show', ['token' => $publicToken])
            ->with('success', 'We have recorded your response. Thank you for letting us know.');
    }

    public function addComment(Request $request, string $publicToken): RedirectResponse
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        TreatmentPlanComment::create([
            'treatment_plan_id' => $plan->id,
            'author_type' => 'patient',
            'author_name' => $plan->patient->first_name.' '.$plan->patient->last_name,
            'content' => $validated['content'],
            'is_internal' => false,
        ]);

        TreatmentPlanEvent::create([
            'treatment_plan_id' => $plan->id,
            'event_type' => 'commented',
            'patient_action' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => null,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('patient.plan.show', ['token' => $publicToken])
            ->with('success', 'Your comment has been added.')
            ->withFragment('comments');
    }

    public function toggleOptionalItem(Request $request, string $publicToken): RedirectResponse
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        $validated = $request->validate([
            'item_id' => 'required|integer|exists:treatment_plan_items,id',
        ]);

        $item = $plan->items()->where('id', $validated['item_id'])->firstOrFail();

        // Only allow toggling optional items
        $item->update(['is_optional' => ! $item->is_optional]);
        $plan->recalculateTotal();

        return redirect()
            ->route('patient.plan.show', ['token' => $publicToken])
            ->with('success', 'Your selection has been updated.');
    }

    public function downloadPdf(string $publicToken): Response
    {
        $plan = TreatmentPlan::withoutGlobalScopes()
            ->where('public_token', $publicToken)
            ->with([
                'patient',
                'clinic',
                'items.material',
                'items.template',
                'phases',
                'sections',
            ])
            ->firstOrFail();

        TreatmentPlanEvent::create([
            'treatment_plan_id' => $plan->id,
            'event_type' => 'pdf_downloaded',
            'patient_action' => true,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => null,
            'created_at' => now(),
        ]);

        $branding = $plan->clinic->branding ?? null;
        $primaryColour = $branding?->primary_colour ?? '#0A2540';
        $accentColour = $branding?->accent_colour ?? '#E8663D';

        $html = view('patient.plan-pdf', compact('plan', 'primaryColour', 'accentColour'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/mpdf-tmp'),
            'format' => 'A4',
            'default_font' => 'helvetica',
            'margin_top' => 20,
            'margin_bottom' => 25,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        $mpdf->SetTitle($plan->title ?: 'Treatment Plan');
        $mpdf->SetAuthor($plan->clinic->name);
        $mpdf->WriteHTML($html);

        $filename = 'Treatment-Plan-'.$plan->reference_number.'.pdf';

        return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
