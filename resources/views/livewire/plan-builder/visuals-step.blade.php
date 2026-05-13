<div>

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Visuals</h2>
        <p class="text-gray-500 text-sm mt-1">Add animation clips and before/after cases to help the patient understand their treatment.</p>
    </div>

    <div class="space-y-8">

        {{-- Animation clips --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Animation clips
                    @if(count($animationClips) > 0)
                        <span class="ml-2 bg-clinical/10 text-clinical text-xs px-1.5 py-0.5 rounded-full">{{ count($animationClips) }} selected</span>
                    @endif
                </h3>
            </div>

            @if($availableClips->isEmpty())
                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl p-8 text-center text-gray-400">
                    <p class="text-sm">No animation clips available. Add clips in the Clinic Settings.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($availableClips as $clip)
                        @php $selected = in_array($clip->id, $animationClips); @endphp
                        <div
                            class="relative rounded-xl overflow-hidden border-2 transition cursor-pointer group
                                {{ $selected ? 'border-clinical ring-2 ring-clinical/20' : 'border-gray-100 hover:border-clinical/30' }}"
                        >
                            {{-- Thumbnail --}}
                            <div class="aspect-video bg-gray-100 flex items-center justify-center">
                                @if($clip->thumbnail_url)
                                    <img src="{{ $clip->thumbnail_url }}" alt="{{ $clip->title }}" class="w-full h-full object-cover"/>
                                @else
                                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-3">
                                <p class="text-xs font-medium text-gray-800 truncate">{{ $clip->title }}</p>
                                @if($clip->duration_seconds)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ gmdate('i:s', $clip->duration_seconds) }}</p>
                                @endif
                            </div>

                            {{-- Toggle button --}}
                            <button
                                @if($selected)
                                    wire:click="removeClip({{ $clip->id }})"
                                @else
                                    wire:click="addClip({{ $clip->id }})"
                                @endif
                                class="absolute inset-0 w-full h-full"
                            ></button>

                            {{-- Selected indicator --}}
                            @if($selected)
                                <div class="absolute top-2 right-2 w-6 h-6 bg-clinical rounded-full flex items-center justify-center">
                                    <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Before/after cases --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Before &amp; after cases
                    @if(count($beforeAfterCases) > 0)
                        <span class="ml-2 bg-clinical/10 text-clinical text-xs px-1.5 py-0.5 rounded-full">{{ count($beforeAfterCases) }} selected</span>
                    @endif
                </h3>
            </div>

            @if($availableCases->isEmpty())
                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl p-8 text-center text-gray-400">
                    <p class="text-sm">No before/after cases available. Add cases in the Clinic Settings.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availableCases as $case)
                        @php $selected = in_array($case->id, $beforeAfterCases); @endphp
                        <div
                            class="relative rounded-xl overflow-hidden border-2 transition
                                {{ $selected ? 'border-clinical ring-2 ring-clinical/20' : 'border-gray-100 hover:border-clinical/30' }}"
                        >
                            {{-- Before/after images --}}
                            <div class="grid grid-cols-2 gap-0.5 bg-gray-200">
                                <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                    @if($case->before_image_url)
                                        <img src="{{ $case->before_image_url }}" alt="Before" class="w-full h-full object-cover"/>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="absolute bottom-1 left-1 text-xs bg-black/50 text-white px-1 rounded">Before</span>
                                </div>
                                <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                    @if($case->after_image_url)
                                        <img src="{{ $case->after_image_url }}" alt="After" class="w-full h-full object-cover"/>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="absolute bottom-1 left-1 text-xs bg-black/50 text-white px-1 rounded">After</span>
                                </div>
                            </div>

                            <div class="p-3">
                                <p class="text-xs font-medium text-gray-800 truncate">{{ $case->title }}</p>
                                @if($case->treatment_name)
                                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $case->treatment_name }}</p>
                                @endif
                            </div>

                            <button
                                @if($selected)
                                    wire:click="removeCase({{ $case->id }})"
                                @else
                                    wire:click="addCase({{ $case->id }})"
                                @endif
                                class="absolute inset-0 w-full h-full"
                            ></button>

                            @if($selected)
                                <div class="absolute top-2 right-2 w-6 h-6 bg-clinical rounded-full flex items-center justify-center">
                                    <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>

</div>
