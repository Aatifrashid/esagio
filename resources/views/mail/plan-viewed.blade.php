<x-mail::message>
# Treatment Plan {{ $isFirstView ? 'First Viewed' : 'Viewed Again' }}

**{{ $patientName }}** has {{ $isFirstView ? 'viewed their treatment plan for the first time' : 'viewed their treatment plan again' }}.

**Plan:** {{ $planTitle }}<br>
**Total views:** {{ $viewCount }}

<x-mail::button :url="url('/dashboard')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
