<div x-data="{
    dragging: null,
    dragItemId: null,
    showToast: false,
    toastMsg: '',
    flash(msg) { this.toastMsg = msg; this.showToast = true; setTimeout(() => this.showToast = false, 2000); }
}">

    {{-- Toast --}}
    <div x-show="showToast" x-transition.opacity class="fixed top-4 right-4 z-50 bg-gray-900 text-white text-sm px-4 py-2.5 rounded-lg shadow-lg" x-cloak x-text="toastMsg"></div>

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-clinical">Treatment Plan</h2>
            <p class="text-gray-500 text-sm mt-1">Add treatments, assign teeth, and schedule visits.</p>
        </div>
        <div class="flex items-center gap-2">
            <button
                wire:click="toggleVisits"
                class="text-xs border rounded-lg px-3 py-1.5 transition
                    {{ $showVisits ? 'border-clinical bg-clinical/5 text-clinical' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}"
            >
                {{ $showVisits ? 'Hide visits' : 'Schedule visits' }}
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
                                    <button wire:click="addCustomItem" class="mt-2 text-xs text-clinical hover:text-clinical-700 font-medium transition">Add item +</button>
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
        <div class="lg:col-span-2 space-y-4">
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
                        <div class="col-span-4">Treatment</div>
                        <div class="col-span-2">Teeth</div>
                        <div class="col-span-1 text-right">Qty</div>
                        <div class="col-span-2 text-right">Price</div>
                        <div class="col-span-1 text-right">Total</div>
                        <div class="col-span-1"></div>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach($items as $item)
                            <div
                                class="grid grid-cols-12 gap-2 px-4 py-3 items-center group transition cursor-pointer {{ $activeItemId === $item->id ? 'bg-clinical/5 ring-1 ring-clinical/20 rounded-lg' : 'hover:bg-gray-50/50' }}"
                                wire:key="item-{{ $item->id }}"
                                wire:click="setActiveItem({{ $item->id }})"
                                draggable="true"
                                x-on:dragstart="dragItemId = {{ $item->id }}"
                                x-on:dragend="dragItemId = null"
                            >
                                {{-- Drag handle --}}
                                <div class="col-span-1 flex items-center justify-center cursor-grab text-gray-300 hover:text-gray-500">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 6a2 2 0 100-4 2 2 0 000 4zM8 14a2 2 0 100-4 2 2 0 000 4zM8 22a2 2 0 100-4 2 2 0 000 4zM16 6a2 2 0 100-4 2 2 0 000 4zM16 14a2 2 0 100-4 2 2 0 000 4zM16 22a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </div>

                                {{-- Name --}}
                                <div class="col-span-4" @click.stop>
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

                                {{-- Teeth --}}
                                <div class="col-span-2" wire:click.stop="setActiveItem({{ $item->id }})">
                                    @if(!empty($item->tooth_positions))
                                        <div class="flex flex-wrap gap-0.5">
                                            @foreach(array_slice($item->tooth_positions, 0, 4) as $tp)
                                                <span class="text-[10px] bg-clinical/10 text-clinical font-mono rounded px-1">{{ $tp }}</span>
                                            @endforeach
                                            @if(count($item->tooth_positions) > 4)
                                                <span class="text-[10px] text-gray-400">+{{ count($item->tooth_positions) - 4 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 {{ $activeItemId === $item->id ? 'text-clinical' : '' }}">
                                            {{ $activeItemId === $item->id ? 'Click teeth on chart →' : '—' }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Qty --}}
                                <div class="col-span-1" @click.stop>
                                    <input
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        wire:change="updateItem({{ $item->id }}, 'quantity', $event.target.value)"
                                        min="1"
                                        class="w-full border border-gray-200 rounded text-sm text-right px-2 py-1 focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                                    />
                                </div>

                                {{-- Unit price --}}
                                <div class="col-span-2" @click.stop>
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
                                <div class="col-span-1 flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition" @click.stop>
                                    <button wire:click="toggleOptional({{ $item->id }})" title="{{ $item->is_optional ? 'Make required' : 'Make optional' }}" class="p-1 rounded text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </button>
                                    <button wire:click="duplicateItem({{ $item->id }})" title="Duplicate" class="p-1 rounded text-gray-400 hover:text-clinical hover:bg-clinical/10 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                    <button wire:click="removeItem({{ $item->id }})" wire:confirm="Remove this item?" title="Remove" class="p-1 rounded text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

            {{-- ============================================================
                 TOOTH CHART — Assign teeth to treatments
            ============================================================ --}}
            @if($items->isNotEmpty())
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1">Treatment Tooth Chart</h3>
                    <p class="text-xs text-gray-400 mb-4">
                        @if($activeItemId)
                            Click teeth to assign them to the selected treatment.
                        @else
                            Select a treatment item first, then click teeth to assign.
                        @endif
                    </p>

                    @php
                        $treatedTeeth = $items->flatMap(fn($i) => $i->tooth_positions ?? [])->unique()->values()->toArray();

                        $toothHotspots = [
                            '18' => ['left' => '9.83%', 'top' => '7.36%', 'width' => '5.20%', 'height' => '31.46%', 'clip' => 'polygon(5.4% 81.7%, 10.8% 73.2%, 16.2% 61.7%, 21.6% 57.9%, 27.0% 55.3%, 32.4% 54.9%, 37.8% 63.4%, 43.2% 59.1%, 48.6% 56.2%, 54.1% 55.7%, 59.5% 58.3%, 64.9% 57.4%, 70.3% 54.9%, 75.7% 55.7%, 81.1% 58.7%, 86.5% 65.5%, 91.9% 81.7%, 91.9% 91.1%, 86.5% 93.6%, 81.1% 94.5%, 75.7% 94.9%, 70.3% 94.9%, 64.9% 94.9%, 59.5% 94.5%, 54.1% 93.6%, 48.6% 93.2%, 43.2% 93.6%, 37.8% 94.0%, 32.4% 94.5%, 27.0% 94.9%, 21.6% 94.9%, 16.2% 94.0%, 10.8% 93.2%, 5.4% 91.1%)'],
                            '17' => ['left' => '16.43%', 'top' => '7.36%', 'width' => '5.48%', 'height' => '31.46%', 'clip' => 'polygon(5.1% 83.4%, 10.3% 77.0%, 15.4% 64.3%, 20.5% 59.1%, 25.6% 55.3%, 30.8% 53.2%, 35.9% 51.9%, 41.0% 51.9%, 46.2% 52.8%, 51.3% 53.2%, 56.4% 59.1%, 61.5% 55.7%, 66.7% 52.3%, 71.8% 52.8%, 76.9% 54.5%, 82.1% 59.6%, 87.2% 77.4%, 92.3% 83.8%, 92.3% 88.5%, 87.2% 92.8%, 82.1% 94.0%, 76.9% 94.9%, 71.8% 95.3%, 66.7% 94.9%, 61.5% 94.9%, 56.4% 94.0%, 51.3% 93.6%, 46.2% 93.6%, 41.0% 94.0%, 35.9% 94.5%, 30.8% 94.9%, 25.6% 94.9%, 20.5% 94.5%, 15.4% 94.0%, 10.3% 92.8%, 5.1% 87.7%)'],
                            '16' => ['left' => '23.31%', 'top' => '7.36%', 'width' => '5.48%', 'height' => '31.46%', 'clip' => 'polygon(5.1% 82.1%, 10.3% 60.0%, 15.4% 53.2%, 20.5% 50.2%, 25.6% 49.8%, 30.8% 52.8%, 35.9% 51.1%, 41.0% 50.2%, 46.2% 52.3%, 51.3% 58.7%, 56.4% 63.4%, 61.5% 59.6%, 66.7% 55.3%, 71.8% 51.1%, 76.9% 50.2%, 82.1% 51.5%, 87.2% 59.6%, 92.3% 81.7%, 92.3% 89.4%, 87.2% 93.2%, 82.1% 94.5%, 76.9% 94.9%, 71.8% 94.9%, 66.7% 94.9%, 61.5% 94.5%, 56.4% 94.0%, 51.3% 93.6%, 46.2% 93.6%, 41.0% 94.0%, 35.9% 94.9%, 30.8% 94.9%, 25.6% 95.3%, 20.5% 94.9%, 15.4% 94.5%, 10.3% 92.8%, 5.1% 89.8%)'],
                            '15' => ['left' => '30.06%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 83.0%, 11.5% 79.6%, 15.4% 74.5%, 19.2% 68.9%, 23.1% 64.3%, 26.9% 60.0%, 30.8% 54.0%, 34.6% 49.4%, 38.5% 46.0%, 42.3% 43.4%, 46.2% 42.6%, 50.0% 42.6%, 53.8% 43.0%, 57.7% 43.8%, 61.5% 45.5%, 65.4% 48.5%, 69.2% 59.1%, 73.1% 66.0%, 76.9% 73.2%, 80.8% 78.3%, 84.6% 82.6%, 88.5% 86.4%, 88.5% 90.6%, 84.6% 92.8%, 80.8% 93.6%, 76.9% 94.0%, 73.1% 94.0%, 69.2% 94.5%, 65.4% 94.5%, 61.5% 94.5%, 57.7% 94.9%, 53.8% 95.3%, 50.0% 95.7%, 46.2% 95.7%, 42.3% 95.7%, 38.5% 95.3%, 34.6% 94.9%, 30.8% 94.5%, 26.9% 94.0%, 23.1% 93.6%, 19.2% 93.2%, 15.4% 92.8%, 11.5% 91.9%, 7.7% 90.2%)'],
                            '14' => ['left' => '34.69%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 81.7%, 11.5% 77.9%, 15.4% 73.2%, 19.2% 66.0%, 23.1% 59.6%, 26.9% 48.9%, 30.8% 40.4%, 34.6% 38.7%, 38.5% 38.3%, 42.3% 38.3%, 46.2% 38.7%, 50.0% 40.4%, 53.8% 42.6%, 57.7% 44.7%, 61.5% 48.1%, 65.4% 52.8%, 69.2% 57.4%, 73.1% 63.0%, 76.9% 69.8%, 80.8% 75.3%, 84.6% 80.4%, 88.5% 85.1%, 88.5% 89.8%, 84.6% 91.5%, 80.8% 92.8%, 76.9% 93.2%, 73.1% 93.6%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 95.3%, 53.8% 95.7%, 50.0% 95.7%, 46.2% 95.7%, 42.3% 95.7%, 38.5% 95.3%, 34.6% 94.9%, 30.8% 94.5%, 26.9% 94.0%, 23.1% 93.6%, 19.2% 93.2%, 15.4% 92.8%, 11.5% 91.9%, 7.7% 91.1%)'],
                            '13' => ['left' => '39.04%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 84.3%, 11.5% 78.7%, 15.4% 74.5%, 19.2% 68.5%, 23.1% 60.9%, 26.9% 51.1%, 30.8% 38.3%, 34.6% 32.3%, 38.5% 31.1%, 42.3% 31.1%, 46.2% 31.5%, 50.0% 32.3%, 53.8% 34.0%, 57.7% 37.0%, 61.5% 40.4%, 65.4% 44.3%, 69.2% 48.9%, 73.1% 54.9%, 76.9% 62.1%, 80.8% 70.6%, 84.6% 77.0%, 88.5% 83.0%, 88.5% 89.8%, 84.6% 91.5%, 80.8% 92.3%, 76.9% 92.8%, 73.1% 93.2%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 95.3%, 53.8% 95.7%, 50.0% 95.7%, 46.2% 95.7%, 42.3% 95.3%, 38.5% 94.9%, 34.6% 94.5%, 30.8% 94.0%, 26.9% 93.6%, 23.1% 92.8%, 19.2% 92.3%, 15.4% 91.5%, 11.5% 90.6%, 7.7% 88.9%)'],
                            '12' => ['left' => '43.54%', 'top' => '7.36%', 'width' => '3.09%', 'height' => '31.46%', 'clip' => 'polygon(9.1% 76.6%, 13.6% 69.8%, 18.2% 63.0%, 22.7% 50.6%, 27.3% 42.6%, 31.8% 39.6%, 36.4% 37.4%, 40.9% 37.0%, 45.5% 37.0%, 50.0% 37.4%, 54.5% 40.4%, 59.1% 44.3%, 63.6% 48.1%, 68.2% 53.2%, 72.7% 58.3%, 77.3% 64.7%, 81.8% 72.8%, 86.4% 79.1%, 86.4% 94.5%, 81.8% 94.9%, 77.3% 94.9%, 72.7% 94.9%, 68.2% 94.9%, 63.6% 94.9%, 59.1% 94.9%, 54.5% 94.9%, 50.0% 94.9%, 45.5% 94.9%, 40.9% 94.9%, 36.4% 94.9%, 31.8% 94.9%, 27.3% 94.9%, 22.7% 94.9%, 18.2% 94.9%, 13.6% 94.5%, 9.1% 94.5%)'],
                            '11' => ['left' => '47.33%', 'top' => '7.36%', 'width' => '3.79%', 'height' => '31.46%', 'clip' => 'polygon(7.4% 81.7%, 11.1% 75.7%, 14.8% 70.6%, 18.5% 64.3%, 22.2% 56.6%, 25.9% 45.5%, 29.6% 37.4%, 33.3% 33.6%, 37.0% 32.8%, 40.7% 32.3%, 44.4% 32.8%, 48.1% 33.6%, 51.9% 34.9%, 55.6% 36.6%, 59.3% 39.6%, 63.0% 43.4%, 66.7% 48.5%, 70.4% 54.0%, 74.1% 60.0%, 77.8% 66.8%, 81.5% 72.3%, 85.2% 76.6%, 88.9% 82.6%, 88.9% 92.3%, 85.2% 94.9%, 81.5% 95.3%, 77.8% 95.7%, 74.1% 95.7%, 70.4% 95.7%, 66.7% 95.7%, 63.0% 95.7%, 59.3% 95.7%, 55.6% 95.3%, 51.9% 95.3%, 48.1% 95.3%, 44.4% 95.3%, 40.7% 95.3%, 37.0% 95.3%, 33.3% 94.9%, 29.6% 94.9%, 25.9% 94.9%, 22.2% 94.9%, 18.5% 94.9%, 14.8% 94.5%, 11.1% 94.5%, 7.4% 93.6%)'],
                            '21' => ['left' => '51.69%', 'top' => '7.36%', 'width' => '3.79%', 'height' => '31.46%', 'clip' => 'polygon(7.4% 80.0%, 11.1% 74.9%, 14.8% 70.2%, 18.5% 65.1%, 22.2% 58.7%, 25.9% 51.9%, 29.6% 46.4%, 33.3% 41.7%, 37.0% 38.3%, 40.7% 36.2%, 44.4% 34.0%, 48.1% 32.8%, 51.9% 32.3%, 55.6% 32.3%, 59.3% 32.8%, 63.0% 34.9%, 66.7% 42.6%, 70.4% 50.6%, 74.1% 57.4%, 77.8% 65.1%, 81.5% 70.6%, 85.2% 75.7%, 88.9% 83.0%, 88.9% 90.6%, 85.2% 94.0%, 81.5% 94.5%, 77.8% 94.9%, 74.1% 94.9%, 70.4% 94.9%, 66.7% 94.9%, 63.0% 95.3%, 59.3% 95.3%, 55.6% 95.3%, 51.9% 95.3%, 48.1% 95.3%, 44.4% 95.3%, 40.7% 95.3%, 37.0% 95.3%, 33.3% 95.3%, 29.6% 95.3%, 25.9% 95.3%, 22.2% 95.3%, 18.5% 95.3%, 14.8% 95.3%, 11.1% 94.9%, 7.4% 94.5%)'],
                            '22' => ['left' => '56.04%', 'top' => '7.36%', 'width' => '3.23%', 'height' => '31.46%', 'clip' => 'polygon(8.7% 83.0%, 13.0% 76.2%, 17.4% 68.9%, 21.7% 62.1%, 26.1% 55.7%, 30.4% 50.6%, 34.8% 46.4%, 39.1% 42.6%, 43.5% 39.6%, 47.8% 37.9%, 52.2% 37.0%, 56.5% 36.6%, 60.9% 37.4%, 65.2% 38.7%, 69.6% 43.8%, 73.9% 56.6%, 78.3% 64.3%, 82.6% 71.9%, 87.0% 78.3%, 87.0% 94.0%, 82.6% 94.5%, 78.3% 94.5%, 73.9% 94.9%, 69.6% 94.9%, 65.2% 94.9%, 60.9% 94.9%, 56.5% 94.9%, 52.2% 94.9%, 47.8% 94.9%, 43.5% 94.9%, 39.1% 94.9%, 34.8% 94.9%, 30.4% 94.9%, 26.1% 94.9%, 21.7% 94.9%, 17.4% 94.9%, 13.0% 94.5%, 8.7% 94.0%)'],
                            '23' => ['left' => '60.11%', 'top' => '7.36%', 'width' => '3.51%', 'height' => '31.46%', 'clip' => 'polygon(8.0% 81.3%, 12.0% 75.7%, 16.0% 68.9%, 20.0% 60.9%, 24.0% 54.9%, 28.0% 49.4%, 32.0% 44.3%, 36.0% 40.0%, 40.0% 36.2%, 44.0% 33.6%, 48.0% 31.9%, 52.0% 31.5%, 56.0% 31.1%, 60.0% 31.9%, 64.0% 34.0%, 68.0% 42.6%, 72.0% 53.2%, 76.0% 60.4%, 80.0% 67.7%, 84.0% 75.7%, 88.0% 80.4%, 88.0% 90.2%, 84.0% 91.5%, 80.0% 92.3%, 76.0% 92.8%, 72.0% 93.2%, 68.0% 94.0%, 64.0% 94.5%, 60.0% 95.3%, 56.0% 95.7%, 52.0% 96.2%, 48.0% 96.2%, 44.0% 96.2%, 40.0% 95.7%, 36.0% 95.3%, 32.0% 94.9%, 28.0% 94.5%, 24.0% 93.6%, 20.0% 93.2%, 16.0% 92.3%, 12.0% 91.9%, 8.0% 91.1%)'],
                            '24' => ['left' => '64.47%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 82.1%, 11.5% 78.3%, 15.4% 73.2%, 19.2% 67.7%, 23.1% 61.7%, 26.9% 56.2%, 30.8% 52.3%, 34.6% 47.7%, 38.5% 43.8%, 42.3% 40.9%, 46.2% 39.1%, 50.0% 38.3%, 53.8% 37.9%, 57.7% 38.3%, 61.5% 39.1%, 65.4% 41.7%, 69.2% 54.5%, 73.1% 63.0%, 76.9% 70.2%, 80.8% 76.2%, 84.6% 79.1%, 88.5% 81.7%, 88.5% 89.8%, 84.6% 91.5%, 80.8% 92.3%, 76.9% 92.8%, 73.1% 93.6%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 95.3%, 53.8% 95.7%, 50.0% 96.2%, 46.2% 96.2%, 42.3% 96.2%, 38.5% 95.7%, 34.6% 95.3%, 30.8% 94.9%, 26.9% 94.5%, 23.1% 94.0%, 19.2% 93.2%, 15.4% 92.8%, 11.5% 92.3%, 7.7% 91.1%)'],
                            '25' => ['left' => '69.10%', 'top' => '7.36%', 'width' => '3.65%', 'height' => '31.46%', 'clip' => 'polygon(7.7% 83.4%, 11.5% 79.6%, 15.4% 75.7%, 19.2% 70.2%, 23.1% 64.3%, 26.9% 57.9%, 30.8% 49.4%, 34.6% 45.5%, 38.5% 43.8%, 42.3% 43.0%, 46.2% 42.1%, 50.0% 42.6%, 53.8% 43.8%, 57.7% 46.4%, 61.5% 51.1%, 65.4% 56.2%, 69.2% 61.3%, 73.1% 66.0%, 76.9% 72.3%, 80.8% 77.4%, 84.6% 80.4%, 88.5% 83.8%, 88.5% 89.4%, 84.6% 91.5%, 80.8% 92.8%, 76.9% 93.2%, 73.1% 93.6%, 69.2% 94.0%, 65.4% 94.5%, 61.5% 94.9%, 57.7% 94.9%, 53.8% 95.3%, 50.0% 95.3%, 46.2% 95.3%, 42.3% 94.9%, 38.5% 94.9%, 34.6% 94.5%, 30.8% 94.5%, 26.9% 94.0%, 23.1% 94.0%, 19.2% 94.0%, 15.4% 94.0%, 11.5% 93.6%, 7.7% 92.8%)'],
                            '26' => ['left' => '74.02%', 'top' => '7.36%', 'width' => '5.34%', 'height' => '31.46%', 'clip' => 'polygon(5.3% 83.0%, 10.5% 57.9%, 15.8% 51.5%, 21.1% 50.2%, 26.3% 51.1%, 31.6% 55.3%, 36.8% 60.0%, 42.1% 63.4%, 47.4% 60.9%, 52.6% 52.3%, 57.9% 51.1%, 63.2% 51.5%, 68.4% 51.1%, 73.7% 49.8%, 78.9% 50.6%, 84.2% 53.2%, 89.5% 61.7%, 89.5% 92.3%, 84.2% 94.0%, 78.9% 94.9%, 73.7% 94.9%, 68.4% 94.5%, 63.2% 94.5%, 57.9% 94.0%, 52.6% 93.2%, 47.4% 93.2%, 42.1% 93.6%, 36.8% 94.5%, 31.6% 94.9%, 26.3% 95.3%, 21.1% 95.3%, 15.8% 94.5%, 10.5% 93.2%, 5.3% 90.2%)'],
                            '27' => ['left' => '80.90%', 'top' => '7.36%', 'width' => '5.34%', 'height' => '31.46%', 'clip' => 'polygon(5.3% 84.3%, 10.5% 74.0%, 15.8% 59.6%, 21.1% 54.5%, 26.3% 52.3%, 31.6% 52.3%, 36.8% 56.2%, 42.1% 57.4%, 47.4% 52.3%, 52.6% 52.8%, 57.9% 51.9%, 63.2% 52.3%, 68.4% 53.6%, 73.7% 56.2%, 78.9% 59.1%, 84.2% 65.5%, 89.5% 78.3%, 89.5% 91.5%, 84.2% 93.6%, 78.9% 94.5%, 73.7% 94.9%, 68.4% 94.5%, 63.2% 94.0%, 57.9% 93.6%, 52.6% 93.2%, 47.4% 93.6%, 42.1% 94.0%, 36.8% 94.5%, 31.6% 94.9%, 26.3% 94.9%, 21.1% 94.9%, 15.8% 94.0%, 10.5% 93.2%, 5.3% 89.8%)'],
                            '28' => ['left' => '87.78%', 'top' => '7.36%', 'width' => '5.20%', 'height' => '31.46%', 'clip' => 'polygon(5.4% 82.1%, 10.8% 66.0%, 16.2% 58.7%, 21.6% 55.3%, 27.0% 54.9%, 32.4% 57.0%, 37.8% 58.7%, 43.2% 55.7%, 48.6% 57.0%, 54.1% 60.4%, 59.5% 62.6%, 64.9% 55.3%, 70.3% 55.3%, 75.7% 57.0%, 81.1% 60.4%, 86.5% 74.9%, 91.9% 82.6%, 91.9% 88.9%, 86.5% 92.3%, 81.1% 93.6%, 75.7% 94.5%, 70.3% 94.5%, 64.9% 94.5%, 59.5% 94.0%, 54.1% 93.6%, 48.6% 93.2%, 43.2% 93.6%, 37.8% 94.0%, 32.4% 94.5%, 27.0% 94.9%, 21.6% 94.9%, 16.2% 94.5%, 10.8% 93.6%, 5.4% 91.5%)'],
                            '48' => ['left' => '9.69%', 'top' => '47.5%', 'width' => '5.62%', 'height' => '18.5%', 'clip' => 'polygon(15% 7%, 25% 7%, 30% 28%, 42% 28%, 48% 7%, 52% 7%, 58% 28%, 70% 28%, 75% 7%, 85% 7%, 92% 28%, 96% 48%, 96% 92%, 92% 97%, 8% 97%, 4% 92%, 4% 48%, 8% 28%)'],
                            '47' => ['left' => '16.43%', 'top' => '47.5%', 'width' => '5.48%', 'height' => '18.5%', 'clip' => 'polygon(15% 7%, 25% 7%, 30% 28%, 42% 28%, 48% 7%, 52% 7%, 58% 28%, 70% 28%, 75% 7%, 85% 7%, 92% 28%, 96% 48%, 96% 92%, 92% 97%, 8% 97%, 4% 92%, 4% 48%, 8% 28%)'],
                            '46' => ['left' => '23.17%', 'top' => '47.5%', 'width' => '5.62%', 'height' => '18.5%', 'clip' => 'polygon(15% 7%, 25% 7%, 30% 28%, 42% 28%, 48% 7%, 52% 7%, 58% 28%, 70% 28%, 75% 7%, 85% 7%, 92% 28%, 96% 48%, 96% 92%, 92% 97%, 8% 97%, 4% 92%, 4% 48%, 8% 28%)'],
                            '45' => ['left' => '29.92%', 'top' => '47.5%', 'width' => '3.93%', 'height' => '18.5%', 'clip' => 'polygon(28% 5%, 40% 5%, 48% 20%, 52% 20%, 60% 5%, 72% 5%, 82% 30%, 86% 55%, 86% 88%, 80% 95%, 20% 95%, 14% 88%, 14% 55%, 18% 30%)'],
                            '44' => ['left' => '34.97%', 'top' => '47.5%', 'width' => '3.65%', 'height' => '18.5%', 'clip' => 'polygon(28% 5%, 40% 5%, 48% 20%, 52% 20%, 60% 5%, 72% 5%, 82% 30%, 86% 55%, 86% 88%, 80% 95%, 20% 95%, 14% 88%, 14% 55%, 18% 30%)'],
                            '43' => ['left' => '39.61%', 'top' => '47.5%', 'width' => '3.51%', 'height' => '18.5%', 'clip' => 'polygon(38% 3%, 50% 3%, 62% 3%, 72% 22%, 78% 42%, 78% 80%, 72% 90%, 28% 90%, 22% 80%, 22% 42%, 28% 22%)'],
                            '42' => ['left' => '44.10%', 'top' => '47.5%', 'width' => '2.95%', 'height' => '18.5%', 'clip' => 'polygon(30% 5%, 45% 5%, 55% 5%, 70% 5%, 78% 25%, 82% 50%, 82% 82%, 76% 90%, 24% 90%, 18% 82%, 18% 50%, 22% 25%)'],
                            '41' => ['left' => '47.89%', 'top' => '47.5%', 'width' => '2.81%', 'height' => '18.5%', 'clip' => 'polygon(30% 5%, 45% 5%, 55% 5%, 70% 5%, 78% 25%, 80% 50%, 80% 82%, 74% 90%, 26% 90%, 20% 82%, 20% 50%, 22% 25%)'],
                            '31' => ['left' => '52.11%', 'top' => '47.5%', 'width' => '2.81%', 'height' => '18.5%', 'clip' => 'polygon(30% 5%, 45% 5%, 55% 5%, 70% 5%, 78% 25%, 80% 50%, 80% 82%, 74% 90%, 26% 90%, 20% 82%, 20% 50%, 22% 25%)'],
                            '32' => ['left' => '55.76%', 'top' => '47.5%', 'width' => '2.95%', 'height' => '18.5%', 'clip' => 'polygon(30% 5%, 45% 5%, 55% 5%, 70% 5%, 78% 25%, 82% 50%, 82% 82%, 76% 90%, 24% 90%, 18% 82%, 18% 50%, 22% 25%)'],
                            '33' => ['left' => '59.69%', 'top' => '47.5%', 'width' => '3.37%', 'height' => '18.5%', 'clip' => 'polygon(38% 3%, 50% 3%, 62% 3%, 72% 22%, 78% 42%, 78% 80%, 72% 90%, 28% 90%, 22% 80%, 22% 42%, 28% 22%)'],
                            '34' => ['left' => '64.04%', 'top' => '47.5%', 'width' => '3.79%', 'height' => '18.5%', 'clip' => 'polygon(28% 5%, 40% 5%, 48% 20%, 52% 20%, 60% 5%, 72% 5%, 82% 30%, 86% 55%, 86% 88%, 80% 95%, 20% 95%, 14% 88%, 14% 55%, 18% 30%)'],
                            '35' => ['left' => '68.82%', 'top' => '47.5%', 'width' => '3.93%', 'height' => '18.5%', 'clip' => 'polygon(28% 5%, 40% 5%, 48% 20%, 52% 20%, 60% 5%, 72% 5%, 82% 30%, 86% 55%, 86% 88%, 80% 95%, 20% 95%, 14% 88%, 14% 55%, 18% 30%)'],
                            '36' => ['left' => '74.02%', 'top' => '47.5%', 'width' => '5.48%', 'height' => '18.5%', 'clip' => 'polygon(15% 7%, 25% 7%, 30% 28%, 42% 28%, 48% 7%, 52% 7%, 58% 28%, 70% 28%, 75% 7%, 85% 7%, 92% 28%, 96% 48%, 96% 92%, 92% 97%, 8% 97%, 4% 92%, 4% 48%, 8% 28%)'],
                            '37' => ['left' => '80.76%', 'top' => '47.5%', 'width' => '5.48%', 'height' => '18.5%', 'clip' => 'polygon(15% 7%, 25% 7%, 30% 28%, 42% 28%, 48% 7%, 52% 7%, 58% 28%, 70% 28%, 75% 7%, 85% 7%, 92% 28%, 96% 48%, 96% 92%, 92% 97%, 8% 97%, 4% 92%, 4% 48%, 8% 28%)'],
                            '38' => ['left' => '87.50%', 'top' => '47.5%', 'width' => '5.62%', 'height' => '18.5%', 'clip' => 'polygon(15% 7%, 25% 7%, 30% 28%, 42% 28%, 48% 7%, 52% 7%, 58% 28%, 70% 28%, 75% 7%, 85% 7%, 92% 28%, 96% 48%, 96% 92%, 92% 97%, 8% 97%, 4% 92%, 4% 48%, 8% 28%)'],
                        ];
                    @endphp

                    <div class="relative w-full" style="max-width: 550px; margin: 0 auto;">
                        <img src="{{ asset('images/dental-chart.png') }}?v=6" alt="Dental Chart" class="w-full h-auto select-none pointer-events-none" draggable="false">

                        @foreach($toothHotspots as $tooth => $pos)
                            @php
                                $diagConditions = $toothChartData[$tooth]['conditions'] ?? [];
                                $hasDiag = count($diagConditions) > 0;
                                $isTreated = in_array($tooth, $treatedTeeth);
                                $isMissing = in_array('MISSING', $diagConditions);
                                $clipPath = $pos['clip'] ?? '';
                                $activeItem = $activeItemId ? $items->firstWhere('id', $activeItemId) : null;
                                $activeTeeth = $activeItem ? ($activeItem->tooth_positions ?? []) : [];
                                $isActiveAssigned = in_array($tooth, $activeTeeth);
                            @endphp
                            <button
                                wire:click="toggleToothForItem('{{ $tooth }}')"
                                class="absolute transition-all duration-150 group {{ $activeItemId ? 'cursor-pointer' : 'cursor-default' }}"
                                style="left: {{ $pos['left'] }}; top: {{ $pos['top'] }}; width: {{ $pos['width'] }}; height: {{ $pos['height'] }}; clip-path: {{ $clipPath }};"
                            >
                                @if($isMissing)
                                    <div class="absolute inset-0 bg-white/70"></div>
                                @elseif($isActiveAssigned)
                                    <div class="absolute inset-0 bg-clinical/40"></div>
                                @elseif($isTreated)
                                    <div class="absolute inset-0 bg-green-500/25"></div>
                                @elseif($hasDiag)
                                    <div class="absolute inset-0 bg-amber-400/20"></div>
                                @endif
                                @if($activeItemId && !$isActiveAssigned && !$isMissing)
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition bg-clinical/15"></div>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-3 mt-3 pt-3 border-t border-gray-100">
                        @if($activeItemId)
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-500">
                                <span class="w-2.5 h-2.5 rounded-full bg-clinical"></span> Assigned to selected
                            </div>
                        @endif
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Treatment assigned
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Diagnosed
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============================================================
                 VISITS — Drag treatments into visit groups
            ============================================================ --}}
            @if($showVisits && $items->isNotEmpty())
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Schedule Visits</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Drag treatments into visits to schedule when each procedure will be done.</p>
                        </div>
                        <div class="flex items-center gap-1">
                            @for($v = 1; $v <= 5; $v++)
                                <button
                                    wire:click="setVisitCount({{ $v }})"
                                    class="w-7 h-7 rounded-lg text-xs font-bold transition
                                        {{ $visitCount === $v ? 'bg-clinical text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                >{{ $v }}</button>
                            @endfor
                            <span class="text-[10px] text-gray-400 ml-1">visits</span>
                        </div>
                    </div>

                    {{-- Unassigned items --}}
                    @if($unassignedItems->isNotEmpty())
                        <div class="mb-4 p-3 bg-amber-50/50 border border-amber-100 rounded-xl">
                            <p class="text-xs font-semibold text-amber-700 mb-2">Unassigned treatments</p>
                            <div class="space-y-1">
                                @foreach($unassignedItems as $item)
                                    <div
                                        draggable="true"
                                        x-on:dragstart="dragItemId = {{ $item->id }}"
                                        x-on:dragend="dragItemId = null"
                                        class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 border border-amber-100 cursor-grab active:cursor-grabbing text-sm"
                                    >
                                        <svg class="h-3.5 w-3.5 text-gray-300 flex-none" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 6a2 2 0 100-4 2 2 0 000 4zM8 14a2 2 0 100-4 2 2 0 000 4zM16 6a2 2 0 100-4 2 2 0 000 4zM16 14a2 2 0 100-4 2 2 0 000 4z"/>
                                        </svg>
                                        <span class="text-gray-700 truncate flex-1">{{ $item->name ?: 'Untitled' }}</span>
                                        @if(!empty($item->tooth_positions))
                                            <span class="text-[10px] text-clinical bg-clinical/10 rounded px-1">{{ count($item->tooth_positions) }} teeth</span>
                                        @endif
                                        <span class="text-xs text-gray-400 font-mono">{{ $plan->currency ?? '£' }}{{ number_format($item->line_total, 0) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Visit columns --}}
                    <div class="grid grid-cols-1 md:grid-cols-{{ min($visitCount, 3) }} gap-3">
                        @foreach($phases as $phase)
                            @php
                                $visitItems = $items->filter(fn($i) => $i->procedure_phase === (string) $phase->phase_number);
                                $visitTotal = $visitItems->sum('line_total');
                            @endphp
                            <div
                                class="border-2 border-dashed rounded-xl p-3 transition min-h-[120px]"
                                :class="dragItemId ? 'border-clinical/40 bg-clinical/5' : 'border-gray-200'"
                                x-on:dragover.prevent
                                x-on:drop.prevent="
                                    if (dragItemId) {
                                        $wire.assignItemToVisit(dragItemId, {{ $phase->phase_number }});
                                        flash('Assigned to {{ $phase->name }}');
                                        dragItemId = null;
                                    }
                                "
                            >
                                <div class="flex items-center justify-between mb-2">
                                    <input
                                        type="text"
                                        value="{{ $phase->name }}"
                                        wire:change="updateVisitName({{ $phase->id }}, $event.target.value)"
                                        class="text-xs font-bold text-gray-700 uppercase tracking-wide border-0 bg-transparent p-0 focus:ring-0 w-24"
                                    />
                                    <span class="text-[10px] font-mono text-gray-400">{{ $plan->currency ?? '£' }}{{ number_format($visitTotal, 0) }}</span>
                                </div>

                                @if($visitItems->isEmpty())
                                    <div class="flex items-center justify-center h-16 text-xs text-gray-300">
                                        Drop treatments here
                                    </div>
                                @else
                                    <div class="space-y-1">
                                        @foreach($visitItems as $vi)
                                            <div class="flex items-center gap-1.5 bg-gray-50 rounded-lg px-2.5 py-1.5 text-xs group">
                                                <span class="w-1.5 h-1.5 rounded-full bg-clinical flex-none"></span>
                                                <span class="text-gray-700 truncate flex-1">{{ $vi->name ?: 'Untitled' }}</span>
                                                <button
                                                    wire:click="removeItemFromVisit({{ $vi->id }})"
                                                    class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition flex-none"
                                                >
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
