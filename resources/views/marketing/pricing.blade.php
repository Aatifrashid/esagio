<x-layouts.marketing :title="'Pricing'">

<div x-data="{annual: false}">
<section class="bg-navy text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="font-display text-5xl font-extrabold tracking-tight mb-4">Honest pricing</h1>
        <p class="text-gray-300 text-lg">No per-seat fees. No surprise invoices. One flat monthly rate.</p>

        {{-- Monthly/annual toggle --}}
        <div class="mt-8 inline-flex items-center bg-white/10 rounded-full p-1">
            <button @click="annual = false" :class="!annual ? 'bg-white text-navy' : 'text-white'" class="px-4 py-2 rounded-full text-sm font-medium transition">Monthly</button>
            <button @click="annual = true" :class="annual ? 'bg-white text-navy' : 'text-white'" class="px-4 py-2 rounded-full text-sm font-medium transition">Annual <span class="text-accent font-semibold">-20%</span></button>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tier cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
            @foreach([
                ['name' => 'Free', 'monthly' => 0, 'annual' => 0, 'desc' => 'Try it without a card. Good for solo practitioners testing the platform.', 'plans' => '3', 'video' => 'None', 'branding' => false, 'crm' => false, 'highlight' => false],
                ['name' => 'Starter', 'monthly' => 19, 'annual' => 15, 'desc' => 'For a single clinic sending plans every week.', 'plans' => '20', 'video' => 'None', 'branding' => true, 'crm' => true, 'highlight' => false],
                ['name' => 'Professional', 'monthly' => 49, 'annual' => 39, 'desc' => 'The plan most busy clinics use. Video included.', 'plans' => '100', 'video' => '600 mins', 'branding' => true, 'crm' => true, 'highlight' => true],
                ['name' => 'Agency', 'monthly' => 99, 'annual' => 79, 'desc' => 'Multi-location. White-label. High volume.', 'plans' => '500', 'video' => '3,000 mins', 'branding' => true, 'crm' => true, 'highlight' => false],
            ] as $tier)
            <div class="{{ $tier['highlight'] ? 'bg-navy text-white border-2 border-accent shadow-xl' : 'bg-white border-gray-200 shadow-sm' }} rounded-[5px] border p-6 relative">
                @if($tier['highlight'])
                <div class="absolute -top-4 left-0 right-0 flex justify-center">
                    <span class="badge badge-brand">Most Popular</span>
                </div>
                @endif
                <h3 class="font-bold text-lg {{ $tier['highlight'] ? 'text-white' : 'text-gray-900' }} mb-1">{{ $tier['name'] }}</h3>
                <p class="text-xs {{ $tier['highlight'] ? 'text-gray-300' : 'text-gray-500' }} mb-4 leading-relaxed">{{ $tier['desc'] }}</p>
                <div class="mb-6">
                    <span x-show="!annual" class="font-display text-4xl font-bold {{ $tier['highlight'] ? 'text-white' : 'text-navy' }} tracking-tight">£{{ $tier['monthly'] }}</span>
                    <span x-show="annual" x-cloak class="font-display text-4xl font-bold {{ $tier['highlight'] ? 'text-white' : 'text-navy' }} tracking-tight">£{{ $tier['annual'] }}</span>
                    <span class="{{ $tier['highlight'] ? 'text-gray-300' : 'text-gray-400' }} text-sm">/mo</span>
                </div>
                <a href="/register" class="btn w-full {{ $tier['highlight'] ? 'btn-primary' : 'btn-dark' }}">
                    {{ $tier['monthly'] === 0 ? 'Start free' : 'Get started' }}
                </a>
            </div>
            @endforeach
        </div>

        {{-- Feature comparison matrix --}}
        <div class="bg-white rounded-[5px] border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-lg text-gray-900">Full feature comparison</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-gray-500 font-medium w-1/3">Feature</th>
                            <th class="text-center px-4 py-3 text-gray-700 font-semibold">Free</th>
                            <th class="text-center px-4 py-3 text-gray-700 font-semibold">Starter</th>
                            <th class="text-center px-4 py-3 text-accent font-semibold">Professional</th>
                            <th class="text-center px-4 py-3 text-gray-700 font-semibold">Agency</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach([
                            ['Plans per month', '3', '20', '100', '500'],
                            ['Patient portal', '✓', '✓', '✓', '✓'],
                            ['PDF export', '✓', '✓', '✓', '✓'],
                            ['E-signature capture', '✓', '✓', '✓', '✓'],
                            ['Clinic branding', '', '✓', '✓', '✓'],
                            ['CRM pipeline', '', '✓', '✓', '✓'],
                            ['Before/after gallery', '', '✓', '✓', '✓'],
                            ['Video consultations', '', '', '600 mins', '3,000 mins'],
                            ['Multi-location', '', '', '', '✓'],
                            ['White-label', '', '', '', '✓'],
                            ['Priority support', '', '', '✓', '✓'],
                            ['Dedicated manager', '', '', '', '✓'],
                        ] as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-700 font-medium">{{ $row[0] }}</td>
                            <td class="px-4 py-3 text-center text-gray-500">{{ $row[1] ?: '' }}</td>
                            <td class="px-4 py-3 text-center text-gray-500">{{ $row[2] ?: '' }}</td>
                            <td class="px-4 py-3 text-center text-accent font-medium">{{ $row[3] ?: '' }}</td>
                            <td class="px-4 py-3 text-center text-gray-500">{{ $row[4] ?: '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="font-display text-3xl font-bold text-navy tracking-tight mb-10 text-center">Questions about pricing</h2>
        <div class="space-y-4" x-data="{open: null}">
            @foreach([
                ['q' => 'Is VAT included?', 'a' => 'Prices shown are ex-VAT. UK clinics will see VAT added at checkout.'],
                ['q' => 'Can I switch plans mid-month?', 'a' => 'Yes. Upgrades take effect immediately. Downgrades apply at the next billing date.'],
                ['q' => 'Do unused plans roll over?', 'a' => 'No. Plans reset on the first of each month. This keeps the system fair for everyone.'],
                ['q' => 'What happens if I hit my plan limit?', 'a' => 'You will see a prompt to upgrade. Existing plans stay intact. You just cannot create new ones until you upgrade or the month resets.'],
            ] as $i => $faq)
            <div class="border border-gray-200 rounded-[5px] overflow-hidden">
                <button class="w-full text-left px-6 py-4 flex justify-between items-center font-medium text-gray-900 hover:bg-gray-50 transition" @click="open = open === {{ $i }} ? null : {{ $i }}">
                    {{ $faq['q'] }}
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}" x-cloak x-collapse class="px-6 pb-4 text-gray-600 text-sm">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

</div>

</x-layouts.marketing>
