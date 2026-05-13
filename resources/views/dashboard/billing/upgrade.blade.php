<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Your Plan - Esagio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-[#0A2540] mb-3">Choose your plan</h1>
            <p class="text-gray-600">Start free. Upgrade when you need more.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Starter --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Starter</h2>
                    <div class="mt-1 flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-[#0A2540]">£19</span>
                        <span class="text-gray-500 text-sm">/month</span>
                    </div>
                </div>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li>20 treatment plans per month</li>
                    <li>Patient portal</li>
                    <li>PDF downloads</li>
                    <li>Basic CRM</li>
                    <li>Email support</li>
                </ul>
                <form method="POST" action="{{ route('billing.subscribe', 'starter') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-[#0A2540] text-white text-sm font-medium hover:bg-[#0a2540]/90 transition">
                        Subscribe to Starter
                    </button>
                </form>
            </div>

            {{-- Professional --}}
            <div class="bg-white rounded-2xl border-2 border-[#E8663D] p-6 shadow-md relative">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#E8663D] text-white text-xs font-semibold px-3 py-1 rounded-full">Most Popular</span>
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Professional</h2>
                    <div class="mt-1 flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-[#0A2540]">£49</span>
                        <span class="text-gray-500 text-sm">/month</span>
                    </div>
                </div>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li>100 treatment plans per month</li>
                    <li>Video consultations (600 mins)</li>
                    <li>Before/after galleries</li>
                    <li>Full CRM pipeline</li>
                    <li>Priority support</li>
                </ul>
                <form method="POST" action="{{ route('billing.subscribe', 'professional') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-[#E8663D] text-white text-sm font-medium hover:bg-[#E8663D]/90 transition">
                        Subscribe to Professional
                    </button>
                </form>
            </div>

            {{-- Agency --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Agency</h2>
                    <div class="mt-1 flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-[#0A2540]">£99</span>
                        <span class="text-gray-500 text-sm">/month</span>
                    </div>
                </div>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li>500 treatment plans per month</li>
                    <li>Video consultations (3,000 mins)</li>
                    <li>Multi-location support</li>
                    <li>White-label branding</li>
                    <li>Dedicated account manager</li>
                </ul>
                <form method="POST" action="{{ route('billing.subscribe', 'agency') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-[#0A2540] text-white text-sm font-medium hover:bg-[#0a2540]/90 transition">
                        Subscribe to Agency
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-sm text-gray-500 mt-8">
            All plans include a 14-day free trial. Cancel any time. No lock-ins.
        </p>
    </div>
</body>
</html>
