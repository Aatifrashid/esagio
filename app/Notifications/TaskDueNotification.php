<?php

namespace App\Notifications;

use App\Models\CrmTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected CrmTask $task
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task Due: '.$this->task->title)
            ->markdown('mail.task-due', [
                'task' => $this->task,
                'userName' => $notifiable->name,
                'patientName' => $this->task->patient
                    ? $this->task->patient->first_name.' '.$this->task->patient->last_name
                    : null,
                'dueDate' => $this->task->due_date->format('d M Y'),
            ]);
    }
}
