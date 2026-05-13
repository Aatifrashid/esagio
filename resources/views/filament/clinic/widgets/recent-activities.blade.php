<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Recent Activities</x-slot>

        @php $activities = $this->getActivities(); @endphp

        @if ($activities->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No recent activities.</p>
        @else
            <div class="space-y-3">
                @foreach ($activities as $activity)
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                                @if($activity->type === 'call') bg-blue-100 text-blue-600
                                @elseif($activity->type === 'email') bg-green-100 text-green-600
                                @elseif($activity->type === 'meeting') bg-yellow-100 text-yellow-600
                                @else bg-gray-100 text-gray-600
                                @endif
                                text-xs font-bold uppercase">
                                {{ strtoupper(substr($activity->type, 0, 2)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $activity->subject }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $activity->patient?->first_name }} {{ $activity->patient?->last_name }}
                                &middot;
                                {{ $activity->occurred_at?->diffForHumans() }}
                            </p>
                        </div>
                        @if ($activity->outcome)
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($activity->outcome === 'positive') bg-green-100 text-green-700
                                @elseif($activity->outcome === 'negative') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $activity->outcome)) }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
