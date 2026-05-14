<x-mail::message>
# Task Due Reminder

Hello {{ $userName }},

You have a task that is due soon.

**Task:** {{ $task->title }}<br>
**Due date:** {{ $dueDate }}

@if($patientName)
**Patient:** {{ $patientName }}
@endif

@if($task->description)
**Details:** {{ $task->description }}
@endif

<x-mail::button :url="url('/dashboard')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
