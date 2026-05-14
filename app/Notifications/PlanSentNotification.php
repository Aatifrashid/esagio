<?php

namespace App\Notifications;

use App\Models\TreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected TreatmentPlan $plan
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Treatment Plan from '.$this->plan->clinic->name)
            ->markdown('mail.plan-sent', [
                'plan' => $this->plan,
                'patientName' => $notifiable->first_name,
                'clinicName' => $this->plan->clinic->name,
                'planUrl' => route('patient.plan.show', $this->plan->public_token),
            ]);
    }
}
