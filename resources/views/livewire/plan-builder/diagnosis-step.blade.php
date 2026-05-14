<div x-data="{
    dragging: null,
    showToast: false,
    toastMsg: '',
    flash(msg) { this.toastMsg = msg; this.showToast = true; setTimeout(() => this.showToast = false, 2000); }
}" class="max-w-6xl mx-auto">

    {{-- Toast --}}
    <div x-show="showToast" x-transition.opacity class="fixed top-4 right-4 z-50 bg-gray-900 text-white text-sm px-4 py-2.5 rounded-lg shadow-lg" x-cloak x-text="toastMsg"></div>

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Diagnosis</h2>
        <p class="text-gray-500 text-sm mt-1">Select teeth, then drag conditions onto them — or click a condition to paint it across selected teeth.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ============================================================
             LEFT: Interactive Tooth Chart (2 cols)
        ============================================================ --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Quick select bar --}}
            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-400 font-medium">Quick select:</span>
                <button wire:click="selectAllUpper" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">Upper jaw</button>
                <button wire:click="selectAllLower" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">Lower jaw</button>
                <button wire:click="selectAll" class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-clinical/10 hover:text-clinical text-gray-600 transition font-medium">All teeth</button>
                @if(count($selectedTeeth) > 0)
                    <button wire:click="clearSelectedTeeth" class="px-2.5 py-1 rounded-md bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">Clear ({{ count($selectedTeeth) }})</button>
                @endif
            </div>

            {{-- Tooth Chart Card --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm"
                 x-on:dragover.prevent
                 x-on:drop.prevent="
                    if (dragging) {
                        $wire.applyConditionToTeeth(dragging);
                        flash('Applied to ' + {{ count($selectedTeeth) }} + ' teeth');
                        dragging = null;
                    }
                 ">

                {{-- Upper jaw --}}
                <div class="text-center mb-1">
                    <span class="text-[10px] uppercase tracking-widest text-gray-300 font-bold">Upper Jaw</span>
                </div>

                @php
                    $upperRight = ['18','17','16','15','14','13','12','11'];
                    $upperLeft  = ['21','22','23','24','25','26','27','28'];
                    $lowerRight = ['48','47','46','45','44','43','42','41'];
                    $lowerLeft  = ['31','32','33','34','35','36','37','38'];

                    // Tooth type mapping for SVG shapes
                    $toothTypes = [
                        '11' => 'incisor', '12' => 'incisor', '13' => 'canine', '14' => 'premolar', '15' => 'premolar', '16' => 'molar', '17' => 'molar', '18' => 'molar',
                        '21' => 'incisor', '22' => 'incisor', '23' => 'canine', '24' => 'premolar', '25' => 'premolar', '26' => 'molar', '27' => 'molar', '28' => 'molar',
                        '31' => 'incisor', '32' => 'incisor', '33' => 'canine', '34' => 'premolar', '35' => 'premolar', '36' => 'molar', '37' => 'molar', '38' => 'molar',
                        '41' => 'incisor', '42' => 'incisor', '43' => 'canine', '44' => 'premolar', '45' => 'premolar', '46' => 'molar', '47' => 'molar', '48' => 'molar',
                    ];
                @endphp

                {{-- Upper teeth row --}}
                <div class="flex justify-center gap-0.5 mb-1">
                    @foreach(array_merge($upperRight, $upperLeft) as $i => $tooth)
                        @php
                            $conditions = $toothChartData[$tooth]['conditions'] ?? [];
                            $hasConditions = count($conditions) > 0;
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $type = $toothTypes[$tooth] ?? 'incisor';
                            $firstConditionColour = null;
                            if ($hasConditions) {
                                $condCode = $conditions[0];
                                foreach ($availableConditions as $ac) {
                                    if ($ac['code'] === $condCode) { $firstConditionColour = $ac['colour']; break; }
                                }
                            }
                        @endphp
                        <div class="flex flex-col items-center group {{ $i === 7 ? 'mr-3' : '' }}">
                            <button
                                wire:click="toggleTooth('{{ $tooth }}')"
                                x-on:dragover.prevent
                                x-on:drop.prevent.stop="
                                    if (dragging) {
                                        $wire.toggleTooth('{{ $tooth }}');
                                        $wire.applyConditionToTeeth(dragging);
                                        flash('Applied to tooth {{ $tooth }}');
                                        dragging = null;
                                    }
                                "
                                class="relative w-10 h-14 flex items-end justify-center transition-all duration-150 rounded-t-[14px] {{ $isSelected ? 'scale-110 z-10' : 'hover:scale-105' }}"
                            >
                                {{-- Tooth SVG --}}
                                <svg viewBox="0 0 40 56" class="w-full h-full drop-shadow-sm">
                                    @if($type === 'molar')
                                        <path d="M6 8 C6 3, 14 0, 20 0 C26 0, 34 3, 34 8 L36 16 C37 22, 36 28, 34 34 L32 44 C30 50, 26 54, 20 56 C14 54, 10 50, 8 44 L6 34 C4 28, 3 22, 4 16 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                        <path d="M12 14 Q20 10 28 14" class="fill-none {{ $isSelected ? 'stroke-clinical/40' : 'stroke-gray-200' }}" stroke-width="1" />
                                        <path d="M14 20 Q20 17 26 20" class="fill-none {{ $isSelected ? 'stroke-clinical/30' : 'stroke-gray-100' }}" stroke-width="0.8" />
                                    @elseif($type === 'premolar')
                                        <path d="M8 6 C8 2, 14 0, 20 0 C26 0, 32 2, 32 6 L33 16 C34 24, 32 32, 30 40 L28 48 C26 53, 22 56, 20 56 C18 56, 14 53, 12 48 L10 40 C8 32, 6 24, 7 16 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                        <path d="M14 12 Q20 9 26 12" class="fill-none {{ $isSelected ? 'stroke-clinical/40' : 'stroke-gray-200' }}" stroke-width="1" />
                                    @elseif($type === 'canine')
                                        <path d="M10 4 C10 1, 15 0, 20 0 C25 0, 30 1, 30 4 L31 14 C32 22, 30 34, 26 44 L24 50 C22 54, 20 56, 20 56 C20 56, 18 54, 16 50 L14 44 C10 34, 8 22, 9 14 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                    @else
                                        <path d="M11 4 C11 1, 15 0, 20 0 C25 0, 29 1, 29 4 L30 14 C31 24, 28 36, 26 44 L24 50 C22 54, 20 56, 20 56 C20 56, 18 54, 16 50 L14 44 C12 36, 9 24, 10 14 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                    @endif
                                </svg>

                                {{-- Condition dots --}}
                                @if($hasConditions)
                                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1 flex gap-0.5">
                                        @foreach(array_slice($conditions, 0, 3) as $cond)
                                            @php
                                                $dotColour = '#94a3b8';
                                                foreach ($availableConditions as $ac) {
                                                    if ($ac['code'] === $cond) { $dotColour = $ac['colour']; break; }
                                                }
                                            @endphp
                                            <span class="w-2 h-2 rounded-full ring-1 ring-white" style="background-color: {{ $dotColour }}"></span>
                                        @endforeach
                                        @if(count($conditions) > 3)
                                            <span class="w-2 h-2 rounded-full bg-gray-400 ring-1 ring-white text-[6px] text-white flex items-center justify-center">+</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Selection ring --}}
                                @if($isSelected)
                                    <div class="absolute -inset-0.5 rounded-t-[16px] border-2 border-clinical/60 pointer-events-none animate-pulse"></div>
                                @endif
                            </button>
                            <span class="text-[9px] font-bold mt-0.5 {{ $isSelected ? 'text-clinical' : ($hasConditions ? 'text-gray-700' : 'text-gray-400') }}">{{ $tooth }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Gum line --}}
                <div class="relative my-3">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t-2 border-dashed border-pink-200/60"></div></div>
                    <div class="relative flex justify-center"><span class="bg-white px-3 text-[9px] uppercase tracking-widest text-pink-300 font-bold">Gum Line</span></div>
                </div>

                {{-- Lower teeth row --}}
                <div class="flex justify-center gap-0.5 mt-1">
                    @foreach(array_merge($lowerRight, $lowerLeft) as $i => $tooth)
                        @php
                            $conditions = $toothChartData[$tooth]['conditions'] ?? [];
                            $hasConditions = count($conditions) > 0;
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $type = $toothTypes[$tooth] ?? 'incisor';
                        @endphp
                        <div class="flex flex-col items-center group {{ $i === 7 ? 'mr-3' : '' }}">
                            <span class="text-[9px] font-bold mb-0.5 {{ $isSelected ? 'text-clinical' : ($hasConditions ? 'text-gray-700' : 'text-gray-400') }}">{{ $tooth }}</span>
                            <button
                                wire:click="toggleTooth('{{ $tooth }}')"
                                x-on:dragover.prevent
                                x-on:drop.prevent.stop="
                                    if (dragging) {
                                        $wire.toggleTooth('{{ $tooth }}');
                                        $wire.applyConditionToTeeth(dragging);
                                        flash('Applied to tooth {{ $tooth }}');
                                        dragging = null;
                                    }
                                "
                                class="relative w-10 h-14 flex items-start justify-center transition-all duration-150 rounded-b-[14px] {{ $isSelected ? 'scale-110 z-10' : 'hover:scale-105' }}"
                            >
                                {{-- Tooth SVG (flipped for lower) --}}
                                <svg viewBox="0 0 40 56" class="w-full h-full drop-shadow-sm transform rotate-180">
                                    @if($type === 'molar')
                                        <path d="M6 8 C6 3, 14 0, 20 0 C26 0, 34 3, 34 8 L36 16 C37 22, 36 28, 34 34 L32 44 C30 50, 26 54, 20 56 C14 54, 10 50, 8 44 L6 34 C4 28, 3 22, 4 16 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                        <path d="M12 14 Q20 10 28 14" class="fill-none {{ $isSelected ? 'stroke-clinical/40' : 'stroke-gray-200' }}" stroke-width="1" />
                                        <path d="M14 20 Q20 17 26 20" class="fill-none {{ $isSelected ? 'stroke-clinical/30' : 'stroke-gray-100' }}" stroke-width="0.8" />
                                    @elseif($type === 'premolar')
                                        <path d="M8 6 C8 2, 14 0, 20 0 C26 0, 32 2, 32 6 L33 16 C34 24, 32 32, 30 40 L28 48 C26 53, 22 56, 20 56 C18 56, 14 53, 12 48 L10 40 C8 32, 6 24, 7 16 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                        <path d="M14 12 Q20 9 26 12" class="fill-none {{ $isSelected ? 'stroke-clinical/40' : 'stroke-gray-200' }}" stroke-width="1" />
                                    @elseif($type === 'canine')
                                        <path d="M10 4 C10 1, 15 0, 20 0 C25 0, 30 1, 30 4 L31 14 C32 22, 30 34, 26 44 L24 50 C22 54, 20 56, 20 56 C20 56, 18 54, 16 50 L14 44 C10 34, 8 22, 9 14 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                    @else
                                        <path d="M11 4 C11 1, 15 0, 20 0 C25 0, 29 1, 29 4 L30 14 C31 24, 28 36, 26 44 L24 50 C22 54, 20 56, 20 56 C20 56, 18 54, 16 50 L14 44 C12 36, 9 24, 10 14 Z"
                                              class="{{ $isSelected ? 'fill-clinical/20 stroke-clinical' : ($hasConditions ? 'fill-white stroke-gray-300' : 'fill-white stroke-gray-200') }}"
                                              stroke-width="{{ $isSelected ? 2 : 1.5 }}" />
                                    @endif
                                </svg>

                                {{-- Condition dots --}}
                                @if($hasConditions)
                                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1 flex gap-0.5">
                                        @foreach(array_slice($conditions, 0, 3) as $cond)
                                            @php
                                                $dotColour = '#94a3b8';
                                                foreach ($availableConditions as $ac) {
                                                    if ($ac['code'] === $cond) { $dotColour = $ac['colour']; break; }
                                                }
                                            @endphp
                                            <span class="w-2 h-2 rounded-full ring-1 ring-white" style="background-color: {{ $dotColour }}"></span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($isSelected)
                                    <div class="absolute -inset-0.5 rounded-b-[16px] border-2 border-clinical/60 pointer-events-none animate-pulse"></div>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Lower jaw label --}}
                <div class="text-center mt-1">
                    <span class="text-[10px] uppercase tracking-widest text-gray-300 font-bold">Lower Jaw</span>
                </div>
            </div>

            {{-- Selected teeth detail panel --}}
            @if(count($selectedTeeth) > 0)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                {{ count($selectedTeeth) === 1 ? 'Tooth ' . $selectedTeeth[0] : count($selectedTeeth) . ' teeth selected' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if(count($selectedTeeth) > 1)
                                    {{ implode(', ', array_slice($selectedTeeth, 0, 8)) }}{{ count($selectedTeeth) > 8 ? '...' : '' }}
                                @else
                                    Click conditions below to add, or drag them onto the chart
                                @endif
                            </p>
                        </div>
                        <button wire:click="clearSelectedTeeth" class="text-xs text-gray-400 hover:text-gray-600 transition">Clear selection</button>
                    </div>

                    {{-- Existing conditions on selected teeth --}}
                    @php
                        $selectedConditions = collect($selectedTeeth)
                            ->flatMap(fn ($t) => collect($toothChartData[$t]['conditions'] ?? [])
                                ->map(fn ($c) => ['tooth' => $t, 'code' => $c]))
                            ->all();
                    @endphp
                    @if(count($selectedConditions) > 0)
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach($selectedConditions as $sc)
                                @php
                                    $condLabel = $sc['code'];
                                    $condColour = '#94a3b8';
                                    foreach ($availableConditions as $ac) {
                                        if ($ac['code'] === $sc['code']) { $condLabel = $ac['label']; $condColour = $ac['colour']; break; }
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium pl-1.5 pr-1 py-0.5 rounded-full text-white" style="background-color: {{ $condColour }}">
                                    <span class="opacity-70">#{{ $sc['tooth'] }}</span> {{ $condLabel }}
                                    <button wire:click="removeConditionFromTooth('{{ $sc['tooth'] }}', '{{ $sc['code'] }}')" class="ml-0.5 hover:bg-white/20 rounded-full p-0.5 transition">
                                        <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Condition tags summary --}}
            @if(count($conditionTags) > 0)
                <div class="bg-gradient-to-r from-slate-50 to-white border border-gray-200 rounded-2xl p-4">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Conditions Found</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($conditionTags as $tag)
                            @php
                                $tagLabel = $tag;
                                $tagColour = '#94a3b8';
                                $tagCount = 0;
                                foreach ($availableConditions as $ac) {
                                    if ($ac['code'] === $tag) { $tagLabel = $ac['label']; $tagColour = $ac['colour']; break; }
                                }
                                foreach ($toothChartData as $td) {
                                    if (in_array($tag, $td['conditions'])) $tagCount++;
                                }
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full text-white" style="background-color: {{ $tagColour }}">
                                {{ $tagLabel }}
                                <span class="bg-white/20 text-[10px] px-1.5 rounded-full">{{ $tagCount }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ============================================================
             RIGHT: Conditions palette + notes (1 col)
        ============================================================ --}}
        <div class="space-y-4">

            {{-- Draggable conditions palette --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Conditions</h3>
                <p class="text-xs text-gray-400 mb-4">Drag onto teeth or click to paint on selected teeth</p>

                <div class="grid grid-cols-1 gap-1.5">
                    @foreach($availableConditions as $condition)
                        <button
                            draggable="true"
                            x-on:dragstart="dragging = '{{ $condition['code'] }}'"
                            x-on:dragend="dragging = null"
                            wire:click="selectCondition('{{ $condition['code'] }}')"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl border text-left text-xs font-medium transition-all cursor-grab active:cursor-grabbing
                                {{ $activeCondition === $condition['code']
                                    ? 'border-clinical bg-clinical/5 text-clinical ring-1 ring-clinical/20'
                                    : 'border-gray-100 text-gray-600 hover:border-gray-200 hover:bg-gray-50' }}"
                        >
                            <span class="w-3 h-3 rounded-full flex-none ring-1 ring-black/5" style="background-color: {{ $condition['colour'] }}"></span>
                            <span class="flex-1">{{ $condition['label'] }}</span>
                            @if($activeCondition === $condition['code'])
                                <svg class="w-3.5 h-3.5 text-clinical" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"/></svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Diagnosis notes --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <label class="block text-sm font-semibold text-gray-800 mb-1">Diagnosis Notes</label>
                <p class="text-xs text-gray-400 mb-3">Included in the patient's plan document.</p>
                <textarea
                    wire:model.blur="diagnosisText"
                    rows="6"
                    placeholder="Describe findings and recommended course of action..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
                ></textarea>
            </div>

            {{-- Save --}}
            <button
                wire:click="saveDiagnosis"
                x-on:click="flash('Diagnosis saved')"
                class="w-full bg-clinical hover:bg-clinical-700 text-white text-sm font-semibold rounded-xl px-4 py-3 transition flex items-center justify-center gap-2 shadow-sm"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Diagnosis
            </button>
        </div>
    </div>

</div>
