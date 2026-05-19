<div class="flex flex-col" style="height: calc(100vh - 48px); background: #f5f5f5; font-family: 'Lexend Deca', sans-serif;">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between px-4 py-2 border-b shrink-0" style="border-color: #e5e7eb; background: #fff;">
        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors shadow-sm" style="background: #E8663D;" onmouseover="this.style.background='#d4572f'" onmouseout="this.style.background='#E8663D'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New event
            </button>

            <div class="w-px h-6 mx-1" style="background: #e5e7eb;"></div>

            @foreach(['day' => 'Day', 'week' => 'Week', 'month' => 'Month'] as $mode => $label)
                <button wire:click="setViewMode('{{ $mode }}')"
                    class="px-3 py-1.5 text-sm rounded-md transition-colors"
                    style="{{ $viewMode === $mode
                        ? 'background: rgba(232,102,61,0.1); color: #E8663D; font-weight: 500;'
                        : 'color: #6b7280;' }}"
                    onmouseover="@if($viewMode !== $mode)this.style.background='#f3f4f6'; this.style.color='#1a1a1a'@endif"
                    onmouseout="@if($viewMode !== $mode)this.style.background='transparent'; this.style.color='#6b7280'@endif">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="goToToday" class="px-3 py-1.5 text-sm font-medium rounded-md border transition-colors" style="border-color: #e5e7eb; color: #374151;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                Today
            </button>
            <button wire:click="previousPeriod" class="p-1.5 rounded-md transition-colors" style="color: #6b7280;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="nextPeriod" class="p-1.5 rounded-md transition-colors" style="color: #6b7280;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <h2 class="text-lg font-semibold ml-1" style="color: #1a1a1a;">{{ $this->headerLabel }}</h2>
        </div>
    </div>

    {{-- =================== MONTH VIEW =================== --}}
    @if($viewMode === 'month')
    <div class="flex flex-col flex-1 min-h-0">
        {{-- Day headers --}}
        <div class="grid grid-cols-7 shrink-0" style="border-bottom: 1px solid #e5e7eb; background: #fff;">
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider" style="color: #6b7280; {{ !$loop->first ? 'border-left: 1px solid #e5e7eb;' : '' }}">
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
                <div class="grid grid-cols-7 flex-1 min-h-0" style="border-bottom: 1px solid #f0f0f0;">
                    @foreach($week as $day)
                        <div
                            wire:click="openCreateModal('{{ $day['date'] }}')"
                            class="flex flex-col p-2 cursor-pointer transition-colors overflow-hidden"
                            style="{{ !$loop->first ? 'border-left: 1px solid #f0f0f0;' : '' }}{{ !$day['isCurrentMonth'] ? 'background: #fafafa;' : 'background: #fff;' }}{{ $day['isToday'] ? 'background: rgba(232,102,61,0.06); box-shadow: inset 0 0 0 1px rgba(232,102,61,0.3);' : '' }}"
                            onmouseover="@if(!$day['isToday'])this.style.background='#f9fafb'@endif"
                            onmouseout="@if(!$day['isToday'])this.style.background='{{ !$day['isCurrentMonth'] ? '#fafafa' : '#fff' }}'@endif"
                        >
                            @if($day['isToday'])
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shrink-0" style="background: #E8663D;">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-medium shrink-0" style="color: {{ $day['isCurrentMonth'] ? '#1a1a1a' : '#9ca3af' }};">
                                    {{ $day['dayNumber'] }}
                                </span>
                            @endif

                            <div class="flex flex-col gap-0.5 mt-1 min-h-0 overflow-hidden flex-1">
                                @foreach(array_slice($day['appointments'], 0, 3) as $appt)
                                    <div class="flex items-center rounded truncate shrink-0"
                                         style="font-size:8px;line-height:1.3;gap:2px;padding:1px 4px;background-color: {{ $appt['colour'] ?? '#E8663D' }}15; border-left: 2px solid {{ $appt['colour'] ?? '#E8663D' }};">
                                        <span class="shrink-0" style="font-weight:600;color: {{ $appt['colour'] ?? '#E8663D' }}">{{ \Carbon\Carbon::parse($appt['starts_at'])->format('H:i') }}</span>
                                        <span class="truncate" style="color: #374151;">{{ $appt['title'] }}</span>
                                    </div>
                                @endforeach
                                @if(count($day['appointments']) > 3)
                                    <div class="text-[10px] font-medium px-1.5 shrink-0" style="color: #E8663D;">+{{ count($day['appointments']) - 3 }} more</div>
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
        <div class="sticky top-0 z-10 grid" style="grid-template-columns: 60px repeat(7, 1fr); background: #fff; border-bottom: 1px solid #e5e7eb;">
            <div style="border-right: 1px solid #e5e7eb;"></div>
            @foreach($this->getWeekDays() as $day)
                <div class="px-2 py-2 text-center" style="border-right: 1px solid #e5e7eb;">
                    <div class="text-xs font-medium uppercase" style="color: #6b7280;">{{ $day['dayName'] }}</div>
                    @if($day['isToday'])
                        <div class="inline-flex items-center justify-center w-8 h-8 text-white rounded-full text-sm font-bold mt-0.5" style="background: #E8663D;">{{ $day['dayNumber'] }}</div>
                    @else
                        <div class="text-lg font-light mt-0.5" style="color: #374151;">{{ $day['dayNumber'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="grid" style="grid-template-columns: 60px repeat(7, 1fr); background: #fff;">
            @for($h = 0; $h < 24; $h++)
                <div class="h-14 flex items-start justify-end pr-2 pt-0.5" style="border-right: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0;">
                    <span class="text-[11px] font-medium" style="color: #9ca3af;">{{ $h === 0 ? '' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</span>
                </div>
                @foreach($this->getWeekDays() as $day)
                    <div class="h-14 cursor-pointer transition-colors relative"
                        style="border-right: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; {{ $day['isToday'] ? 'background: rgba(232,102,61,0.03);' : '' }}"
                        onmouseover="this.style.background='rgba(232,102,61,0.05)'" onmouseout="this.style.background='{{ $day['isToday'] ? 'rgba(232,102,61,0.03)' : 'transparent' }}'"
                         wire:click="openCreateModal('{{ $day['date'] }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
                        @foreach(array_filter($appointments, function($a) use ($day, $h) {
                            return \Carbon\Carbon::parse($a['starts_at'])->format('Y-m-d') === $day['date']
                                && \Carbon\Carbon::parse($a['starts_at'])->hour === $h;
                        }) as $appt)
                            <div class="absolute inset-x-0.5 top-0.5 rounded px-1 py-0.5 text-[10px] leading-tight truncate z-10"
                                 style="background-color: {{ $appt['colour'] ?? '#E8663D' }}; color: white;">
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
    <div class="flex-1 overflow-auto" style="background: #fff;">
        <div class="max-w-4xl mx-auto">
            @php $dayDate = \Carbon\Carbon::parse($currentDateString); @endphp
            <div class="px-6 py-3 sticky top-0 z-10" style="border-bottom: 1px solid #e5e7eb; background: #fff;">
                <div class="flex items-center gap-3">
                    @if($dayDate->isToday())
                        <div class="inline-flex items-center justify-center w-12 h-12 text-white rounded-full text-xl font-bold" style="background: #E8663D;">{{ $dayDate->day }}</div>
                    @else
                        <div class="inline-flex items-center justify-center w-12 h-12 text-xl font-light" style="color: #374151;">{{ $dayDate->day }}</div>
                    @endif
                    <div>
                        <div class="text-sm font-medium uppercase" style="color: #6b7280;">{{ $dayDate->format('l') }}</div>
                        <div class="text-xs" style="color: #9ca3af;">{{ $dayDate->format('F Y') }}</div>
                    </div>
                </div>
            </div>
            <div class="grid" style="grid-template-columns: 70px 1fr;">
                @for($h = 0; $h < 24; $h++)
                    <div class="h-16 flex items-start justify-end pr-3 pt-1" style="border-bottom: 1px solid #f0f0f0;">
                        <span class="text-xs font-medium" style="color: #9ca3af;">{{ $h === 0 ? '' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</span>
                    </div>
                    <div class="h-16 cursor-pointer transition-colors relative"
                         style="border-bottom: 1px solid #f0f0f0; border-left: 1px solid #f0f0f0;"
                         onmouseover="this.style.background='rgba(232,102,61,0.05)'" onmouseout="this.style.background='transparent'"
                         wire:click="openCreateModal('{{ $dayDate->format('Y-m-d') }}T{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00')">
                        @foreach(array_filter($appointments, function($a) use ($dayDate, $h) {
                            return \Carbon\Carbon::parse($a['starts_at'])->format('Y-m-d') === $dayDate->format('Y-m-d')
                                && \Carbon\Carbon::parse($a['starts_at'])->hour === $h;
                        }) as $appt)
                            <div class="absolute inset-x-1 top-0.5 rounded-md px-2 py-1 text-xs z-10 shadow-sm"
                                 style="background-color: {{ $appt['colour'] ?? '#E8663D' }}; color: white;">
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
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0,0,0,0.3); backdrop-filter: blur(4px);" wire:click.self="$set('showCreateModal', false)">
        <div class="w-full max-w-md mx-4" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); overflow: hidden;">
            {{-- Header --}}
            <div class="flex items-center justify-between" style="padding: 20px 24px 16px; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0;">New Appointment</h3>
                <button wire:click="$set('showCreateModal', false)" style="padding: 6px; border-radius: 6px; color: #9CA3AF; background: none; border: none; cursor: pointer; display: flex;" onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#9CA3AF'">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit="createAppointment" style="padding: 24px;">
                {{-- Title --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Title</label>
                    <input type="text" wire:model="title" placeholder="Add a title" autofocus
                        style="width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 14px; font-size: 14px; color: #1a1a1a; outline: none; box-sizing: border-box;"
                        onfocus="this.style.borderColor='#E8663D'; this.style.boxShadow='0 0 0 2px rgba(232,102,61,0.2)'" onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    @error('title') <span style="font-size: 12px; color: #dc2626; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- Start / End --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; overflow: hidden;">
                    <div style="min-width: 0;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Start</label>
                        <input type="datetime-local" wire:model="startsAt"
                            style="width: 100%; max-width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 10px; font-size: 13px; color: #1a1a1a; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#E8663D'; this.style.boxShadow='0 0 0 2px rgba(232,102,61,0.2)'" onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('startsAt') <span style="font-size: 12px; color: #dc2626; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div style="min-width: 0;">
                        <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">End</label>
                        <input type="datetime-local" wire:model="endsAt"
                            style="width: 100%; max-width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 10px; font-size: 13px; color: #1a1a1a; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#E8663D'; this.style.boxShadow='0 0 0 2px rgba(232,102,61,0.2)'" onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('endsAt') <span style="font-size: 12px; color: #dc2626; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Type --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Type</label>
                    <select wire:model="appointmentType"
                        style="width: 100%; max-width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 10px; font-size: 13px; color: #1a1a1a; outline: none; box-sizing: border-box; appearance: auto;">
                        <option value="consultation">Consultation</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="treatment">Treatment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Patient search --}}
                <div style="margin-bottom: 20px; position: relative;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Patient</label>
                    <div style="position: relative;">
                        @if($patientId && $patientName)
                            <div style="width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 36px 10px 10px; font-size: 13px; color: #1a1a1a; box-sizing: border-box; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ $patientName }}</span>
                                <button type="button" wire:click="clearPatient"
                                    style="background: none; border: none; color: #9CA3AF; cursor: pointer; padding: 2px; display: flex;"
                                    onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#9CA3AF'">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @else
                            <input type="text"
                                wire:model.live.debounce.300ms="patientSearch"
                                placeholder="Search patients..."
                                style="width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 36px 10px 10px; font-size: 13px; color: #1a1a1a; outline: none; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#E8663D'; this.style.boxShadow='0 0 0 2px rgba(232,102,61,0.2)'" onblur="setTimeout(() => { this.style.borderColor='#e5e7eb'; this.style.boxShadow='none' }, 200)">
                            <svg style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;" width="16" height="16" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        @endif
                    </div>
                    @if(!$patientId && strlen($patientSearch) >= 1)
                        <div style="position: absolute; left: 0; right: 0; top: 100%; margin-top: 4px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; max-height: 200px; overflow-y: auto; z-index: 60; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            @forelse($this->patientResults as $patient)
                                <div wire:key="patient-{{ $patient->id }}"
                                    wire:click="selectPatient({{ $patient->id }})"
                                    style="padding: 10px 12px; cursor: pointer; font-size: 13px; color: #374151; border-bottom: 1px solid #f0f0f0;"
                                    onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                                    <span style="font-weight: 500;">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                                    @if($patient->email)
                                        <span style="color: #9ca3af; font-size: 12px; margin-left: 8px;">{{ $patient->email }}</span>
                                    @endif
                                </div>
                            @empty
                                <div style="padding: 12px; font-size: 13px; color: #9ca3af; text-align: center;">No patients found</div>
                            @endforelse
                        </div>
                    @endif
                </div>

                {{-- Notes --}}
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Notes</label>
                    <textarea wire:model="description" rows="3" placeholder="Add description..."
                        style="width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 14px; font-size: 14px; color: #1a1a1a; outline: none; box-sizing: border-box; resize: vertical;"
                        onfocus="this.style.borderColor='#E8663D'; this.style.boxShadow='0 0 0 2px rgba(232,102,61,0.2)'" onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"></textarea>
                </div>

                {{-- Actions --}}
                <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 4px;">
                    <button type="button" wire:click="$set('showCreateModal', false)"
                        style="padding: 10px 20px; font-size: 14px; font-weight: 500; color: #6b7280; background: none; border: 1px solid #e5e7eb; border-radius: 10px; cursor: pointer;"
                        onmouseover="this.style.color='#1a1a1a'; this.style.borderColor='#d1d5db'" onmouseout="this.style.color='#6b7280'; this.style.borderColor='#e5e7eb'">
                        Cancel
                    </button>
                    <button type="submit"
                        style="padding: 10px 24px; font-size: 14px; font-weight: 500; color: #fff; background: #E8663D; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                        onmouseover="this.style.background='#d4572f'" onmouseout="this.style.background='#E8663D'">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
