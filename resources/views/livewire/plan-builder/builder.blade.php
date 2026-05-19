<div
    class="flex flex-col h-screen bg-gray-50 overflow-hidden"
    x-data="planBuilder()"
    x-on:keydown.ctrl.s.window.prevent="$wire.forceSave()"
    x-on:keydown.meta.s.window.prevent="$wire.forceSave()"
    x-on:keydown.ctrl.enter.window.prevent="$wire.nextStep()"
    x-on:keydown.meta.enter.window.prevent="$wire.nextStep()"
    x-on:keydown.ctrl.k.window.prevent="$wire.openCommandPalette()"
    x-on:keydown.meta.k.window.prevent="$wire.openCommandPalette()"
    x-on:keydown.escape.window="$wire.closeCommandPalette()"
    x-on:copy-to-clipboard.window="copyToClipboard($event.detail.text)"
>

    {{-- Sticky top bar --}}
    <header class="flex-none h-14 bg-clinical border-b border-clinical-700 flex items-center px-4 gap-4 z-30">
        <div class="flex items-center gap-2 min-w-0 flex-1">
            <span class="font-serif text-white text-sm font-medium truncate max-w-[200px]">
                {{ $plan->reference_number }}
            </span>
            <input
                wire:model.blur="plan.title"
                wire:change="autoSave"
                type="text"
                class="bg-transparent border-0 border-b border-white/30 text-white placeholder-white/50 text-sm font-medium focus:border-white/70 focus:ring-0 px-0 py-0.5 min-w-0 flex-1 max-w-xs"
                placeholder="Plan title..."
                value="{{ $plan->title }}"
            />
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                @if($plan->status === 'draft') bg-gray-600 text-gray-200
                @elseif($plan->status === 'sent') bg-blue-600 text-blue-100
                @elseif($plan->status === 'viewed') bg-yellow-600 text-yellow-100
                @elseif($plan->status === 'accepted') bg-green-600 text-green-100
                @elseif($plan->status === 'declined') bg-red-600 text-red-100
                @else bg-purple-600 text-purple-100 @endif">
                {{ ucfirst($plan->status) }}
            </span>
        </div>

        {{-- Save indicator --}}
        <div class="flex items-center gap-1.5 text-xs">
            @if($saveStatus === 'saving')
                <svg class="animate-spin h-3.5 w-3.5 text-white/60" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <span class="text-white/60">Saving...</span>
            @elseif($saveStatus === 'saved')
                <svg class="h-3.5 w-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-white/60">
                    Saved {{ $lastSavedAt ? \Carbon\Carbon::parse($lastSavedAt)->diffForHumans() : '' }}
                </span>
            @else
                <svg class="h-3.5 w-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span class="text-red-400">Save error</span>
            @endif
        </div>

        <button
            wire:click="togglePreview"
            class="hidden lg:flex items-center gap-1.5 text-xs text-white/70 hover:text-white border border-white/20 hover:border-white/40 rounded px-2.5 py-1 transition"
        >
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            {{ $showPreview ? 'Hide preview' : 'Show preview' }}
        </button>

        @if($plan->patient_id)
            <a
                href="{{ route('patient.plan.show', ['token' => $plan->public_token ?? '#']) }}"
                target="_blank"
                class="hidden lg:flex items-center gap-1.5 text-xs text-white/70 hover:text-white border border-white/20 hover:border-white/40 rounded px-2.5 py-1 transition"
            >
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Patient view
            </a>
        @endif

        <button
            wire:click="sendPlan"
            wire:confirm="Send this plan to the patient?"
            @if($plan->status !== 'draft') disabled @endif
            class="flex items-center gap-1.5 text-sm font-medium bg-accent hover:bg-accent-600 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-lg px-4 py-1.5 transition"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Send plan
        </button>
    </header>

    {{-- Three-column body --}}
    <div class="flex flex-1 min-h-0">

        {{-- Left rail: step navigation --}}
        <aside class="flex-none w-60 bg-white border-r border-gray-100 flex flex-col overflow-y-auto">

            {{-- Plan meta --}}
            <div class="p-4 border-b border-gray-100">
                @if($plan->patient)
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-clinical/10 flex items-center justify-center text-clinical text-xs font-bold">
                            {{ strtoupper(substr($plan->patient->first_name, 0, 1) . substr($plan->patient->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $plan->patient->first_name }} {{ $plan->patient->last_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $plan->patient->email }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-gray-400">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-400">No patient selected</span>
                    </div>
                @endif
            </div>

            {{-- Step list --}}
            <nav class="p-2 flex-1">
                @foreach($stepLabels as $num => $label)
                    @php
                        $isActive = $currentStep === $num;
                        $isComplete = $num < $currentStep;
                    @endphp
                    <button
                        wire:click="goToStep({{ $num }})"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm mb-0.5 transition-colors
                            {{ $isActive ? 'bg-clinical/10 text-clinical font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                    >
                        <span class="flex-none w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $isActive ? 'bg-clinical text-white' : ($isComplete ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-500') }}">
                            @if($isComplete)
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                {{ $num }}
                            @endif
                        </span>
                        <span class="truncate">{{ $label }}</span>
                    </button>
                @endforeach
            </nav>

            {{-- Keyboard hints --}}
            <div class="p-3 border-t border-gray-100">
                <p class="text-xs text-gray-400 mb-1.5">Keyboard shortcuts</p>
                <div class="flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Save</span>
                        <kbd class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Ctrl+S</kbd>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Next step</span>
                        <kbd class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Ctrl+Enter</kbd>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Commands</span>
                        <kbd class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Ctrl+K</kbd>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Centre: step content --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <div class="flex-1 p-6 min-h-0">
                @switch($currentStep)
                    @case(1)
                        <livewire:plan-builder.patient-step :plan="$plan" :key="'patient-'.$plan->id" />
                        @break
                    @case(2)
                        <livewire:plan-builder.diagnosis-step :plan="$plan" :key="'diagnosis-'.$plan->id" />
                        @break
                    @case(3)
                        <livewire:plan-builder.treatment-step :plan="$plan" :key="'treatment-'.$plan->id" />
                        @break
                    @case(4)
                        <livewire:plan-builder.visuals-step :plan="$plan" :key="'visuals-'.$plan->id" />
                        @break
                    @case(5)
                        <livewire:plan-builder.content-step :plan="$plan" :key="'content-'.$plan->id" />
                        @break
                    @case(6)
                        <livewire:plan-builder.video-step :plan="$plan" :key="'video-'.$plan->id" />
                        @break
                    @case(7)
                        <livewire:plan-builder.settings-step :plan="$plan" :key="'settings-'.$plan->id" />
                        @break
                    @case(8)
                        <livewire:plan-builder.review-step :plan="$plan" :key="'review-'.$plan->id" />
                        @break
                @endswitch
            </div>
        </main>

        {{-- Right rail: live preview --}}
        @if($showPreview)
        <aside class="hidden xl:flex flex-none w-[380px] bg-gray-100 border-l border-gray-200 flex-col overflow-hidden">
            <div class="flex-none flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Patient preview</span>
                <button wire:click="togglePreview" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Preview content --}}
            <div class="flex-1 overflow-y-auto p-4">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    {{-- Preview header --}}
                    <div class="bg-clinical px-5 py-6">
                        <p class="text-white/60 text-xs font-medium uppercase tracking-widest mb-1">Treatment Plan</p>
                        <h2 class="font-serif text-white text-xl font-semibold">{{ $plan->title ?: 'Untitled Plan' }}</h2>
                        @if($plan->patient)
                            <p class="text-white/70 text-sm mt-1">For {{ $plan->patient->first_name }} {{ $plan->patient->last_name }}</p>
                        @endif
                        <p class="text-white/50 text-xs mt-2">Ref: {{ $plan->reference_number }}</p>
                    </div>

                    {{-- Preview items --}}
                    <div class="p-5">
                        @if($plan->items->isNotEmpty())
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Treatments</h3>
                            <div class="space-y-2">
                                @foreach($plan->items->take(5) as $item)
                                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $item->name ?: 'Unnamed item' }}</p>
                                            @if($item->is_optional)
                                                <span class="text-xs text-gray-400">Optional</span>
                                            @endif
                                        </div>
                                        <span class="text-sm font-semibold text-clinical">
                                            {{ $plan->currency }} {{ number_format($item->line_total, 2) }}
                                        </span>
                                    </div>
                                @endforeach
                                @if($plan->items->count() > 5)
                                    <p class="text-xs text-gray-400 text-center pt-1">+{{ $plan->items->count() - 5 }} more items</p>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Total</span>
                                <span class="text-lg font-bold text-clinical">
                                    {{ $plan->currency }} {{ number_format($plan->total_amount ?? 0, 2) }}
                                </span>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <svg class="h-10 w-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm">No items added yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </aside>
        @endif
    </div>

    {{-- Sticky bottom bar --}}
    <footer class="flex-none h-12 bg-white border-t border-gray-100 flex items-center px-4 gap-4 z-30">
        <div class="flex items-center gap-3 flex-1">
            <span class="text-sm text-gray-500">
                <span class="font-semibold text-gray-800">{{ $plan->items->count() }}</span>
                {{ Str::plural('item', $plan->items->count()) }}
            </span>
            <span class="text-gray-300">|</span>
            <span class="text-sm">
                <span class="font-semibold text-clinical">{{ $plan->currency ?? 'GBP' }} {{ number_format($plan->total_amount ?? 0, 2) }}</span>
                <span class="text-gray-400 ml-1">total</span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button
                wire:click="previousStep"
                @if($currentStep === 1) disabled @endif
                class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed border border-gray-200 hover:border-gray-300 rounded-lg px-3 py-1.5 transition"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>

            <span class="text-xs text-gray-400">Step {{ $currentStep }} of 8</span>

            <button
                wire:click="nextStep"
                @if($currentStep === 8) disabled @endif
                class="flex items-center gap-1.5 text-sm text-white bg-clinical hover:bg-clinical-700 disabled:opacity-30 disabled:cursor-not-allowed rounded-lg px-3 py-1.5 transition"
            >
                Next
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <div class="hidden lg:flex items-center gap-1.5 text-xs text-gray-400">
            <kbd class="bg-gray-100 rounded px-1.5 py-0.5">Ctrl+K</kbd>
            <span>commands</span>
        </div>
    </footer>

    {{-- Command palette --}}
    @if($showCommandPalette)
    <div
        class="fixed inset-0 bg-black/50 z-50 flex items-start justify-center pt-[15vh]"
        x-on:click.self="$wire.closeCommandPalette()"
    >
        <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden mx-4">
            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                <svg class="h-5 w-5 text-gray-400 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    wire:model.live="searchQuery"
                    type="text"
                    placeholder="Go to step, search templates..."
                    class="flex-1 border-0 focus:ring-0 text-sm text-gray-800 placeholder-gray-400 p-0"
                    x-init="$el.focus()"
                />
                <button
                    wire:click="closeCommandPalette"
                    class="text-xs text-gray-400 bg-gray-100 rounded px-1.5 py-0.5 hover:bg-gray-200 transition"
                >
                    Esc
                </button>
            </div>

            <div class="p-2 max-h-72 overflow-y-auto">
                <p class="text-xs text-gray-400 px-3 pt-2 pb-1 font-medium uppercase tracking-wide">Navigate</p>
                @foreach($stepLabels as $num => $label)
                    <button
                        wire:click="goToStep({{ $num }}); closeCommandPalette()"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 text-left transition"
                    >
                        <span class="w-5 h-5 rounded bg-clinical/10 text-clinical text-xs font-bold flex items-center justify-center">{{ $num }}</span>
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                        @if($currentStep === $num)
                            <span class="ml-auto text-xs text-gray-400">Current</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
