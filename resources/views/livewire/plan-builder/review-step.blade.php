<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Review and Send</h2>
        <p class="text-gray-500 text-sm mt-1">Review the completed plan and send it to the patient.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Left: plan preview --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Plan card --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="bg-clinical px-6 py-5">
                    <p class="text-white/50 text-xs uppercase tracking-widest mb-1">Treatment Plan</p>
                    <h3 class="font-serif text-white text-xl font-semibold">{{ $plan->title ?: 'Untitled Plan' }}</h3>
                    @if($plan->patient)
                        <p class="text-white/70 text-sm mt-1">For {{ $plan->patient->first_name }} {{ $plan->patient->last_name }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-xs text-white/50">{{ $plan->reference_number }}</span>
                        @if($plan->valid_until)
                            <span class="text-xs text-white/50">Valid until {{ $plan->valid_until->format('j M Y') }}</span>
                        @endif
                    </div>
                </div>

                <div class="p-5">
                    @if($plan->items->isNotEmpty())
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Treatments</h4>
                        <div class="space-y-2">
                            @foreach($plan->items as $item)
                                <div class="flex items-start justify-between gap-3 pb-2 border-b border-gray-50 last:border-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800">{{ $item->name }}</p>
                                        @if($item->description)
                                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ $item->description }}</p>
                                        @endif
                                        @if($item->is_optional)
                                            <span class="inline-block mt-1 text-xs text-amber-500 font-medium">Optional</span>
                                        @endif
                                    </div>
                                    <div class="text-right flex-none">
                                        <p class="text-sm font-semibold text-gray-800">{{ $plan->currency }} {{ number_format($item->line_total, 2) }}</p>
                                        @if($item->quantity > 1)
                                            <p class="text-xs text-gray-400">{{ $item->quantity }} × {{ $plan->currency }} {{ number_format($item->unit_price, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700">Plan total</span>
                            <span class="text-xl font-bold text-clinical">{{ $plan->currency }} {{ number_format($plan->total_amount ?? 0, 2) }}</span>
                        </div>

                        @if($plan->deposit_amount)
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">Deposit required</span>
                                <span class="text-sm font-medium text-accent">{{ $plan->currency }} {{ number_format($plan->deposit_amount, 2) }}</span>
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">No treatment items.</p>
                    @endif
                </div>
            </div>

            {{-- Validation checklist --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Readiness check</h4>

                @php
                    $checks = [
                        ['label' => 'Patient assigned', 'pass' => (bool) $plan->patient_id],
                        ['label' => 'Plan title set', 'pass' => (bool) $plan->title],
                        ['label' => 'Treatment items added', 'pass' => $plan->items->isNotEmpty()],
                        ['label' => 'All items have a price', 'pass' => $plan->items->where('is_optional', false)->every(fn($i) => $i->unit_price > 0)],
                        ['label' => 'Currency configured', 'pass' => (bool) $plan->currency],
                    ];
                @endphp

                <div class="space-y-2">
                    @foreach($checks as $check)
                        <div class="flex items-center gap-2.5">
                            @if($check['pass'])
                                <svg class="h-4 w-4 text-green-500 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-red-400 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                            <span class="text-sm {{ $check['pass'] ? 'text-gray-600' : 'text-red-500 font-medium' }}">{{ $check['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                @if(!empty($validationErrors))
                    <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg">
                        <p class="text-xs font-semibold text-red-700 mb-1">Issues to resolve before sending:</p>
                        @foreach($validationErrors as $error)
                            <p class="text-xs text-red-600">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: send options --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Send method --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Send via</h4>

                <div class="space-y-2 mb-5">
                    @foreach(['email' => ['Email', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'], 'whatsapp' => ['WhatsApp', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'], 'sms' => ['SMS', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'], 'link' => ['Copy link', 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1']] as $method => [$label, $icon])
                        <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-clinical/30 hover:bg-clinical/5 transition {{ $sendMethod === $method ? 'border-clinical bg-clinical/5' : '' }}">
                            <input
                                wire:model="sendMethod"
                                type="radio"
                                value="{{ $method }}"
                                class="text-clinical focus:ring-clinical border-gray-300"
                            />
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                @if(empty($validationErrors))
                    <button
                        wire:click="send"
                        wire:confirm="Send this plan to the patient?"
                        @if($plan->status !== 'draft') disabled @endif
                        class="w-full bg-accent hover:bg-accent-600 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg px-4 py-3 transition flex items-center justify-center gap-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        @if($plan->status === 'draft')
                            Send plan
                        @else
                            Plan already sent
                        @endif
                    </button>
                @else
                    <div class="w-full bg-gray-100 text-gray-400 text-sm font-medium rounded-lg px-4 py-3 text-center cursor-not-allowed">
                        Resolve issues before sending
                    </div>
                @endif
            </div>

            {{-- Other actions --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Other actions</h4>

                <div class="space-y-2">
                    <button
                        wire:click="copyPublicLink"
                        class="w-full flex items-center gap-2 px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition"
                    >
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy public link
                    </button>

                    <button
                        wire:click="downloadPdf"
                        class="w-full flex items-center gap-2 px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition"
                    >
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </button>
                </div>
            </div>

            {{-- Plan metadata --}}
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                <div class="space-y-2 text-xs text-gray-500">
                    <div class="flex items-center justify-between">
                        <span>Status</span>
                        <span class="font-medium text-gray-700">{{ ucfirst($plan->status) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Version</span>
                        <span class="font-medium text-gray-700">v{{ $plan->version }}</span>
                    </div>
                    @if($plan->sent_at)
                        <div class="flex items-center justify-between">
                            <span>Sent</span>
                            <span class="font-medium text-gray-700">{{ $plan->sent_at->format('j M Y H:i') }}</span>
                        </div>
                    @endif
                    @if($plan->viewed_at)
                        <div class="flex items-center justify-between">
                            <span>Viewed</span>
                            <span class="font-medium text-gray-700">{{ $plan->viewed_count }}x</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
