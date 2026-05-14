<?php

namespace App\Notifications;

use App\Models\VideoConsultationSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VideoConsultationReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected VideoConsultationSession $session,
        protected string $reminderType = '24h' // '24h' or '1h'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $timeLabel = $this->reminderType === '1h' ? '1 hour' : '24 hours';

        return (new MailMessage)
            ->subject("Video Consultation Reminder - in {$timeLabel}")
            ->markdown('mail.video-reminder', [
                'session' => $this->session,
                'patientName' => $notifiable->first_name ?? $notifiable->name,
                'clinicName' => $this->session->clinic->name,
                'scheduledFor' => $this->session->scheduled_for->format('d M Y \a\t H:i'),
                'timeLabel' => $timeLabel,
            ]);
    }
}
