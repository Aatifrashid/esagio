<?php

namespace App\Services\FollowUp;

use App\Models\FollowUpLog;
use App\Models\FollowUpSequence;
use App\Models\FollowUpStep;
use App\Models\TreatmentPlan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FollowUpEngine
{
    /**
     * Process all due follow-ups across clinics on the Professional+ tier.
     */
    public function processDueFollowUps(): int
    {
        $processed = 0;

        $sequences = FollowUpSequence::query()
            ->where('is_active', true)
            ->whereHas('clinic', function ($q) {
                $q->whereIn('plan_tier', ['professional', 'enterprise'])
                    ->where('is_active', true);
            })
            ->with(['steps', 'clinic'])
            ->get();

        foreach ($sequences as $sequence) {
            $plans = $this->getMatchingPlans($sequence);

            foreach ($plans as $plan) {
                $triggerTimestamp = $this->getTriggerTimestamp($plan, $sequence->trigger_event);

                if (! $triggerTimestamp) {
                    continue;
                }

                foreach ($sequence->steps as $step) {
                    if ($this->shouldSendStep($step, $plan, $triggerTimestamp)) {
                        $this->sendStep($step, $plan);
                        $processed++;
                    }
                }
            }
        }

        return $processed;
    }

    /**
     * Cancel all pending follow-ups for a given treatment plan.
     */
    public function cancelForPlan(TreatmentPlan $plan): int
    {
        return FollowUpLog::query()
            ->where('treatment_plan_id', $plan->id)
            ->where('status', FollowUpLog::STATUS_PENDING)
            ->update([
                'status' => FollowUpLog::STATUS_CANCELLED,
            ]);
    }

    /**
     * Get treatment plans that match the sequence trigger event.
     */
    protected function getMatchingPlans(FollowUpSequence $sequence): Collection
    {
        $query = TreatmentPlan::query()
            ->where('clinic_id', $sequence->clinic_id)
            ->whereNull('deleted_at');

        return match ($sequence->trigger_event) {
            FollowUpSequence::TRIGGER_PLAN_SENT => $query
                ->whereNotNull('sent_at')
                ->whereNull('accepted_at')
                ->whereNull('declined_at')
                ->get(),
            FollowUpSequence::TRIGGER_PLAN_VIEWED => $query
                ->whereNotNull('viewed_at')
                ->whereNull('accepted_at')
                ->whereNull('declined_at')
                ->get(),
            FollowUpSequence::TRIGGER_PLAN_DECLINED => $query
                ->whereNotNull('declined_at')
                ->get(),
            default => collect(),
        };
    }

    /**
     * Get the timestamp that triggered the sequence for a given plan.
     */
    protected function getTriggerTimestamp(TreatmentPlan $plan, string $triggerEvent): ?Carbon
    {
        return match ($triggerEvent) {
            FollowUpSequence::TRIGGER_PLAN_SENT => $plan->sent_at,
            FollowUpSequence::TRIGGER_PLAN_VIEWED => $plan->viewed_at,
            FollowUpSequence::TRIGGER_PLAN_DECLINED => $plan->declined_at,
            default => null,
        };
    }

    /**
     * Determine whether a step should be sent for a given plan.
     */
    protected function shouldSendStep(FollowUpStep $step, TreatmentPlan $plan, Carbon $triggerTimestamp): bool
    {
        $dueAt = $triggerTimestamp->copy()->addHours($step->delay_hours);

        if ($dueAt->isFuture()) {
            return false;
        }

        // Check if this step has already been sent or is pending for this plan.
        $existingLog = FollowUpLog::query()
            ->where('follow_up_step_id', $step->id)
            ->where('treatment_plan_id', $plan->id)
            ->whereIn('status', [FollowUpLog::STATUS_PENDING, FollowUpLog::STATUS_SENT])
            ->exists();

        return ! $existingLog;
    }

    /**
     * Send a follow-up step for a treatment plan.
     */
    protected function sendStep(FollowUpStep $step, TreatmentPlan $plan): void
    {
        $patient = $plan->patient;

        if (! $patient || ! $patient->email) {
            Log::warning('Follow-up skipped: patient has no email address.', [
                'step_id' => $step->id,
                'plan_id' => $plan->id,
            ]);

            return;
        }

        $log = FollowUpLog::create([
            'follow_up_step_id' => $step->id,
            'treatment_plan_id' => $plan->id,
            'patient_id' => $patient->id,
            'status' => FollowUpLog::STATUS_PENDING,
        ]);

        try {
            if ($step->channel === FollowUpStep::CHANNEL_EMAIL) {
                $body = $this->renderTemplate($step->body_template, $plan, $patient);
                $subject = $this->renderTemplate($step->subject ?? 'Follow-up regarding your treatment plan', $plan, $patient);

                Mail::raw($body, function ($message) use ($patient, $subject, $plan) {
                    $message->to($patient->email, $patient->first_name.' '.$patient->last_name)
                        ->subject($subject)
                        ->replyTo($plan->clinic->email, $plan->clinic->name);
                });
            }

            $log->update([
                'status' => FollowUpLog::STATUS_SENT,
                'sent_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'status' => FollowUpLog::STATUS_FAILED,
            ]);

            Log::error('Follow-up send failed.', [
                'step_id' => $step->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Render a template string with plan and patient placeholders.
     */
    protected function renderTemplate(string $template, TreatmentPlan $plan, $patient): string
    {
        return str_replace(
            [
                '{{patient_name}}',
                '{{plan_title}}',
                '{{plan_link}}',
                '{{clinic_name}}',
                '{{plan_total}}',
            ],
            [
                $patient->first_name.' '.$patient->last_name,
                $plan->title,
                route('patient.plan.show', $plan->public_token),
                $plan->clinic->name,
                number_format($plan->total_amount, 2),
            ],
            $template
        );
    }
}
