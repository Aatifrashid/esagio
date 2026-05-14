<?php

namespace App\Console\Commands;

use App\Models\VideoConsultationSession;
use App\Notifications\VideoConsultationReminderNotification;
use Illuminate\Console\Command;

class SendVideoReminders extends Command
{
    protected $signature = 'reminders:video';

    protected $description = 'Send video consultation reminders 24 hours and 1 hour before scheduled time';

    public function handle(): int
    {
        $this->info('Checking for upcoming video consultations...');

        $sent = 0;

        // 24-hour reminders: sessions between 23 and 25 hours from now
        $twentyFourHourSessions = VideoConsultationSession::query()
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_for', [
                now()->addHours(23),
                now()->addHours(25),
            ])
            ->with(['patient', 'clinic'])
            ->get();

        foreach ($twentyFourHourSessions as $session) {
            if ($session->patient?->email) {
                $session->patient->notify(new VideoConsultationReminderNotification($session, '24h'));
                $sent++;
            }
        }

        // 1-hour reminders: sessions between 30 minutes and 90 minutes from now
        $oneHourSessions = VideoConsultationSession::query()
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_for', [
                now()->addMinutes(30),
                now()->addMinutes(90),
            ])
            ->with(['patient', 'clinic'])
            ->get();

        foreach ($oneHourSessions as $session) {
            if ($session->patient?->email) {
                $session->patient->notify(new VideoConsultationReminderNotification($session, '1h'));
                $sent++;
            }
        }

        $this->info("Sent {$sent} video consultation reminder(s).");

        return self::SUCCESS;
    }
}
