<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Upcoming Tasks</x-slot>

        @php $tasks = $this->getTasks(); @endphp

        @if ($tasks->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No upcoming tasks.</p>
        @else
            <div class="space-y-2">
                @foreach ($tasks as $task)
                    @php
                        $isOverdue = $task->due_date?->isPast();
                        $isToday = $task->due_date?->isToday();
                    @endphp
                    <div class="flex items-center gap-3 p-2 rounded-lg {{ $isOverdue ? 'bg-red-50 dark:bg-red-900/20' : ($isToday ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-gray-50 dark:bg-gray-800') }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $task->title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $task->patient?->first_name }} {{ $task->patient?->last_name }}
                                @if ($task->assignedUser)
                                    &middot; {{ $task->assignedUser->name }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-medium {{ $isOverdue ? 'text-red-600' : ($isToday ? 'text-yellow-600' : 'text-gray-500') }}">
                                @if ($isOverdue)
                                    Overdue
                                @elseif ($isToday)
                                    Today
                                @else
                                    {{ $task->due_date?->format('d M') }}
                                @endif
                            </p>
                            <span class="text-xs px-1.5 py-0.5 rounded
                                @if($task->priority === 'urgent') bg-red-100 text-red-700
                                @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
