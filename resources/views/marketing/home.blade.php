<x-layouts.marketing>

{{-- Hero --}}
<section class="relative bg-[#0A2540] text-white overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, #E8663D 0%, transparent 50%), radial-gradient(circle at 80% 20%, #0A2540 0%, transparent 50%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-4 py-1.5 text-sm mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Now with built-in CRM. No Salesforce bolt-on required.
            </div>
            <h1 class="font-['Fraunces'] text-5xl lg:text-6xl font-bold leading-tight mb-6">
                Treatment plans that close more cases
            </h1>
            <p class="text-xl text-gray-300 mb-4 max-w-2xl">
                The clinical software modern dental clinics actually want to use. CRM included. Half the price of BrightPlans.
            </p>
            <p class="text-gray-400 mb-10">
                Interactive plans. Patient-facing portals. Video consultations. Signature capture. All of it.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/register" class="inline-flex justify-center items-center px-8 py-4 bg-[#E8663D] text-white font-semibold rounded-xl hover:bg-[#E8663D]/90 transition text-lg">
                    Start Free &mdash; No Card Required
                </a>
                <a href="/demo" class="inline-flex justify-center items-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition text-lg">
                    Try Interactive Demo
                </a>
            </div>
            <p class="text-sm text-gray-500 mt-4">Free plan available. No credit card to start.</p>
        </div>
    </div>
</section>

{{-- Social proof strip --}}
<section class="bg-gray-50 border-y border-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-gray-500">
            <span>Trusted by dental clinics across the UK, Turkey, Spain and beyond</span>
            <span class="text-gray-300">|</span>
            <span class="font-semibold text-gray-700">4.9/5</span>
            <span>from 200+ reviews</span>
            <span class="text-gray-300">|</span>
            <span>GDPR compliant</span>
        </div>
    </div>
</section>

{{-- Feature columns --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-['Fraunces'] text-4xl font-bold text-[#0A2540] mb-4">
                Everything a modern clinic needs
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Built by people who have worked in dental clinics. Not by enterprise software consultants charging £400/day.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl border border-gray-100 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-[#0A2540]/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#0A2540]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900 mb-2">Treatment Plan Builder</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Drag-and-drop plan builder with phases, optional add-ons, before/after photos, and 3D animations. Patients can view, sign and pay from their phone.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-100 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-[#E8663D]/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#E8663D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900 mb-2">CRM Built In</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Pipeline boards, activity tracking, tasks, patient history. All tied to your treatment plans. No separate subscription needed.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-100 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-900 mb-2">Video Consultations</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Built-in video calls linked directly to treatment plans. Share screen, annotate photos, and capture consent. No Zoom add-on.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Pricing snippet --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-['Fraunces'] text-4xl font-bold text-[#0A2540] mb-4">Simple, honest pricing</h2>
            <p class="text-gray-600">No per-seat fees. No hidden charges. Cancel any time.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach([
                ['name' => 'Free', 'price' => '£0', 'plans' => '3 plans/month', 'highlight' => false],
                ['name' => 'Starter', 'price' => '£19', 'plans' => '20 plans/month', 'highlight' => false],
                ['name' => 'Professional', 'price' => '£49', 'plans' => '100 plans/month', 'highlight' => true],
                ['name' => 'Agency', 'price' => '£99', 'plans' => '500 plans/month', 'highlight' => false],
            ] as $tier)
            <div class="bg-white rounded-2xl border {{ $tier['highlight'] ? 'border-[#E8663D] shadow-lg' : 'border-gray-200' }} p-6 text-center relative">
                @if($tier['highlight'])
                <div class="absolute -top-3 left-0 right-0 flex justify-center">
                    <span class="bg-[#E8663D] text-white text-xs font-semibold px-3 py-1 rounded-full">Popular</span>
                </div>
                @endif
                <div class="font-semibold text-gray-900 mb-1">{{ $tier['name'] }}</div>
                <div class="text-3xl font-bold text-[#0A2540] mb-1">{{ $tier['price'] }}<span class="text-sm font-normal text-gray-500">/mo</span></div>
                <div class="text-sm text-gray-600 mb-4">{{ $tier['plans'] }}</div>
                <a href="/pricing" class="text-sm text-[#E8663D] font-medium hover:underline">See all features</a>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="/pricing" class="inline-flex items-center gap-2 text-[#0A2540] font-medium hover:underline">
                Compare all plans
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-['Fraunces'] text-4xl font-bold text-[#0A2540] mb-4">Common questions</h2>
        </div>

        <div class="space-y-4" x-data="{open: null}">
            @foreach([
                ['q' => 'Do I need a credit card to start?', 'a' => 'No. The free plan is genuinely free. No card required. You only need one if you upgrade to a paid plan.'],
                ['q' => 'Can patients sign on their phone?', 'a' => 'Yes. Every plan gets a unique patient link. They can view it, ask questions, and sign from any device. No app download needed.'],
                ['q' => 'How is Esagio different from BrightPlans?', 'a' => 'BrightPlans charges per user and locks core CRM features behind premium tiers. Esagio includes CRM at every level, starts at £19, and does not charge per-seat fees.'],
                ['q' => 'Can I customise plans with my clinic branding?', 'a' => 'Yes. Upload your logo, set your brand colours, and choose fonts. Patients see your branding throughout.'],
                ['q' => 'Is the data GDPR compliant?', 'a' => 'Yes. Data is hosted in the EU. We are registered with the ICO and follow all relevant UK and EU data protection rules.'],
                ['q' => 'Can I cancel at any time?', 'a' => 'Yes. Monthly plans cancel at end of billing period. No exit fees, no lock-ins.'],
            ] as $i => $faq)
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button
                    class="w-full text-left px-6 py-4 flex justify-between items-center font-medium text-gray-900 hover:bg-gray-50 transition"
                    @click="open = open === {{ $i }} ? null : {{ $i }}"
                >
                    {{ $faq['q'] }}
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-4 text-gray-600 text-sm leading-relaxed">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Footer CTA --}}
<section class="bg-[#0A2540] text-white py-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-['Fraunces'] text-4xl font-bold mb-4">
            Ready to close more cases?
        </h2>
        <p class="text-gray-300 text-lg mb-8">
            Join clinics across the UK and Europe using Esagio every day. Takes five minutes to set up.
        </p>
        <a href="/register" class="inline-flex items-center px-8 py-4 bg-[#E8663D] text-white font-semibold rounded-xl hover:bg-[#E8663D]/90 transition text-lg">
            Start free today
        </a>
        <p class="text-gray-500 text-sm mt-4">No credit card. No commitment.</p>
    </div>
</section>

</x-layouts.marketing>
