<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Diagnosis</h2>
        <p class="text-gray-500 text-sm mt-1">Document the patient's diagnosis using the tooth chart and conditions below.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Left: tooth chart + conditions --}}
        <div class="lg:col-span-3 space-y-5">

            {{-- Tooth chart --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Tooth chart</h3>

                {{-- Upper teeth (11-18, 21-28) --}}
                <div class="mb-2">
                    <p class="text-xs text-gray-400 text-center mb-2">Upper jaw</p>
                    <div class="flex gap-1 justify-center flex-wrap">
                        @php
                            $upperTeeth = ['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'];
                        @endphp
                        @foreach($upperTeeth as $tooth)
                            @php
                                $conditions = $toothChartData[$tooth]['conditions'] ?? [];
                                $hasConditions = count($conditions) > 0;
                                $isActive = $activeTooth === $tooth;
                            @endphp
                            <button
                                wire:click="selectTooth('{{ $tooth }}')"
                                title="Tooth {{ $tooth }}"
                                class="relative w-9 h-10 rounded-t-full border-2 text-xs font-bold flex flex-col items-center justify-end pb-1 transition
                                    {{ $isActive ? 'border-accent bg-accent/10 text-accent' : ($hasConditions ? 'border-clinical bg-clinical/10 text-clinical' : 'border-gray-200 bg-white text-gray-500 hover:border-clinical/40 hover:bg-clinical/5') }}"
                            >
                                @if($hasConditions)
                                    <span class="absolute top-0.5 right-0.5 w-2 h-2 rounded-full bg-accent"></span>
                                @endif
                                {{ $tooth }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-b border-dashed border-gray-200 py-1 my-2">
                    <p class="text-xs text-center text-gray-300">gum line</p>
                </div>

                {{-- Lower teeth (31-38, 41-48) --}}
                <div>
                    <div class="flex gap-1 justify-center flex-wrap">
                        @php
                            $lowerTeeth = ['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'];
                        @endphp
                        @foreach($lowerTeeth as $tooth)
                            @php
                                $conditions = $toothChartData[$tooth]['conditions'] ?? [];
                                $hasConditions = count($conditions) > 0;
                                $isActive = $activeTooth === $tooth;
                            @endphp
                            <button
                                wire:click="selectTooth('{{ $tooth }}')"
                                title="Tooth {{ $tooth }}"
                                class="relative w-9 h-10 rounded-b-full border-2 text-xs font-bold flex flex-col items-start pt-1 justify-start transition
                                    {{ $isActive ? 'border-accent bg-accent/10 text-accent' : ($hasConditions ? 'border-clinical bg-clinical/10 text-clinical' : 'border-gray-200 bg-white text-gray-500 hover:border-clinical/40 hover:bg-clinical/5') }}"
                            >
                                @if($hasConditions)
                                    <span class="absolute bottom-0.5 right-0.5 w-2 h-2 rounded-full bg-accent"></span>
                                @endif
                                {{ $tooth }}
                            </button>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 text-center mt-2">Lower jaw</p>
                </div>
            </div>

            {{-- Active tooth conditions panel --}}
            @if($activeTooth)
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700">Tooth {{ $activeTooth }} conditions</h3>
                        <button
                            wire:click="selectTooth('{{ $activeTooth }}')"
                            class="text-gray-400 hover:text-gray-600 transition"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Current conditions chips --}}
                    @php $currentConditions = $toothChartData[$activeTooth]['conditions'] ?? []; @endphp
                    @if(count($currentConditions) > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($currentConditions as $cond)
                                <span class="inline-flex items-center gap-1.5 bg-clinical/10 text-clinical text-xs font-medium px-2.5 py-1 rounded-full">
                                    {{ $cond }}
                                    <button
                                        wire:click="removeCondition('{{ $activeTooth }}', '{{ $cond }}')"
                                        class="text-clinical/60 hover:text-clinical transition"
                                    >
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Available conditions grid --}}
                    <p class="text-xs text-gray-400 mb-2">Add condition</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($availableConditions as $condition)
                            <button
                                wire:click="addCondition('{{ $activeTooth }}', '{{ $condition['code'] }}')"
                                @if(in_array($condition['code'], $currentConditions)) disabled @endif
                                class="flex items-center gap-2 px-3 py-2 border border-gray-100 rounded-lg text-xs text-gray-600 hover:border-clinical/40 hover:bg-clinical/5 hover:text-clinical transition disabled:opacity-40 disabled:cursor-not-allowed text-left"
                            >
                                <span class="w-2.5 h-2.5 rounded-full flex-none" style="background-color: {{ $condition['colour'] ?? '#94a3b8' }}"></span>
                                {{ $condition['label'] ?? $condition['code'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Condition summary chips --}}
            @if(count($conditionTags) > 0)
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500 mb-3">Conditions present</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($conditionTags as $tag)
                            <span class="bg-accent/10 text-accent-700 text-xs font-medium px-2.5 py-1 rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: diagnosis notes --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnosis notes</label>
                <p class="text-xs text-gray-400 mb-3">These notes will be included in the patient's plan document.</p>
                <textarea
                    wire:model.blur="diagnosisText"
                    rows="10"
                    placeholder="Describe the patient's dental condition, findings, and recommended course of action..."
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
                ></textarea>
            </div>

            <button
                wire:click="saveDiagnosis"
                class="w-full bg-clinical hover:bg-clinical-700 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition flex items-center justify-center gap-2"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save diagnosis
            </button>
        </div>
    </div>

</div>
