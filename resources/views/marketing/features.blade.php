<x-layouts.marketing :title="'Features'">

<section class="bg-[#0A2540] text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="font-['Fraunces'] text-5xl font-bold mb-4">Everything, included</h1>
        <p class="text-gray-300 text-lg">One platform. No bolt-ons. No per-feature pricing.</p>
    </div>
</section>

{{-- Feature sections with alternating layout --}}
@php
$features = [
    [
        'title' => 'Treatment Plan Builder',
        'subtitle' => 'Build once. Reuse forever.',
        'description' => 'Create plans from templates in seconds. Drag phases. Add optional treatments. Attach before/after photos, 3D animations and material options. Patients see a clean, clinical presentation. Not a wall of text.',
        'points' => ['Phase-based structure', 'Optional add-on treatments', 'Before/after photo galleries', '3D procedure animations', 'Material comparisons (implant brands, etc.)'],
        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    ],
    [
        'title' => 'Patient Portal',
        'subtitle' => 'No app. No friction.',
        'description' => 'Every plan gets a unique link. Patients open it on their phone. They read, ask questions via comments, choose optional treatments, sign digitally, and pay a deposit. All without creating an account.',
        'points' => ['Unique shareable link per plan', 'Mobile-first design', 'Digital signature capture', 'Deposit payment via Stripe', 'Password-protected option'],
        'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    ],
    [
        'title' => 'Built-in CRM',
        'subtitle' => 'Pipeline boards. Activity tracking. Tasks.',
        'description' => 'Track every lead from first enquiry to accepted plan. Kanban pipeline, activity timeline, follow-up tasks. It is tied directly to treatment plans so nothing slips through the gaps.',
        'points' => ['Drag-and-drop pipeline stages', 'Activity log on every patient', 'Task management with due dates', 'Lead source tracking', 'Revenue forecasting'],
        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    ],
    [
        'title' => 'Video Consultations',
        'subtitle' => 'Face to face. From anywhere.',
        'description' => 'Video calls built into the platform. Link them directly to a treatment plan. Share screen, annotate photos, and capture verbal consent. No separate Zoom subscription.',
        'points' => ['HD video calls in-browser', 'Screen sharing', 'Linked to treatment plans', 'Consultation notes alongside video', 'Session recordings (coming soon)'],
        'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
    ],
    [
        'title' => 'Price Lists & Materials',
        'subtitle' => 'Multiple currencies. Multiple brands.',
        'description' => 'Set up price lists by currency. Attach treatment-specific materials (implant brands, ceramic types). Patients see the options clearly, without needing to call and ask.',
        'points' => ['Multi-currency price lists', 'Per-treatment pricing', 'Material option comparisons', 'Premium material badges', 'Warranty information'],
        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
];
@endphp

@foreach($features as $i => $feature)
<section class="py-20 {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center {{ $i % 2 !== 0 ? 'lg:flex-row-reverse' : '' }}">
            <div class="{{ $i % 2 !== 0 ? 'lg:order-2' : '' }}">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-[#0A2540]/10 rounded-xl mb-4">
                    <svg class="w-6 h-6 text-[#0A2540]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-[#E8663D] font-semibold text-sm mb-1">{{ $feature['subtitle'] }}</p>
                <h2 class="font-['Fraunces'] text-3xl font-bold text-[#0A2540] mb-4">{{ $feature['title'] }}</h2>
                <p class="text-gray-600 leading-relaxed mb-6">{{ $feature['description'] }}</p>
                <ul class="space-y-2">
                    @foreach($feature['points'] as $point)
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $point }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="{{ $i % 2 !== 0 ? 'lg:order-1' : '' }} bg-gray-100 rounded-2xl aspect-video flex items-center justify-center text-gray-400">
                <span class="text-sm">Feature screenshot</span>
            </div>
        </div>
    </div>
</section>
@endforeach

<section class="bg-[#0A2540] text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="font-['Fraunces'] text-4xl font-bold mb-4">See it for yourself</h2>
        <p class="text-gray-300 mb-8">Try the interactive demo. No sign-up required.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/demo" class="inline-flex justify-center items-center px-8 py-4 bg-[#E8663D] text-white font-semibold rounded-xl hover:bg-[#E8663D]/90 transition">
                Try interactive demo
            </a>
            <a href="/register" class="inline-flex justify-center items-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition">
                Start free account
            </a>
        </div>
    </div>
</section>

</x-layouts.marketing>
