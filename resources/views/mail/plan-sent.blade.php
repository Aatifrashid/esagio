<x-mail::message>
# Hello {{ $patientName }},

Your treatment plan has been prepared by **{{ $clinicName }}**.

**Plan:** {{ $plan->title }}

Please review your treatment plan by clicking the button below.

<x-mail::button :url="$planUrl">
View Your Treatment Plan
</x-mail::button>

If you have any questions, please do not hesitate to contact us.

Thanks,<br>
{{ $clinicName }}
</x-mail::message>
