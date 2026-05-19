<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-900">
    {{-- Header bar --}}
    <div class="flex items-center justify-between px-6 py-3 border-b border-gray-700">
        <div class="flex items-center gap-3">
            {{-- Today button --}}
            <button wire:click="goToToday" class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                Today
            </button>

            {{-- Prev/Next arrows --}}
            <button wire:click="previousPeriod" class="p-1.5 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="nextPeriod" class="p-1.5 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Period label --}}
            <h2 class="text-lg font-semibold text-white">{{ $this->headerLabel }}</h2>
        </div>

        <div class="flex items-center gap-3">
            {{-- View mode toggle --}}
            <div class="flex bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                @foreach(['month' => 'Month', 'week' => 'Week', 'day' => 'Day'] as $mode => $label)
                    <button wire:click="setViewMode('{{ $mode }}')"
                        class="px-3 py-1.5 text-sm transition-colors {{ $viewMode === $mode ? 'bg-gray-600 text-white' : 'text-gray-400 hover:text-white' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- New button --}}
            <button wire:click="openCreateModal" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New
            </button>
        </div>
    </div>

    {{-- Month View --}}
    @if($viewMode === 'month')
    <div class="flex-1 overflow-hidden flex flex-col">
        {{-- Day headers --}}
        <div class="grid grid-cols-7 border-b border-gray-700">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="px-3 py-2 text-xs font-medium text-gray-400 text-center uppercase">{{ $day }}</div>
            @endforeach
        </div>

        {{-- Calendar days grid --}}
        <div class="grid grid-cols-7 flex-1 auto-rows-fr">
            @foreach($this->getCalendarDays() as $day)
                <div
                    wire:click="openCreateModal('{{ $day['date'] }}')"
                    class="border-b border-r border-gray-800 p-1 min-h-[100px] cursor-pointer hover:bg-gray-800/50 transition-colors
                        {{ $day['isCurrentMonth'] ? '' : 'opacity-40' }}
                        {{ $day['isToday'] ? 'bg-blue-900/20' : '' }}"
                >
                    <div class="flex justify-start px-1">
                        @if($day['isToday'])
                            <span class="text-sm bg-blue-600 text-white w-7 h-7 rounded-full flex items-center justify-center font-medium">
                                {{ $day['dayNumber'] }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400 w-7 h-7 flex items-center justify-center">
                                {{ $day['dayNumber'] }}
                            </span>
                        @endif
                    </div>

                    {{-- Appointments for this day --}}
                    <div class="space-y-0.5 mt-0.5">
                        @foreach($day['appointments'] as $appt)
                            <div class="px-1.5 py-0.5 rounded text-xs truncate"
                                 style="background-color: {{ $appt['colour'] ?? '#3B82F6' }}30; color: {{ $appt['colour'] ?? '#3B82F6' }}">
                                {{ \Carbon\Carbon::parse($appt['starts_at'])->format('H:i') }} {{ $appt['title'] }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Week View --}}
    @elseif($viewMode === 'week')
    <div class="flex-1 overflow-auto">
        <div class="grid grid-cols-8 min-h-full">
            {{-- Time column --}}
            <div class="border-r border-gray-800">
                <div class="sticky top-0 z-10 bg-gray-900 border-b border-gray-700 px-2 py-2 h-[52px]"></div>
                @for($h = 0; $h < 24; $h++)
                    <div class="h-16 border-b border-gray-800 px-2 py-1 text-xs text-gray-500 text-right">
                        {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                    </div>
                @endfor
            </div>
            {{-- 7 day columns --}}
            @foreach($this->getWeekDays() as $day)
                <div class="border-r border-gray-800 relative">
                    <div class="sticky top-0 z-10 bg-gray-900 border-b border-gray-700 px-2 py-2 text-center">
                        <div class="text-xs text-gray-400">{{ $day['dayName'] }}</div>
                        @if($day['isToday'])
                            <div class="text-sm bg-blue-600 text-white w-7 h-7 rounded-full flex items-center justify-center mx-auto font-medium">{{ $day['dayNumber'] }}</div>
                        @else
                            <div class="text-sm text-white">{{ $day['dayNumber'] }}</div>
                        @endif
                    </div>
                    @for($h = 0; $h < 24; $h++)
                        <div class="h-16 border-b border-gray-800 hover:bg-gray-800/30 cursor-pointer"
                             wire:click="openCreateModal('{{ $day['date'] }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>

    {{-- Day View --}}
    @else
    <div class="flex-1 overflow-auto max-w-3xl mx-auto w-full">
        <div class="px-4 py-2">
            @for($h = 0; $h < 24; $h++)
                <div class="flex h-16 border-b border-gray-800">
                    <div class="w-16 text-xs text-gray-500 text-right pr-3 pt-1 shrink-0">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00</div>
                    <div class="flex-1 border-l border-gray-800 pl-3 hover:bg-gray-800/30 cursor-pointer transition-colors"
                         wire:click="openCreateModal('{{ Carbon\Carbon::parse($currentDateString)->format('Y-m-d') }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
                    </div>
                </div>
            @endfor
        </div>
    </div>
    @endif

    {{-- Create Appointment Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showCreateModal', false)">
        <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 border border-gray-700">
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white">New Appointment</h3>
                <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="createAppointment" class="p-4 space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Title</label>
                    <input type="text" wire:model="title" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="Appointment title">
                    @error('title') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Start</label>
                        <input type="datetime-local" wire:model="startsAt" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                        @error('startsAt') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">End</label>
                        <input type="datetime-local" wire:model="endsAt" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                        @error('endsAt') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Type</label>
                    <select wire:model="appointmentType" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="consultation">Consultation</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="treatment">Treatment</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Patient (optional)</label>
                    <select wire:model="patientId" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select patient...</option>
                        @foreach($this->patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Description</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional notes..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Create</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
