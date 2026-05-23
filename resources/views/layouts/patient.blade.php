<!DOCTYPE html>
<html lang="{{ $planLanguage ?? str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ isset($title) ? $title : 'Your Treatment Plan - Esagio' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700|inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(isset($clinicBranding))
    <style>
        :root {
            --clinic-primary: {{ $clinicBranding->primary_colour ?? '#0A2540' }};
            --clinic-accent: {{ $clinicBranding->accent_colour ?? '#E8663D' }};
        }
    </style>
    @endif
    @stack('head')
</head>
<body class="font-sans antialiased bg-white">
    @isset($slot)
        {{ $slot }}
    @else
        @yield('content')
    @endisset
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
