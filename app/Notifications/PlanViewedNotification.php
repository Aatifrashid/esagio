<?php

namespace App\Notifications;

use App\Models\TreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanViewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected TreatmentPlan $plan,
        protected bool $isFirstView = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $patient = $this->plan->patient;
        $subject = $this->isFirstView
            ? 'Treatment Plan First Viewed - '.$patient->first_name.' '.$patient->last_name
            : 'Treatment Plan Viewed Again - '.$patient->first_name.' '.$patient->last_name;

        return (new MailMessage)
            ->subject($subject)
            ->markdown('mail.plan-viewed', [
                'plan' => $this->plan,
                'patientName' => $patient->first_name.' '.$patient->last_name,
                'planTitle' => $this->plan->title,
                'isFirstView' => $this->isFirstView,
                'viewCount' => $this->plan->viewed_count,
            ]);
    }
}
