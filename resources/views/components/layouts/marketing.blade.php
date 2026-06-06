<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - Esagio' : 'Esagio - Treatment plans that close more cases' }}</title>
    <meta name="description" content="{{ $description ?? 'The clinical software for modern dental clinics. Built by clinic operators, priced for clinic budgets.' }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=hanken-grotesk:400,500,600,700,800|space-mono:400,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/marketing.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100" x-data="{mobileOpen: false}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="inline-flex items-baseline gap-[0.04em]">
                    <svg class="h-[0.72em] w-auto translate-y-[0.05em]" viewBox="0 0 60 84" fill="#E8663D" style="font-size:24px;height:17px;"><rect x="6" y="4" width="15" height="76" rx="7.5"/><rect x="6" y="4" width="46" height="15" rx="7.5"/><rect x="6" y="34.5" width="36" height="15" rx="7.5"/><rect x="6" y="65" width="46" height="15" rx="7.5"/></svg>
                    <span class="font-display font-extrabold text-2xl tracking-tight text-navy">sagio</span>
                </a>
                <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-[#51607A]">
                    <a href="/features" class="hover:text-navy transition">Features</a>
                    <a href="/pricing" class="hover:text-navy transition">Pricing</a>
                    <a href="/vs-brightplans" class="hover:text-navy transition">vs BrightPlans</a>
                    <a href="/blog" class="hover:text-navy transition">Blog</a>
                    <a href="/demo" class="hover:text-navy transition">Demo</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="/admin" class="btn btn-dark btn-sm">Dashboard</a>
                    @else
                        <a href="/login" class="text-sm font-semibold text-[#51607A] hover:text-navy transition">Log in</a>
                        <a href="/register" class="btn btn-dark btn-sm">Start Free</a>
                    @endauth
                </div>
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div x-show="mobileOpen" x-cloak x-collapse class="md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-3">
                <a href="/features" class="block text-sm font-semibold text-[#51607A] hover:text-navy">Features</a>
                <a href="/pricing" class="block text-sm font-semibold text-[#51607A] hover:text-navy">Pricing</a>
                <a href="/vs-brightplans" class="block text-sm font-semibold text-[#51607A] hover:text-navy">vs BrightPlans</a>
                <a href="/blog" class="block text-sm font-semibold text-[#51607A] hover:text-navy">Blog</a>
                <a href="/demo" class="block text-sm font-semibold text-[#51607A] hover:text-navy">Demo</a>
                <div class="pt-3 border-t border-gray-100 flex items-center gap-4">
                    @auth
                        <a href="/admin" class="btn btn-dark btn-sm">Dashboard</a>
                    @else
                        <a href="/login" class="text-sm font-semibold text-[#51607A] hover:text-navy">Log in</a>
                        <a href="/register" class="btn btn-dark btn-sm">Start Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-navy text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div class="inline-flex items-baseline gap-[0.04em] mb-4">
                        <svg class="h-[12px] w-auto translate-y-[0.05em]" viewBox="0 0 60 84" fill="#E8663D"><rect x="6" y="4" width="15" height="76" rx="7.5"/><rect x="6" y="4" width="46" height="15" rx="7.5"/><rect x="6" y="34.5" width="36" height="15" rx="7.5"/><rect x="6" y="65" width="46" height="15" rx="7.5"/></svg>
                        <span class="font-display font-extrabold text-lg tracking-tight text-white">sagio</span>
                    </div>
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
