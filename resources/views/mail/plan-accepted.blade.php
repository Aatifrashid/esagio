<x-mail::message>
# Treatment Plan Accepted

**{{ $patientName }}** has accepted the treatment plan.

**Plan:** {{ $planTitle }}<br>
**Accepted at:** {{ $acceptedAt }}

<x-mail::button :url="url('/dashboard')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
