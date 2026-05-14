<?php

namespace App\Notifications;

use App\Models\TreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanDeclinedNotification extends Notification implements ShouldQueue
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
        $patient = $this->plan->patient;

        return (new MailMessage)
            ->subject('Treatment Plan Declined - '.$patient->first_name.' '.$patient->last_name)
            ->markdown('mail.plan-declined', [
                'plan' => $this->plan,
                'patientName' => $patient->first_name.' '.$patient->last_name,
                'planTitle' => $this->plan->title,
                'declinedAt' => $this->plan->declined_at->format('d M Y H:i'),
                'reason' => $this->plan->decline_reason,
            ]);
    }
}
