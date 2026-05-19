<div class="flex flex-col bg-gray-900" style="height: calc(100vh - 48px);">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between px-4 py-2 border-b border-gray-700 shrink-0">
        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New event
            </button>

            <div class="w-px h-6 bg-gray-700 mx-1"></div>

            @foreach(['day' => 'Day', 'week' => 'Week', 'month' => 'Month'] as $mode => $label)
                <button wire:click="setViewMode('{{ $mode }}')"
                    class="px-3 py-1.5 text-sm rounded-md transition-colors
                        {{ $viewMode === $mode
                            ? 'bg-blue-900/40 text-blue-400 font-medium'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="goToToday" class="px-3 py-1.5 text-sm font-medium rounded-md border border-gray-600 text-gray-300 hover:bg-gray-800 transition-colors">
                Today
            </button>
            <button wire:click="previousPeriod" class="p-1.5 rounded-md text-gray-400 hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="nextPeriod" class="p-1.5 rounded-md text-gray-400 hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <h2 class="text-lg font-semibold text-white ml-1">{{ $this->headerLabel }}</h2>
        </div>
    </div>

    {{-- =================== MONTH VIEW =================== --}}
    @if($viewMode === 'month')
    <div class="flex flex-col flex-1 min-h-0">
        {{-- Day headers --}}
        <div class="grid grid-cols-7 border-b border-gray-700 shrink-0">
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider {{ !$loop->first ? 'border-l border-gray-700' : '' }}">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        {{-- Day grid --}}
        @php
            $days = $this->getCalendarDays();
            $weeks = array_chunk($days, 7);
        @endphp
        <div class="flex flex-col flex-1 min-h-0">
            @foreach($weeks as $week)
                <div class="grid grid-cols-7 flex-1 min-h-0 border-b border-gray-700/60 last:border-b-0">
                    @foreach($week as $day)
                        <div
                            wire:click="openCreateModal('{{ $day['date'] }}')"
                            class="flex flex-col p-2 cursor-pointer transition-colors overflow-hidden
                                {{ !$loop->first ? 'border-l border-gray-700/60' : '' }}
                                {{ $day['isCurrentMonth'] ? 'bg-gray-900' : 'bg-gray-800/40' }}
                                {{ $day['isToday'] ? 'bg-blue-900/15 ring-1 ring-inset ring-blue-500/40' : '' }}
                                hover:bg-gray-800/60"
                        >
                            {{-- Day number --}}
                            @if($day['isToday'])
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold bg-blue-600 text-white rounded-full shrink-0">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-medium {{ $day['isCurrentMonth'] ? 'text-gray-200' : 'text-gray-600' }} shrink-0">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @endif

                            {{-- Appointments --}}
                            <div class="flex flex-col gap-0.5 mt-1 min-h-0 overflow-hidden flex-1">
                                @foreach(array_slice($day['appointments'], 0, 3) as $appt)
                                    <div class="flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] leading-tight truncate shrink-0"
                                         style="background-color: {{ $appt['colour'] ?? '#3B82F6' }}20; border-left: 3px solid {{ $appt['colour'] ?? '#3B82F6' }};">
                                        <span class="font-medium" style="color: {{ $appt['colour'] ?? '#3B82F6' }}">{{ \Carbon\Carbon::parse($appt['starts_at'])->format('H:i') }}</span>
                                        <span class="text-gray-300 truncate">{{ $appt['title'] }}</span>
                                    </div>
                                @endforeach
                                @if(count($day['appointments']) > 3)
                                    <div class="text-[10px] text-blue-400 font-medium px-1.5 shrink-0">+{{ count($day['appointments']) - 3 }} more</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    {{-- =================== WEEK VIEW =================== --}}
    @elseif($viewMode === 'week')
    <div class="flex-1 overflow-auto">
        <div class="sticky top-0 z-10 grid bg-gray-900 border-b border-gray-700" style="grid-template-columns: 60px repeat(7, 1fr);">
            <div class="border-r border-gray-700"></div>
            @foreach($this->getWeekDays() as $day)
                <div class="border-r border-gray-700 px-2 py-2 text-center">
                    <div class="text-xs font-medium text-gray-400 uppercase">{{ $day['dayName'] }}</div>
                    @if($day['isToday'])
                        <div class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold mt-0.5">{{ $day['dayNumber'] }}</div>
                    @else
                        <div class="text-lg font-light text-gray-300 mt-0.5">{{ $day['dayNumber'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="grid" style="grid-template-columns: 60px repeat(7, 1fr);">
            @for($h = 0; $h < 24; $h++)
                <div class="border-r border-b border-gray-700/60 h-14 flex items-start justify-end pr-2 pt-0.5">
                    <span class="text-[11px] text-gray-500 font-medium">{{ $h === 0 ? '' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</span>
                </div>
                @foreach($this->getWeekDays() as $day)
                    <div class="border-r border-b border-gray-800 h-14 hover:bg-blue-900/10 cursor-pointer transition-colors relative
                        {{ $day['isToday'] ? 'bg-blue-900/5' : '' }}"
                         wire:click="openCreateModal('{{ $day['date'] }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
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
            @php $dayDate = \Carbon\Carbon::parse($currentDateString); @endphp
            <div class="px-6 py-3 border-b border-gray-700 sticky top-0 bg-gray-900 z-10">
                <div class="flex items-center gap-3">
                    @if($dayDate->isToday())
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full text-xl font-bold">{{ $dayDate->day }}</div>
                    @else
                        <div class="inline-flex items-center justify-center w-12 h-12 text-xl font-light text-gray-300">{{ $dayDate->day }}</div>
                    @endif
                    <div>
                        <div class="text-sm font-medium text-gray-400 uppercase">{{ $dayDate->format('l') }}</div>
                        <div class="text-xs text-gray-500">{{ $dayDate->format('F Y') }}</div>
                    </div>
                </div>
            </div>
            <div class="grid" style="grid-template-columns: 70px 1fr;">
                @for($h = 0; $h < 24; $h++)
                    <div class="border-b border-gray-800 h-16 flex items-start justify-end pr-3 pt-1">
                        <span class="text-xs text-gray-500 font-medium">{{ $h === 0 ? '' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</span>
                    </div>
                    <div class="border-b border-l border-gray-800 h-16 hover:bg-blue-900/10 cursor-pointer transition-colors relative"
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

    {{-- =================== CREATE MODAL =================== --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:click.self="$set('showCreateModal', false)">
        <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 border border-gray-700">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white">New Appointment</h3>
                <button wire:click="$set('showCreateModal', false)" class="p-1 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="createAppointment" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                    <input type="text" wire:model="title" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add a title" autofocus>
                    @error('title') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Start</label>
                        <input type="datetime-local" wire:model="startsAt" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                        @error('startsAt') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">End</label>
                        <input type="datetime-local" wire:model="endsAt" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                        @error('endsAt') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Type</label>
                        <select wire:model="appointmentType" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                            <option value="consultation">Consultation</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="treatment">Treatment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Patient</label>
                        <select wire:model="patientId" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="color-scheme: dark;">
                            <option value="">None</option>
                            @foreach($this->patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Notes</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2.5 text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add description..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white rounded-lg hover:bg-gray-700 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
