<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - Esagio' : 'Esagio - Treatment plans that close more cases' }}</title>
    <meta name="description" content="{{ $description ?? 'The clinical software for modern dental clinics. Built by clinic operators, priced for clinic budgets.' }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700|inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100" x-data="{mobileOpen: false}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('svg/logo.svg') }}" alt="Esagio" class="h-8">
                </a>
                <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                    <a href="/features" class="hover:text-gray-900 transition">Features</a>
                    <a href="/pricing" class="hover:text-gray-900 transition">Pricing</a>
                    <a href="/vs-brightplans" class="hover:text-gray-900 transition">vs BrightPlans</a>
                    <a href="/blog" class="hover:text-gray-900 transition">Blog</a>
                    <a href="/demo" class="hover:text-gray-900 transition">Demo</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <a href="/login" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">Log in</a>
                    <a href="/register" class="inline-flex items-center px-4 py-2 bg-[#0A2540] text-white text-sm font-medium rounded-lg hover:bg-[#0A2540]/90 transition">Start Free</a>
                </div>
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div x-show="mobileOpen" x-cloak x-collapse class="md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-3">
                <a href="/features" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Features</a>
                <a href="/pricing" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Pricing</a>
                <a href="/vs-brightplans" class="block text-sm font-medium text-gray-600 hover:text-gray-900">vs BrightPlans</a>
                <a href="/blog" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Blog</a>
                <a href="/demo" class="block text-sm font-medium text-gray-600 hover:text-gray-900">Demo</a>
                <div class="pt-3 border-t border-gray-100 flex items-center gap-4">
                    <a href="/login" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log in</a>
                    <a href="/register" class="inline-flex items-center px-4 py-2 bg-[#0A2540] text-white text-sm font-medium rounded-lg hover:bg-[#0A2540]/90 transition">Start Free</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-[#0A2540] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <img src="{{ asset('svg/logo-light.svg') }}" alt="Esagio" class="h-7 mb-4">
                    <p class="text-sm text-gray-400">Treatment plans that close more cases.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-sm mb-3">Product</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/features" class="hover:text-white transition">Features</a></li>
                        <li><a href="/pricing" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="/demo" class="hover:text-white transition">Demo</a></li>
                        <li><a href="/vs-brightplans" class="hover:text-white transition">vs BrightPlans</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-sm mb-3">Resources</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/blog" class="hover:text-white transition">Blog</a></li>
                        <li><a href="/about" class="hover:text-white transition">About</a></li>
                        <li><a href="/contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-sm mb-3">Legal</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/privacy" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms" class="hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-sm text-gray-500">
                &copy; {{ date('Y') }} Esagio. All rights reserved.
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
