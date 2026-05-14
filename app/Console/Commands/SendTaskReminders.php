<?php

namespace App\Console\Commands;

use App\Models\CrmTask;
use App\Notifications\TaskDueNotification;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    protected $signature = 'reminders:tasks';

    protected $description = 'Send reminders for tasks due today or overdue';

    public function handle(): int
    {
        $this->info('Checking for due tasks...');

        $sent = 0;

        $tasks = CrmTask::query()
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['patient', 'assignedUser'])
            ->get();

        foreach ($tasks as $task) {
            $user = $task->assignedUser;

            if ($user?->email) {
                $user->notify(new TaskDueNotification($task));
                $sent++;
            }
        }

        $this->info("Sent {$sent} task reminder(s).");

        return self::SUCCESS;
    }
}
