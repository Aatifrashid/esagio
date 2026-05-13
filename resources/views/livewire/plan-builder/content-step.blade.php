<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Content</h2>
        <p class="text-gray-500 text-sm mt-1">Personalise the patient's plan with a letter, custom sections, and legal details.</p>
    </div>

    <div class="space-y-5">

        {{-- Intro letter --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Introduction letter</h3>
                <span class="text-xs text-gray-400">Shown at the top of the patient's plan</span>
            </div>
            <textarea
                wire:model.blur="introLetter"
                rows="6"
                placeholder="Dear [Patient name],&#10;&#10;Thank you for visiting us. Following your consultation, we are pleased to present the following treatment plan..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
            ></textarea>
        </div>

        {{-- Custom sections --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Custom sections</h3>
                <button
                    wire:click="addSection"
                    class="flex items-center gap-1.5 text-xs font-medium text-clinical hover:text-clinical-700 border border-clinical/30 hover:border-clinical/60 rounded-lg px-3 py-1.5 transition"
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add section
                </button>
            </div>

            @if($sections->isEmpty())
                <div class="px-5 py-10 text-center text-gray-400">
                    <svg class="h-10 w-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">No custom sections added</p>
                    <p class="text-xs mt-1">Add sections like FAQs, aftercare instructions, or custom messages.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($sections->where('type', 'custom') as $section)
                        <div
                            class="p-5 group"
                            wire:key="section-{{ $section->id }}"
                            x-data="{ editing: false }"
                        >
                            <div class="flex items-start gap-3">
                                {{-- Visibility toggle --}}
                                <button
                                    wire:click="toggleSectionVisibility({{ $section->id }})"
                                    title="{{ $section->is_visible ? 'Hide section' : 'Show section' }}"
                                    class="mt-0.5 flex-none transition"
                                >
                                    @if($section->is_visible)
                                        <svg class="h-4 w-4 text-clinical" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    @endif
                                </button>

                                <div class="flex-1 min-w-0">
                                    <input
                                        type="text"
                                        value="{{ $section->title }}"
                                        wire:change="updateSection({{ $section->id }}, { title: $event.target.value })"
                                        class="w-full border-0 border-b border-transparent hover:border-gray-200 focus:border-clinical bg-transparent text-sm font-semibold text-gray-800 focus:ring-0 px-0 py-0.5 transition"
                                    />

                                    <div x-show="editing" class="mt-2">
                                        <textarea
                                            rows="4"
                                            wire:change="updateSection({{ $section->id }}, { content: $event.target.value })"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
                                        >{{ $section->content }}</textarea>
                                    </div>

                                    <div x-show="!editing" class="mt-1">
                                        <p class="text-sm text-gray-500 line-clamp-2">{{ $section->content ?: 'No content yet. Click edit to add.' }}</p>
                                    </div>

                                    <div class="flex items-center gap-3 mt-2">
                                        <button
                                            x-on:click="editing = !editing"
                                            class="text-xs text-clinical hover:text-clinical-700 font-medium transition"
                                            x-text="editing ? 'Collapse' : 'Edit content'"
                                        ></button>
                                    </div>
                                </div>

                                <button
                                    wire:click="removeSection({{ $section->id }})"
                                    wire:confirm="Remove this section?"
                                    class="text-gray-300 hover:text-red-500 transition p-1 opacity-0 group-hover:opacity-100"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Guarantee text --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Guarantee text</h3>
            <textarea
                wire:model.blur="guaranteeText"
                rows="3"
                placeholder="Describe your clinic's treatment guarantee..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
            ></textarea>
        </div>

        {{-- Legal disclaimer --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Legal disclaimer</h3>
            <textarea
                wire:model.blur="legalDisclaimer"
                rows="3"
                placeholder="Add any required legal or consent language..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
            ></textarea>
        </div>

        <button
            wire:click="saveContent"
            class="w-full bg-clinical hover:bg-clinical-700 text-white text-sm font-medium rounded-lg px-4 py-3 transition flex items-center justify-center gap-2"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save content
        </button>

    </div>

</div>
