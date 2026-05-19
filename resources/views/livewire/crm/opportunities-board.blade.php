<div
    x-data="opportunitiesBoard()"
    x-init="init()"
    class="flex flex-col h-[calc(100vh-64px)] bg-gray-900"
>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    {{-- Header bar --}}
    <div class="sticky top-0 z-10 flex items-center gap-4 px-4 py-3 border-b border-gray-700 bg-gray-900">
        {{-- Pipeline selector --}}
        <select
            wire:model.live="selectedPipelineId"
            wire:change="switchPipeline($event.target.value)"
            class="bg-gray-800 border border-gray-600 text-white text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 min-w-[200px]"
            style="color-scheme: dark;"
        >
            @foreach ($pipelines as $pipeline)
                <option value="{{ $pipeline['id'] }}" class="bg-gray-800 text-white">{{ $pipeline['name'] }}</option>
            @endforeach
        </select>

        {{-- Opportunity count --}}
        <span class="text-blue-400 text-sm font-medium whitespace-nowrap">
            {{ number_format($totalOpportunities) }} {{ Str::plural('opportunity', $totalOpportunities) }}
        </span>

        {{-- View toggle --}}
        <div class="flex items-center bg-gray-800 rounded-lg border border-gray-600 overflow-hidden">
            <button
                wire:click="$set('viewMode', 'kanban')"
                class="p-2 transition-colors {{ $viewMode === 'kanban' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white' }}"
                title="Kanban view"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
            <button
                wire:click="$set('viewMode', 'list')"
                class="p-2 transition-colors {{ $viewMode === 'list' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white' }}"
                title="List view"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Search --}}
        <div class="relative">
            <input
                type="text"
                wire:model.live.debounce.300ms="searchTerm"
                placeholder="Search opportunities..."
                class="w-64 pl-9 pr-4 py-2 rounded-lg border border-gray-600 bg-gray-800 text-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </div>

        {{-- Add opportunity button --}}
        <button class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Opportunity
        </button>
    </div>

    {{-- Kanban view --}}
    @if ($viewMode === 'kanban')
        <div class="flex gap-4 overflow-x-auto px-4 py-4 flex-1" wire:key="kanban-{{ $selectedPipelineId }}">
            @foreach ($stages as $stage)
                @php
                    $patients = $stage['patients'] ?? [];
                    $stageTotal = collect($patients)->sum('deal_value');
                @endphp
                <div class="flex-shrink-0 w-72 flex flex-col max-h-full" x-data="{ collapsed: false }">
                    {{-- Column header --}}
                    <div class="px-3 py-2.5 rounded-t-lg flex items-center justify-between" style="background-color: {{ $stage['colour'] ?? '#3B82F6' }}">
                        <div class="flex items-center gap-2 min-w-0">
                            <button @click="collapsed = !collapsed" class="text-white/70 hover:text-white transition-colors">
                                <svg class="w-4 h-4 transition-transform" :class="{ '-rotate-90': collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <span class="font-semibold text-white text-sm truncate">{{ $stage['name'] }}</span>
                        </div>
                        <span class="bg-white/20 rounded-full px-2 py-0.5 text-xs text-white font-medium flex-shrink-0">
                            {{ count($patients) }}
                        </span>
                    </div>

                    {{-- Stage value summary --}}
                    <div class="px-3 py-1.5 bg-gray-800 border-x border-gray-700 text-xs text-gray-400">
                        {{ count($patients) }} {{ Str::plural('Opportunity', count($patients)) }} &middot; &pound;{{ number_format($stageTotal, 2) }}
                    </div>

                    {{-- Cards container --}}
                    <div
                        x-show="!collapsed"
                        x-transition
                        class="p-2 flex flex-col gap-2 flex-1 overflow-y-auto bg-gray-800/50 border-x border-b border-gray-700 rounded-b-lg min-h-[80px] kanban-column"
                        data-stage-id="{{ $stage['id'] }}"
                        x-ref="column_{{ $stage['id'] }}"
                    >
                        @forelse ($patients as $patient)
                            @php
                                $daysInStage = (int) \Carbon\Carbon::parse($patient['updated_at'])->diffInDays(now());
                            @endphp
                            <div
                                class="bg-gray-800 rounded-lg p-3 border border-gray-700 cursor-grab active:cursor-grabbing select-none hover:border-gray-500 transition-colors"
                                data-patient-id="{{ $patient['id'] }}"
                                draggable="true"
                            >
                                {{-- Top row: name + avatar --}}
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm text-white truncate">
                                            {{ $patient['first_name'] }} {{ $patient['last_name'] }}
                                        </p>
                                    </div>
                                    @if (!empty($patient['assigned_user']['name']))
                                        <div
                                            class="w-7 h-7 rounded-full bg-blue-600/30 flex items-center justify-center text-xs font-semibold text-blue-300 flex-shrink-0"
                                            title="{{ $patient['assigned_user']['name'] }}"
                                        >
                                            {{ strtoupper(substr($patient['assigned_user']['name'], 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Source --}}
                                @if (!empty($patient['source']))
                                    <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-xs bg-gray-700 text-gray-300">
                                        {{ $patient['source'] }}
                                    </span>
                                @endif

                                {{-- Value --}}
                                <p class="text-sm text-green-400 font-medium mt-1.5">
                                    &pound;{{ number_format($patient['deal_value'] ?? 0, 2) }}
                                </p>

                                {{-- Action icons --}}
                                <div class="flex items-center gap-3 mt-2 pt-2 border-t border-gray-700">
                                    {{-- Phone --}}
                                    <button class="text-gray-500 hover:text-blue-400 transition-colors" title="Call">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </button>
                                    {{-- Email --}}
                                    <button class="text-gray-500 hover:text-blue-400 transition-colors" title="Email">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </button>
                                    {{-- Notes --}}
                                    <button class="text-gray-500 hover:text-blue-400 transition-colors" title="Notes">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    {{-- Tasks --}}
                                    <button class="text-gray-500 hover:text-blue-400 transition-colors" title="Tasks">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    </button>
                                    {{-- Calendar --}}
                                    <button class="text-gray-500 hover:text-blue-400 transition-colors" title="Appointment">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </button>
                                    {{-- WhatsApp --}}
                                    <button class="text-gray-500 hover:text-green-400 transition-colors" title="WhatsApp">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.553 4.12 1.522 5.857L.055 23.456l5.747-1.51A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82a9.796 9.796 0 01-5.09-1.42l-.365-.217-3.781.991 1.009-3.685-.238-.378A9.794 9.794 0 012.18 12c0-5.414 4.406-9.82 9.82-9.82 5.414 0 9.82 4.406 9.82 9.82 0 5.414-4.406 9.82-9.82 9.82z"/></svg>
                                    </button>

                                    <span class="ml-auto text-xs text-gray-500">{{ $daysInStage }}d</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-gray-500 italic">
                                No opportunities
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach

            @if (empty($stages))
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-sm">No pipeline selected or no stages configured.</p>
                        <p class="text-xs mt-1">Create a pipeline in Pipeline Settings to get started.</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- List view --}}
    @if ($viewMode === 'list')
        <div class="flex-1 overflow-auto px-4 py-4">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-gray-800 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Name</th>
                        <th class="px-4 py-3">Stage</th>
                        <th class="px-4 py-3">Value</th>
                        <th class="px-4 py-3">Source</th>
                        <th class="px-4 py-3">Assigned To</th>
                        <th class="px-4 py-3 rounded-tr-lg">Days in Stage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stages as $stage)
                        @foreach ($stage['patients'] ?? [] as $patient)
                            @php
                                $daysInStage = (int) \Carbon\Carbon::parse($patient['updated_at'])->diffInDays(now());
                            @endphp
                            <tr class="border-b border-gray-700 hover:bg-gray-800/50 transition-colors">
                                <td class="px-4 py-3 text-white font-medium">
                                    {{ $patient['first_name'] }} {{ $patient['last_name'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $stage['colour'] ?? '#3B82F6' }}"></span>
                                        <span class="text-gray-300">{{ $stage['name'] }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-green-400">&pound;{{ number_format($patient['deal_value'] ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $patient['source'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $patient['assigned_user']['name'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $daysInStage }}d</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            @if ($totalOpportunities === 0)
                <div class="text-center py-16 text-gray-500">
                    <p class="text-sm">No opportunities found.</p>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
function opportunitiesBoard() {
    return {
        sortables: [],

        init() {
            this.$nextTick(() => this.setupSortable());

            Livewire.hook('morph.updated', ({ el }) => {
                this.$nextTick(() => this.setupSortable());
            });
        },

        setupSortable() {
            // Destroy existing sortables
            this.sortables.forEach(s => s.destroy());
            this.sortables = [];

            const columns = document.querySelectorAll('.kanban-column');

            columns.forEach(column => {
                const sortable = Sortable.create(column, {
                    group: 'opportunities',
                    animation: 150,
                    ghostClass: 'opacity-40',
                    chosenClass: 'ring-2 ring-blue-400',
                    dragClass: 'rotate-2',
                    onEnd: (event) => {
                        const patientId = parseInt(event.item.dataset.patientId);
                        const stageId = parseInt(event.to.dataset.stageId);

                        if (event.from !== event.to) {
                            @this.movePatient(patientId, stageId);
                        }
                    },
                });

                this.sortables.push(sortable);
            });
        },
    };
}
</script>
