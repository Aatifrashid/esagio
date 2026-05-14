<x-mail::message>
# Video Consultation Reminder

Hello {{ $patientName }},

This is a reminder that you have a video consultation with **{{ $clinicName }}** in **{{ $timeLabel }}**.

**Scheduled for:** {{ $scheduledFor }}

Please ensure you are ready at the scheduled time.

Thanks,<br>
{{ $clinicName }}
</x-mail::message>
