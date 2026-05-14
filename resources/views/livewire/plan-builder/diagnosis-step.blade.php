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

                @php
                    $toothHotspots = [
                        '18' => ['left' => '3.93%', 'top' => '9.92%', 'width' => '5.71%', 'height' => '39.67%'],
                        '17' => ['left' => '10.0%', 'top' => '7.44%', 'width' => '5.36%', 'height' => '41.32%'],
                        '16' => ['left' => '16.43%', 'top' => '4.96%', 'width' => '6.43%', 'height' => '43.8%'],
                        '15' => ['left' => '23.57%', 'top' => '9.09%', 'width' => '4.64%', 'height' => '39.67%'],
                        '14' => ['left' => '28.21%', 'top' => '6.61%', 'width' => '4.29%', 'height' => '41.32%'],
                        '13' => ['left' => '32.86%', 'top' => '4.13%', 'width' => '3.57%', 'height' => '44.63%'],
                        '12' => ['left' => '36.43%', 'top' => '6.61%', 'width' => '3.43%', 'height' => '42.15%'],
                        '11' => ['left' => '39.86%', 'top' => '4.96%', 'width' => '4.14%', 'height' => '43.8%'],
                        '21' => ['left' => '44.29%', 'top' => '4.96%', 'width' => '4.14%', 'height' => '43.8%'],
                        '22' => ['left' => '48.57%', 'top' => '6.61%', 'width' => '3.57%', 'height' => '42.15%'],
                        '23' => ['left' => '52.29%', 'top' => '4.13%', 'width' => '3.93%', 'height' => '44.63%'],
                        '24' => ['left' => '56.43%', 'top' => '6.61%', 'width' => '5.0%', 'height' => '41.32%'],
                        '25' => ['left' => '61.79%', 'top' => '9.09%', 'width' => '4.64%', 'height' => '39.67%'],
                        '26' => ['left' => '67.14%', 'top' => '4.96%', 'width' => '7.14%', 'height' => '43.8%'],
                        '27' => ['left' => '75.0%', 'top' => '7.44%', 'width' => '7.14%', 'height' => '41.32%'],
                        '28' => ['left' => '82.86%', 'top' => '9.09%', 'width' => '8.93%', 'height' => '40.5%'],
                        '48' => ['left' => '3.93%', 'top' => '57.02%', 'width' => '6.07%', 'height' => '39.67%'],
                        '47' => ['left' => '10.36%', 'top' => '56.2%', 'width' => '5.71%', 'height' => '39.67%'],
                        '46' => ['left' => '16.43%', 'top' => '56.2%', 'width' => '6.07%', 'height' => '40.5%'],
                        '45' => ['left' => '23.21%', 'top' => '57.02%', 'width' => '4.64%', 'height' => '38.84%'],
                        '44' => ['left' => '28.0%', 'top' => '57.02%', 'width' => '3.93%', 'height' => '38.84%'],
                        '43' => ['left' => '32.0%', 'top' => '56.2%', 'width' => '3.71%', 'height' => '40.5%'],
                        '42' => ['left' => '35.86%', 'top' => '57.02%', 'width' => '3.0%', 'height' => '38.84%'],
                        '41' => ['left' => '38.93%', 'top' => '57.02%', 'width' => '3.21%', 'height' => '38.84%'],
                        '31' => ['left' => '42.29%', 'top' => '57.02%', 'width' => '3.21%', 'height' => '38.84%'],
                        '32' => ['left' => '45.71%', 'top' => '57.02%', 'width' => '3.43%', 'height' => '38.84%'],
                        '33' => ['left' => '49.29%', 'top' => '56.2%', 'width' => '3.93%', 'height' => '40.5%'],
                        '34' => ['left' => '53.43%', 'top' => '57.02%', 'width' => '5.0%', 'height' => '38.84%'],
                        '35' => ['left' => '58.71%', 'top' => '57.02%', 'width' => '5.14%', 'height' => '38.84%'],
                        '36' => ['left' => '64.29%', 'top' => '56.2%', 'width' => '7.5%', 'height' => '40.5%'],
                        '37' => ['left' => '72.14%', 'top' => '56.2%', 'width' => '7.5%', 'height' => '39.67%'],
                        '38' => ['left' => '80.0%', 'top' => '57.02%', 'width' => '8.93%', 'height' => '38.84%'],
                    ];
                @endphp

                <div class="relative w-full" style="max-width: 700px; margin: 0 auto;">
                    <img src="{{ asset('images/dental-chart.png') }}" alt="Dental Chart" class="w-full h-auto select-none pointer-events-none" draggable="false">

                    @foreach($toothHotspots as $tooth => $pos)
                        @php
                            $conditions = $toothChartData[$tooth]['conditions'] ?? [];
                            $hasConditions = count($conditions) > 0;
                            $isSelected = in_array($tooth, $selectedTeeth);
                            $isMissing = in_array('MISSING', $conditions);
                            $condColour = null;
                            if ($hasConditions && !$isMissing) {
                                foreach ($availableConditions as $ac) {
                                    if ($ac['code'] === $conditions[0]) { $condColour = $ac['colour']; break; }
                                }
                            }
                        @endphp
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
                            class="absolute rounded-md transition-all duration-150 group"
                            style="left: {{ $pos['left'] }}; top: {{ $pos['top'] }}; width: {{ $pos['width'] }}; height: {{ $pos['height'] }};"
                        >
                            @if($isMissing)
                                <div class="absolute inset-0 bg-white/70 rounded-md flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                            @elseif($hasConditions && $condColour)
                                <div class="absolute inset-0 rounded-md opacity-30" style="background-color: {{ $condColour }};"></div>
                            @endif

                            @if($isSelected)
                                <div class="absolute inset-0 rounded-md ring-2 ring-indigo-500 ring-offset-1 bg-indigo-500/10"></div>
                            @else
                                <div class="absolute inset-0 rounded-md opacity-0 group-hover:opacity-100 bg-clinical/5 ring-1 ring-clinical/20 transition"></div>
                            @endif
                        </button>
                    @endforeach
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

            {{-- Diagnosis Summary Table (BrightPlans style) --}}
            @php
                $teethWithConditions = collect($toothChartData)->filter(fn($d) => count($d['conditions'] ?? []) > 0);
            @endphp
            @if($teethWithConditions->count() > 0)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    {{-- Upper Jaw --}}
                    @php
                        $upperTeethWithCond = $teethWithConditions->filter(fn($d, $k) => intval($k) >= 11 && intval($k) <= 28)->sortKeys();
                        $lowerTeethWithCond = $teethWithConditions->filter(fn($d, $k) => intval($k) >= 31 && intval($k) <= 48)->sortKeys();
                    @endphp
                    @if($upperTeethWithCond->count() > 0)
                        <div class="px-5 pt-4 pb-1">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b border-gray-200 pb-2 mb-2">Diagnosis — Upper Jaw</h4>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-1">
                                @foreach($upperTeethWithCond as $tNum => $tData)
                                    <div class="flex items-center gap-3 py-1.5 border-b border-gray-50 text-sm">
                                        <span class="text-gray-400 font-mono text-xs w-6">{{ $tNum }}.</span>
                                        <span class="text-gray-700">
                                            @foreach($tData['conditions'] as $c)
                                                @php $cl = $c; foreach($availableConditions as $ac) { if($ac['code']===$c){$cl=$ac['label'];break;} } @endphp
                                                {{ $cl }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($lowerTeethWithCond->count() > 0)
                        <div class="px-5 pt-3 pb-4">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b border-gray-200 pb-2 mb-2">Diagnosis — Lower Jaw</h4>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-1">
                                @foreach($lowerTeethWithCond as $tNum => $tData)
                                    <div class="flex items-center gap-3 py-1.5 border-b border-gray-50 text-sm">
                                        <span class="text-gray-400 font-mono text-xs w-6">{{ $tNum }}.</span>
                                        <span class="text-gray-700">
                                            @foreach($tData['conditions'] as $c)
                                                @php $cl = $c; foreach($availableConditions as $ac) { if($ac['code']===$c){$cl=$ac['label'];break;} } @endphp
                                                {{ $cl }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endforeach
                            </div>
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
