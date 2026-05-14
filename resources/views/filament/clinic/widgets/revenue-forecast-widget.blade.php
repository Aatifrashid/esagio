<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-chart-bar class="w-5 h-5 text-primary-500" />
                Revenue Forecast
            </div>
        </x-slot>

        <x-slot name="description">
            Weighted pipeline value based on stage probability
        </x-slot>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Pipeline Value</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">£{{ number_format($totalUnweighted, 0) }}</p>
            </div>
            <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-4">
                <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Weighted Forecast</p>
                <p class="text-xl font-bold text-primary-700 dark:text-primary-300 mt-1">£{{ number_format($totalWeighted, 0) }}</p>
            </div>
            <div class="bg-success-50 dark:bg-success-900/20 rounded-xl p-4">
                <p class="text-xs text-success-600 dark:text-success-400 font-medium">Won This Month</p>
                <p class="text-xl font-bold text-success-700 dark:text-success-300 mt-1">£{{ number_format($wonRevenue, 0) }}</p>
            </div>
            @if($monthlyTarget > 0)
            <div class="bg-warning-50 dark:bg-warning-900/20 rounded-xl p-4">
                <p class="text-xs text-warning-600 dark:text-warning-400 font-medium">Target Progress</p>
                <p class="text-xl font-bold text-warning-700 dark:text-warning-300 mt-1">{{ min(round(($wonRevenue / $monthlyTarget) * 100), 100) }}%</p>
                @php $pct = min(($wonRevenue / $monthlyTarget) * 100, 100); @endphp
                <div class="w-full bg-warning-200 dark:bg-warning-800 rounded-full h-1.5 mt-2">
                    <div class="bg-warning-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @else
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Active Deals</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stages->sum('deal_count') }}</p>
            </div>
            @endif
        </div>

        {{-- Pipeline breakdown --}}
        @if($stages->isNotEmpty())
        <div class="space-y-3">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pipeline Breakdown</p>

            {{-- Stacked bar --}}
            @if($totalUnweighted > 0)
            <div class="flex rounded-lg overflow-hidden h-8">
                @foreach($stages as $stage)
                    @if($stage['total_value'] > 0)
                        @php $width = ($stage['total_value'] / $totalUnweighted) * 100; @endphp
                        <div
                            class="flex items-center justify-center text-white text-[10px] font-bold transition-all hover:opacity-80 cursor-default"
                            style="width: {{ max($width, 2) }}%; background-color: {{ $stage['colour'] }}"
                            title="{{ $stage['name'] }}: £{{ number_format($stage['total_value'], 0) }} ({{ $stage['probability'] }}%)"
                        >
                            @if($width > 8)
                                {{ $stage['probability'] }}%
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            @endif

            {{-- Stage rows --}}
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($stages as $stage)
                <div class="flex items-center gap-3 py-2.5">
                    <span class="w-3 h-3 rounded-full flex-none" style="background-color: {{ $stage['colour'] }}"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $stage['name'] }}
                            @if($stage['is_won'])
                                <span class="text-success-500 text-xs">✓ Won</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stage['deal_count'] }} {{ Str::plural('deal', $stage['deal_count']) }} · {{ $stage['probability'] }}% probability</p>
                    </div>
                    <div class="text-right flex-none">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">£{{ number_format($stage['weighted_value'], 0) }}</p>
                        <p class="text-xs text-gray-400">of £{{ number_format($stage['total_value'], 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
            <div class="text-center py-8">
                <x-heroicon-o-chart-bar class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                <p class="text-sm text-gray-400">No pipeline data yet. Set up pipeline stages to see forecasts.</p>
            </div>
        @endif

    </x-filament::section>
</x-filament-widgets::widget>
