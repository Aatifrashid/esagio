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
                                {{ $day['isToday'] ? '' : '' }}
                                hover:bg-gray-800"
                            style="{{ !$day['isCurrentMonth'] ? 'background-color: rgba(31,41,55,0.5);' : 'background-color: #111827;' }}{{ $day['isToday'] ? 'background-color: rgba(30,58,138,0.15); box-shadow: inset 0 0 0 1px rgba(59,130,246,0.4);' : '' }}"
                        >
                            {{-- Day number --}}
                            @if($day['isToday'])
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shrink-0" style="background-color: #2563EB;">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-medium shrink-0" style="color: {{ $day['isCurrentMonth'] ? '#E5E7EB' : '#4B5563' }};">
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
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" wire:click.self="$set('showCreateModal', false)">
        <div class="w-full max-w-md mx-4" style="background-color: #1F2937; border: 1px solid #374151; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); overflow: hidden;">
            {{-- Header --}}
            <div class="flex items-center justify-between" style="padding: 20px 24px 16px; border-bottom: 1px solid #374151;">
                <h3 style="font-size: 18px; font-weight: 600; color: #F9FAFB; margin: 0;">New Appointment</h3>
                <button wire:click="$set('showCreateModal', false)" style="padding: 6px; border-radius: 6px; color: #9CA3AF; background: none; border: none; cursor: pointer; display: flex;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#9CA3AF'">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit="createAppointment" style="padding: 24px;">
                {{-- Title --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #D1D5DB; margin-bottom: 6px;">Title</label>
                    <input type="text" wire:model="title" placeholder="Add a title" autofocus
                        style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 10px; padding: 10px 14px; font-size: 14px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box;"
                        onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'" onblur="this.style.borderColor='#4B5563'; this.style.boxShadow='none'">
                    @error('title') <span style="font-size: 12px; color: #F87171; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- Start / End --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; overflow: hidden;">
                    <div style="min-width: 0;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #D1D5DB; margin-bottom: 6px;">Start</label>
                        <input type="datetime-local" wire:model="startsAt"
                            style="width: 100%; max-width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 10px; padding: 10px 10px; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'" onblur="this.style.borderColor='#4B5563'; this.style.boxShadow='none'">
                        @error('startsAt') <span style="font-size: 12px; color: #F87171; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div style="min-width: 0;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #D1D5DB; margin-bottom: 6px;">End</label>
                        <input type="datetime-local" wire:model="endsAt"
                            style="width: 100%; max-width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 10px; padding: 10px 10px; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'" onblur="this.style.borderColor='#4B5563'; this.style.boxShadow='none'">
                        @error('endsAt') <span style="font-size: 12px; color: #F87171; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Type --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #D1D5DB; margin-bottom: 6px;">Type</label>
                    <select wire:model="appointmentType"
                        style="width: 100%; max-width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 10px; padding: 10px 10px; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box; appearance: auto;">
                        <option value="consultation">Consultation</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="treatment">Treatment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Patient search --}}
                <div style="margin-bottom: 20px; position: relative;"
                     x-data="{
                        open: false,
                        search: @entangle('patientSearch'),
                        selectedName: '{{ $patientId ? optional($this->patients->firstWhere("id", $patientId))->first_name . " " . optional($this->patients->firstWhere("id", $patientId))->last_name : "" }}',
                     }"
                     x-on:click.away="open = false">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #D1D5DB; margin-bottom: 6px;">Patient</label>
                    <div style="position: relative;">
                        <input type="text"
                            x-model="search"
                            x-on:focus="open = true"
                            x-on:input="open = true; selectedName = ''"
                            wire:model.live.debounce.300ms="patientSearch"
                            placeholder="Search patients..."
                            style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 10px; padding: 10px 36px 10px 10px; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'" onblur="this.style.borderColor='#4B5563'; this.style.boxShadow='none'"
                            x-bind:value="selectedName || search">
                        {{-- Search icon --}}
                        <svg style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;" width="16" height="16" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{-- Clear button --}}
                        @if($patientId)
                            <button type="button" wire:click="$set('patientId', null)" x-on:click="search = ''; selectedName = ''; open = false"
                                style="position: absolute; right: 32px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9CA3AF; cursor: pointer; padding: 2px; display: flex;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>
                    {{-- Dropdown results --}}
                    <div x-show="open && search.length >= 1" x-cloak
                        style="position: absolute; left: 0; right: 0; top: 100%; margin-top: 4px; background-color: #1F2937; border: 1px solid #374151; border-radius: 10px; max-height: 200px; overflow-y: auto; z-index: 60; box-shadow: 0 10px 25px rgba(0,0,0,0.4);">
                        @forelse($this->patientResults as $patient)
                            <div wire:key="patient-{{ $patient->id }}"
                                wire:click="selectPatient({{ $patient->id }}, '{{ addslashes($patient->first_name . ' ' . $patient->last_name) }}')"
                                x-on:click="selectedName = '{{ addslashes($patient->first_name . ' ' . $patient->last_name) }}'; search = ''; open = false"
                                style="padding: 10px 12px; cursor: pointer; font-size: 13px; color: #E5E7EB; border-bottom: 1px solid #374151;"
                                onmouseover="this.style.backgroundColor='#374151'" onmouseout="this.style.backgroundColor='transparent'">
                                <span style="font-weight: 500;">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                                @if($patient->email)
                                    <span style="color: #6B7280; font-size: 12px; margin-left: 8px;">{{ $patient->email }}</span>
                                @endif
                            </div>
                        @empty
                            <div style="padding: 12px; font-size: 13px; color: #6B7280; text-align: center;">No patients found</div>
                        @endforelse
                    </div>
                </div>

                {{-- Notes --}}
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #D1D5DB; margin-bottom: 6px;">Notes</label>
                    <textarea wire:model="description" rows="3" placeholder="Add description..."
                        style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 10px; padding: 10px 14px; font-size: 14px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box; resize: vertical;"
                        onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.3)'" onblur="this.style.borderColor='#4B5563'; this.style.boxShadow='none'"></textarea>
                </div>

                {{-- Actions --}}
                <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 4px;">
                    <button type="button" wire:click="$set('showCreateModal', false)"
                        style="padding: 10px 20px; font-size: 14px; font-weight: 500; color: #9CA3AF; background: none; border: 1px solid #4B5563; border-radius: 10px; cursor: pointer;"
                        onmouseover="this.style.color='#fff'; this.style.borderColor='#6B7280'" onmouseout="this.style.color='#9CA3AF'; this.style.borderColor='#4B5563'">
                        Cancel
                    </button>
                    <button type="submit"
                        style="padding: 10px 24px; font-size: 14px; font-weight: 500; color: #fff; background-color: #2563EB; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.3);"
                        onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
