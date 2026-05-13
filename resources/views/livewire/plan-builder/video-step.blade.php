<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Video Consultation</h2>
        <p class="text-gray-500 text-sm mt-1">Schedule a video call to walk the patient through their treatment plan.</p>
    </div>

    @if($existingSession)
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 flex items-start gap-3">
            <svg class="h-5 w-5 text-green-500 flex-none mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-green-800">Consultation scheduled</p>
                <p class="text-sm text-green-600 mt-0.5">
                    {{ $existingSession->scheduled_for?->format('l, j F Y \a\t H:i') }}
                    ({{ $existingSession->duration_minutes }} minutes)
                </p>
                <p class="text-xs text-green-500 mt-1 font-medium">{{ ucfirst($existingSession->status) }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-5">
            {{ $existingSession ? 'Update consultation' : 'Schedule consultation' }}
        </h3>

        <div class="grid grid-cols-2 gap-5">

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Date <span class="text-red-500">*</span></label>
                <input
                    wire:model="scheduledDate"
                    type="date"
                    min="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                />
                @error('scheduledDate') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Time <span class="text-red-500">*</span></label>
                <input
                    wire:model="scheduledTime"
                    type="time"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                />
                @error('scheduledTime') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Duration <span class="text-red-500">*</span></label>
                <select
                    wire:model="duration"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                >
                    <option value="15">15 minutes</option>
                    <option value="30">30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60">60 minutes</option>
                    <option value="90">90 minutes</option>
                </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Conducted by</label>
                <select
                    wire:model="conductedBy"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                >
                    @foreach($teamMembers as $member)
                        <option value="{{ $member->user_id ?? $member->id }}">
                            {{ $member->user?->name ?? 'Team member' }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-6 pt-5 border-t border-gray-100 flex gap-3">
            <button
                wire:click="scheduleConsultation"
                class="flex-1 bg-clinical hover:bg-clinical-700 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition flex items-center justify-center gap-2"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82V15.18a1 1 0 01-1.447.89L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                {{ $existingSession ? 'Update consultation' : 'Schedule consultation' }}
            </button>
        </div>
    </div>

    <div class="mt-4 bg-clinical/5 border border-clinical/10 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 text-clinical flex-none mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-clinical">How it works</p>
                <p class="text-sm text-clinical/70 mt-1">
                    Once scheduled, the patient receives a link in their plan to join the video call. The consultation room is created automatically via Daily.co.
                </p>
            </div>
        </div>
    </div>

</div>
