<div class="flex h-[calc(100vh-64px)] bg-white dark:bg-gray-900" x-data="{ miniDate: new Date() }">

    {{-- Left sidebar: mini calendar --}}
    <div class="hidden lg:flex flex-col w-60 border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4 shrink-0">
        {{-- Mini month calendar --}}
        @php
            $miniStart = \Carbon\Carbon::parse($currentDateString)->startOfMonth();
            $miniEnd = \Carbon\Carbon::parse($currentDateString)->endOfMonth();
            $miniGridStart = $miniStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $today = now()->format('Y-m-d');
        @endphp
        <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($currentDateString)->format('F Y') }}</span>
                <div class="flex gap-1">
                    <button wire:click="previousPeriod" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button wire:click="nextPeriod" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-0">
                @foreach(['M','T','W','T','F','S','S'] as $d)
                    <div class="text-[10px] font-medium text-gray-400 text-center py-1">{{ $d }}</div>
                @endforeach
                @php $mDate = $miniGridStart->copy(); @endphp
                @for($i = 0; $i < 42; $i++)
                    @php
                        $mStr = $mDate->format('Y-m-d');
                        $isCurrent = $mDate->month === $miniStart->month;
                        $isToday = $mStr === $today;
                        $hasAppt = collect($appointments)->contains(fn($a) => \Carbon\Carbon::parse($a['starts_at'])->format('Y-m-d') === $mStr);
                    @endphp
                    <div class="text-center py-0.5">
                        <span class="inline-flex items-center justify-center w-6 h-6 text-[11px] rounded-full cursor-pointer
                            {{ $isToday ? 'bg-blue-600 text-white font-bold' : ($isCurrent ? 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' : 'text-gray-400 dark:text-gray-600') }}">
                            {{ $mDate->day }}
                        </span>
                        @if($hasAppt && !$isToday)
                            <div class="w-1 h-1 bg-blue-500 rounded-full mx-auto -mt-0.5"></div>
                        @endif
                    </div>
                    @php $mDate->addDay(); @endphp
                @endfor
            </div>
        </div>

        {{-- Upcoming appointments --}}
        <div class="mt-2">
            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Upcoming</h4>
            <div class="space-y-2">
                @php
                    $upcoming = collect($appointments)
                        ->filter(fn($a) => \Carbon\Carbon::parse($a['starts_at'])->isFuture() || \Carbon\Carbon::parse($a['starts_at'])->isToday())
                        ->sortBy('starts_at')
                        ->take(5);
                @endphp
                @forelse($upcoming as $appt)
                    <div class="flex items-start gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                        <div class="w-1 h-8 rounded-full shrink-0 mt-0.5" style="background-color: {{ $appt['colour'] ?? '#3B82F6' }}"></div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $appt['title'] }}</div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($appt['starts_at'])->format('D, H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 dark:text-gray-500 px-2">No upcoming events</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Main calendar area --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Toolbar --}}
        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shrink-0">
            <div class="flex items-center gap-2">
                {{-- New event button --}}
                <button wire:click="openCreateModal" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New event
                </button>

                <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

                {{-- View toggles --}}
                @foreach(['day' => 'Day', 'week' => 'Week', 'month' => 'Month'] as $mode => $label)
                    <button wire:click="setViewMode('{{ $mode }}')"
                        class="px-3 py-1.5 text-sm rounded-md transition-colors
                            {{ $viewMode === $mode
                                ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-medium'
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-2">
                {{-- Today --}}
                <button wire:click="goToToday" class="px-3 py-1.5 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Today
                </button>

                {{-- Nav arrows --}}
                <button wire:click="previousPeriod" class="p-1.5 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button wire:click="nextPeriod" class="p-1.5 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Period label --}}
                <h2 class="text-base font-semibold text-gray-900 dark:text-white ml-1">{{ $this->headerLabel }}</h2>
            </div>
        </div>

        {{-- =================== MONTH VIEW =================== --}}
        @if($viewMode === 'month')
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Day of week headers --}}
            <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 shrink-0">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider {{ !$loop->first ? 'border-l border-gray-200 dark:border-gray-700' : '' }}">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            {{-- Day cells grid — fills all remaining space --}}
            <div class="grid grid-cols-7 flex-1" style="grid-auto-rows: 1fr;">
                @foreach($this->getCalendarDays() as $day)
                    <div
                        wire:click="openCreateModal('{{ $day['date'] }}')"
                        class="border-b border-r border-gray-200 dark:border-gray-700/60 p-1.5 cursor-pointer transition-colors overflow-hidden
                            {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800/40' }}
                            {{ $day['isToday'] ? 'ring-inset ring-1 ring-blue-400 dark:ring-blue-500 bg-blue-50/50 dark:bg-blue-900/10' : '' }}
                            hover:bg-gray-50 dark:hover:bg-gray-800/60"
                    >
                        {{-- Day number --}}
                        <div class="flex items-start justify-between mb-1">
                            @if($day['isToday'])
                                <span class="inline-flex items-center justify-center w-7 h-7 text-sm font-bold bg-blue-600 text-white rounded-full">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-7 h-7 text-sm {{ $day['isCurrentMonth'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-600' }}">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Appointment pills --}}
                        <div class="space-y-0.5">
                            @foreach(array_slice($day['appointments'], 0, 3) as $appt)
                                <div class="flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] leading-tight truncate cursor-pointer hover:opacity-80 transition-opacity"
                                     style="background-color: {{ $appt['colour'] ?? '#3B82F6' }}20; border-left: 3px solid {{ $appt['colour'] ?? '#3B82F6' }};">
                                    <span class="font-medium" style="color: {{ $appt['colour'] ?? '#3B82F6' }}">{{ \Carbon\Carbon::parse($appt['starts_at'])->format('H:i') }}</span>
                                    <span class="text-gray-700 dark:text-gray-300 truncate">{{ $appt['title'] }}</span>
                                </div>
                            @endforeach
                            @if(count($day['appointments']) > 3)
                                <div class="text-[10px] text-blue-500 dark:text-blue-400 font-medium px-1.5">+{{ count($day['appointments']) - 3 }} more</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- =================== WEEK VIEW =================== --}}
        @elseif($viewMode === 'week')
        <div class="flex-1 overflow-auto">
            {{-- Sticky day headers --}}
            <div class="sticky top-0 z-10 grid bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700" style="grid-template-columns: 60px repeat(7, 1fr);">
                <div class="border-r border-gray-200 dark:border-gray-700"></div>
                @foreach($this->getWeekDays() as $day)
                    <div class="border-r border-gray-200 dark:border-gray-700 px-2 py-2 text-center">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ $day['dayName'] }}</div>
                        @if($day['isToday'])
                            <div class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold mt-0.5">{{ $day['dayNumber'] }}</div>
                        @else
                            <div class="text-lg font-light text-gray-700 dark:text-gray-300 mt-0.5">{{ $day['dayNumber'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Time grid --}}
            <div class="grid" style="grid-template-columns: 60px repeat(7, 1fr);">
                @for($h = 0; $h < 24; $h++)
                    {{-- Time label --}}
                    <div class="border-r border-b border-gray-200 dark:border-gray-700/60 h-14 flex items-start justify-end pr-2 pt-0.5">
                        <span class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">{{ $h === 0 ? '' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</span>
                    </div>
                    {{-- 7 day slots --}}
                    @foreach($this->getWeekDays() as $day)
                        <div class="border-r border-b border-gray-100 dark:border-gray-800 h-14 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 cursor-pointer transition-colors relative
                            {{ $day['isToday'] ? 'bg-blue-50/30 dark:bg-blue-900/5' : '' }}"
                             wire:click="openCreateModal('{{ $day['date'] }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
                            {{-- Appointments would be positioned here --}}
                            @foreach(array_filter($appointments, function($a) use ($day, $h) {
                                return \Carbon\Carbon::parse($a['starts_at'])->format('Y-m-d') === $day['date']
                                    && \Carbon\Carbon::parse($a['starts_at'])->hour === $h;
                            }) as $appt)
                                <div class="absolute inset-x-0.5 top-0.5 rounded px-1 py-0.5 text-[10px] leading-tight truncate z-10"
                                     style="background-color: {{ $appt['colour'] ?? '#3B82F6' }}; color: white;">
                                    {{ \Carbon\Carbon::parse($appt['starts_at'])->format('H:i') }} {{ $appt['title'] }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>

        {{-- =================== DAY VIEW =================== --}}
        @else
        <div class="flex-1 overflow-auto">
            <div class="max-w-4xl mx-auto">
                {{-- Day header --}}
                <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-900 z-10">
                    <div class="flex items-center gap-3">
                        @php $dayDate = \Carbon\Carbon::parse($currentDateString); @endphp
                        @if($dayDate->isToday())
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full text-xl font-bold">{{ $dayDate->day }}</div>
                        @else
                            <div class="inline-flex items-center justify-center w-12 h-12 text-xl font-light text-gray-700 dark:text-gray-300">{{ $dayDate->day }}</div>
                        @endif
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">{{ $dayDate->format('l') }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ $dayDate->format('F Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Hour rows --}}
                <div class="grid" style="grid-template-columns: 70px 1fr;">
                    @for($h = 0; $h < 24; $h++)
                        <div class="border-b border-gray-100 dark:border-gray-800 h-16 flex items-start justify-end pr-3 pt-1">
                            <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">{{ $h === 0 ? '' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</span>
                        </div>
                        <div class="border-b border-l border-gray-100 dark:border-gray-800 h-16 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 cursor-pointer transition-colors relative"
                             wire:click="openCreateModal('{{ $dayDate->format('Y-m-d') }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
                            @foreach(array_filter($appointments, function($a) use ($dayDate, $h) {
                                return \Carbon\Carbon::parse($a['starts_at'])->format('Y-m-d') === $dayDate->format('Y-m-d')
                                    && \Carbon\Carbon::parse($a['starts_at'])->hour === $h;
                            }) as $appt)
                                <div class="absolute inset-x-1 top-0.5 rounded-md px-2 py-1 text-xs z-10 shadow-sm"
                                     style="background-color: {{ $appt['colour'] ?? '#3B82F6' }}; color: white;">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($appt['starts_at'])->format('H:i') }} - {{ \Carbon\Carbon::parse($appt['ends_at'])->format('H:i') }}</span>
                                    <span class="ml-1">{{ $appt['title'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- =================== CREATE MODAL =================== --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" wire:click.self="$set('showCreateModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">New Appointment</h3>
                <button wire:click="$set('showCreateModal', false)" class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="createAppointment" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                    <input type="text" wire:model="title" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add a title" autofocus>
                    @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start</label>
                        <input type="datetime-local" wire:model="startsAt" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                        @error('startsAt') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End</label>
                        <input type="datetime-local" wire:model="endsAt" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                        @error('endsAt') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                        <select wire:model="appointmentType" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                            <option value="consultation">Consultation</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="treatment">Treatment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient</label>
                        <select wire:model="patientId" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                            <option value="">None</option>
                            @foreach($this->patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add description..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
