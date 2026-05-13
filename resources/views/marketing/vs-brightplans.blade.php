<x-layouts.marketing :title="'Esagio vs BrightPlans'">

<section class="bg-[#0A2540] text-white py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="font-['Fraunces'] text-5xl font-bold mb-4">Esagio vs BrightPlans</h1>
        <p class="text-gray-300 text-lg">A straight comparison. No spin.</p>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 p-6 bg-[#E8663D]/5 border border-[#E8663D]/20 rounded-2xl text-center">
            <p class="text-[#0A2540] font-medium text-lg">
                The average clinic switching from BrightPlans saves <strong>£720+ per year</strong>.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left px-6 py-4 text-gray-500 font-medium w-2/5">Feature</th>
                            <th class="text-center px-6 py-4 text-[#E8663D] font-bold text-base">Esagio</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-semibold">BrightPlans</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach([
                            ['Starting price', '£0 (free forever)', '£69/month'],
                            ['Per-seat fees', 'Never', 'Yes, £19/seat'],
                            ['CRM included', 'Yes, fully featured', 'Paid add-on'],
                            ['Video consultations', 'Included from Professional', 'Separate subscription'],
                            ['E-signature', 'Yes', 'Yes'],
                            ['Patient portal', 'Yes, mobile-optimised', 'Yes'],
                            ['Clinic branding', 'From Starter (£19)', 'From £69'],
                            ['Before/after gallery', 'Yes', 'Limited'],
                            ['Multi-language plans', 'Yes (8+ languages)', 'English only'],
                            ['PDF generation', 'Yes, instant', 'Yes'],
                            ['Tooth chart', 'Yes, interactive SVG', 'Basic'],
                            ['API access', 'On roadmap', 'Enterprise only'],
                            ['UK-based support', 'Yes, real humans', 'Ticketing system'],
                            ['Contract required', 'No', 'Annual contract'],
                            ['GDPR/ICO registered', 'Yes', 'Yes'],
                        ] as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3.5 text-gray-700 font-medium">{{ $row[0] }}</td>
                            <td class="px-6 py-3.5 text-center text-green-700 font-medium">{{ $row[1] }}</td>
                            <td class="px-6 py-3.5 text-center text-gray-500">{{ $row[2] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Migration CTA --}}
        <div class="mt-16 bg-[#0A2540] rounded-2xl p-10 text-white text-center">
            <h2 class="font-['Fraunces'] text-3xl font-bold mb-3">Ready to make the switch?</h2>
            <p class="text-gray-300 mb-6 max-w-lg mx-auto">
                We will help you migrate your templates and set up your clinic. Takes less than a day. Most clinics are up and running the same afternoon.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="inline-flex justify-center items-center px-8 py-3.5 bg-[#E8663D] text-white font-semibold rounded-xl hover:bg-[#E8663D]/90 transition">
                    Start your free account
                </a>
                <a href="/contact" class="inline-flex justify-center items-center px-8 py-3.5 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition">
                    Talk to migration team
                </a>
            </div>
        </div>
    </div>
</section>

</x-layouts.marketing>
