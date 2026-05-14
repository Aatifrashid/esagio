<x-mail::message>
# Treatment Plan Declined

**{{ $patientName }}** has declined the treatment plan.

**Plan:** {{ $planTitle }}<br>
**Declined at:** {{ $declinedAt }}

@if($reason)
**Reason given:** {{ $reason }}
@endif

<x-mail::button :url="url('/dashboard')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
