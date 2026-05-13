<div
    x-data="kanban()"
    x-init="init()"
    class="flex flex-col h-full"
>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    {{-- Search bar --}}
    <div class="mb-4 flex items-center gap-3">
        <div class="relative flex-1 max-w-sm">
            <input
                type="text"
                wire:model.live.debounce.300ms="searchTerm"
                placeholder="Search patients..."
                class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </div>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $pipeline->name }}
        </span>
    </div>

    {{-- Kanban columns --}}
    <div class="flex gap-4 overflow-x-auto pb-4 flex-1">
        @foreach ($stages as $stage)
            <div class="flex-shrink-0 w-72 flex flex-col bg-gray-50 dark:bg-gray-900 rounded-xl">
                {{-- Stage header --}}
                <div
                    class="px-4 py-3 rounded-t-xl font-semibold text-white text-sm flex items-center justify-between"
                    style="background-color: {{ $stage['colour'] ?? '#3B82F6' }}"
                >
                    <span>{{ $stage['name'] }}</span>
                    <span class="bg-white/20 rounded-full px-2 py-0.5 text-xs">
                        {{ count($stage['patients'] ?? []) }}
                    </span>
                </div>

                {{-- Patient cards --}}
                <div
                    class="p-2 flex flex-col gap-2 flex-1 min-h-24 kanban-column"
                    data-stage-id="{{ $stage['id'] }}"
                    x-ref="column_{{ $stage['id'] }}"
                >
                    @forelse ($stage['patients'] ?? [] as $patient)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700 cursor-grab active:cursor-grabbing select-none"
                            data-patient-id="{{ $patient['id'] }}"
                            draggable="true"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                        {{ $patient['first_name'] }} {{ $patient['last_name'] }}
                                    </p>
                                    @if (!empty($patient['deal_value']))
                                        <p class="text-xs text-green-600 dark:text-green-400 font-medium mt-0.5">
                                            £{{ number_format($patient['deal_value'], 0) }}
                                        </p>
                                    @endif
                                </div>
                                @if (!empty($patient['assigned_user']['name']))
                                    <div
                                        class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-xs font-semibold text-primary-700 dark:text-primary-300 flex-shrink-0"
                                        title="{{ $patient['assigned_user']['name'] }}"
                                    >
                                        {{ strtoupper(substr($patient['assigned_user']['name'], 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            @php
                                $daysInStage = \Carbon\Carbon::parse($patient['updated_at'])->diffInDays(now());
                            @endphp
                            <p class="text-xs text-gray-400 mt-1.5">{{ $daysInStage }} day{{ $daysInStage !== 1 ? 's' : '' }} in stage</p>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-gray-400 italic">
                            No patients
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function kanban() {
    return {
        sortables: [],

        init() {
            this.$nextTick(() => this.setupSortable());
        },

        setupSortable() {
            const columns = document.querySelectorAll('.kanban-column');

            columns.forEach(column => {
                const sortable = Sortable.create(column, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'opacity-40',
                    chosenClass: 'ring-2 ring-primary-400',
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
