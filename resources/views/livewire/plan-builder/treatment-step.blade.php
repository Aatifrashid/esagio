<div x-data="{ dragging: null }">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-clinical">Treatment Plan</h2>
            <p class="text-gray-500 text-sm mt-1">Add treatments from templates or create custom line items.</p>
        </div>
        <div class="flex items-center gap-2">
            <button
                wire:click="$toggle('showPhases')"
                class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition"
            >
                {{ $showPhases ? 'Hide phases' : 'Show phases' }}
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-0 border-b border-gray-200 mb-5">
        @foreach(['quick_add' => 'Quick Add', 'custom' => 'Custom Item', 'compare' => 'Compare'] as $tab => $label)
            <button
                wire:click="$set('activeTab', '{{ $tab }}')"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition -mb-px
                    {{ $activeTab === $tab ? 'border-clinical text-clinical' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: add panel --}}
        <div class="lg:col-span-1 space-y-4">

            @if($activeTab === 'quick_add')
                {{-- Template search --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Search templates</label>
                    <div class="relative mb-3">
                        <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            wire:model.live.debounce.300ms="templateSearch"
                            type="text"
                            placeholder="Search treatments..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                        />
                    </div>

                    @if(count($templates) > 0)
                        <div class="space-y-1">
                            @foreach($templates as $template)
                                <button
                                    wire:click="addFromTemplate({{ $template['id'] }})"
                                    class="w-full text-left px-3 py-2.5 rounded-lg hover:bg-clinical/5 hover:border-clinical/20 border border-transparent transition group"
                                >
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-clinical truncate">{{ $template['name'] }}</p>
                                            @if($template['description_short'])
                                                <p class="text-xs text-gray-400 truncate mt-0.5">{{ $template['description_short'] }}</p>
                                            @endif
                                        </div>
                                        <svg class="h-4 w-4 text-gray-300 group-hover:text-clinical flex-none mt-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @elseif(strlen($templateSearch) >= 2)
                        <p class="text-sm text-gray-400 text-center py-3">No templates found</p>
                    @else
                        {{-- Recent templates --}}
                        @if(count($recentTemplates) > 0)
                            <div>
                                <p class="text-xs text-gray-400 mb-2">Recently used</p>
                                <div class="space-y-1">
                                    @foreach(array_slice($recentTemplates, 0, 6) as $template)
                                        <button
                                            wire:click="addFromTemplate({{ $template['id'] }})"
                                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-clinical/5 border border-transparent hover:border-clinical/20 transition group"
                                        >
                                            <p class="text-sm text-gray-700 group-hover:text-clinical truncate">{{ $template['name'] }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- AI suggestions --}}
                @if(count($suggestions) > 0)
                    <div class="bg-clinical/5 border border-clinical/20 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-clinical uppercase tracking-wide">Suggestions</p>
                            <button wire:click="refreshSuggestions" class="text-clinical/50 hover:text-clinical transition">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-2">
                            @foreach(array_slice($suggestions, 0, 4) as $suggestion)
                                <div class="bg-white rounded-lg p-3">
                                    <p class="text-sm font-medium text-gray-800">{{ $suggestion['name'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $suggestion['reason'] }}</p>
                                    <button
                                        wire:click="addCustomItem"
                                        class="mt-2 text-xs text-clinical hover:text-clinical-700 font-medium transition"
                                    >
                                        Add item +
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @elseif($activeTab === 'custom')
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-sm text-gray-600 mb-4">Add a blank treatment item and fill in the details manually.</p>
                    <button
                        wire:click="addCustomItem"
                        class="w-full bg-clinical hover:bg-clinical-700 text-white text-sm font-medium rounded-lg px-4 py-3 transition flex items-center justify-center gap-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add custom item
                    </button>
                </div>

            @else
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Compare treatment options side by side. Coming soon.</p>
                </div>
            @endif
        </div>

        {{-- Right: items list --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                @if($items->isEmpty())
                    <div class="text-center py-16 px-6">
                        <svg class="h-12 w-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500 font-medium">No treatments added yet</p>
                        <p class="text-sm text-gray-400 mt-1">Search templates or add a custom item from the panel on the left.</p>
                    </div>
                @else
                    {{-- Table header --}}
                    <div class="grid grid-cols-12 gap-2 px-4 py-2 bg-gray-50 border-b border-gray-100 text-xs font-medium text-gray-500 uppercase tracking-wide">
                        <div class="col-span-1"></div>
                        <div class="col-span-5">Treatment</div>
                        <div class="col-span-2 text-right">Qty</div>
                        <div class="col-span-2 text-right">Price</div>
                        <div class="col-span-1 text-right">Total</div>
                        <div class="col-span-1"></div>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach($items as $item)
                            <div
                                class="grid grid-cols-12 gap-2 px-4 py-3 items-center group hover:bg-gray-50/50 transition"
                                wire:key="item-{{ $item->id }}"
                            >
                                {{-- Drag handle --}}
                                <div class="col-span-1 flex items-center justify-center cursor-grab text-gray-300 hover:text-gray-500">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 6a2 2 0 100-4 2 2 0 000 4zM8 14a2 2 0 100-4 2 2 0 000 4zM8 22a2 2 0 100-4 2 2 0 000 4zM16 6a2 2 0 100-4 2 2 0 000 4zM16 14a2 2 0 100-4 2 2 0 000 4zM16 22a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </div>

                                {{-- Name --}}
                                <div class="col-span-5">
                                    <input
                                        type="text"
                                        value="{{ $item->name }}"
                                        wire:change="updateItem({{ $item->id }}, 'name', $event.target.value)"
                                        placeholder="Treatment name..."
                                        class="w-full border-0 bg-transparent text-sm font-medium text-gray-800 focus:ring-2 focus:ring-clinical/20 rounded px-1 py-0.5 focus:bg-white transition"
                                    />
                                    @if($item->is_optional)
                                        <span class="text-xs text-amber-500 font-medium">Optional</span>
                                    @endif
                                </div>

                                {{-- Qty --}}
                                <div class="col-span-2">
                                    <input
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        wire:change="updateItem({{ $item->id }}, 'quantity', $event.target.value)"
                                        min="1"
                                        class="w-full border border-gray-200 rounded text-sm text-right px-2 py-1 focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                                    />
                                </div>

                                {{-- Unit price --}}
                                <div class="col-span-2">
                                    <input
                                        type="number"
                                        value="{{ $item->unit_price }}"
                                        wire:change="updateItem({{ $item->id }}, 'unit_price', $event.target.value)"
                                        min="0"
                                        step="0.01"
                                        class="w-full border border-gray-200 rounded text-sm text-right px-2 py-1 focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                                    />
                                </div>

                                {{-- Line total --}}
                                <div class="col-span-1 text-sm font-semibold text-gray-700 text-right">
                                    {{ number_format($item->line_total, 0) }}
                                </div>

                                {{-- Actions --}}
                                <div class="col-span-1 flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition">
                                    <button
                                        wire:click="toggleOptional({{ $item->id }})"
                                        title="{{ $item->is_optional ? 'Make required' : 'Make optional' }}"
                                        class="p-1 rounded text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="duplicateItem({{ $item->id }})"
                                        title="Duplicate"
                                        class="p-1 rounded text-gray-400 hover:text-clinical hover:bg-clinical/10 transition"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="removeItem({{ $item->id }})"
                                        wire:confirm="Remove this item?"
                                        title="Remove"
                                        class="p-1 rounded text-gray-400 hover:text-red-500 hover:bg-red-50 transition"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="border-t border-gray-100 bg-gray-50/50 px-4 py-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Required treatments</span>
                                <span class="font-semibold text-gray-800">{{ $plan->currency ?? 'GBP' }} {{ number_format($total, 2) }}</span>
                            </div>
                            @if($optionalTotal > 0)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Optional treatments</span>
                                    <span class="text-gray-500">{{ $plan->currency ?? 'GBP' }} {{ number_format($optionalTotal, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                <span class="text-sm font-semibold text-gray-700">Total</span>
                                <span class="text-lg font-bold text-clinical">{{ $plan->currency ?? 'GBP' }} {{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
