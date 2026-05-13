<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Patient</h2>
        <p class="text-gray-500 text-sm mt-1">Search for an existing patient or create a new one to attach to this plan.</p>
    </div>

    {{-- Selected patient card --}}
    @if($selectedPatient)
        <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-clinical/10 flex items-center justify-center text-clinical font-bold text-lg">
                {{ strtoupper(substr($selectedPatient->first_name, 0, 1) . substr($selectedPatient->last_name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">{{ $selectedPatient->first_name }} {{ $selectedPatient->last_name }}</p>
                <div class="flex items-center gap-3 mt-0.5 text-sm text-gray-500 flex-wrap">
                    @if($selectedPatient->email)
                        <span>{{ $selectedPatient->email }}</span>
                    @endif
                    @if($selectedPatient->phone)
                        <span>{{ $selectedPatient->phone }}</span>
                    @endif
                    @if($selectedPatient->reference_code)
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded font-mono">{{ $selectedPatient->reference_code }}</span>
                    @endif
                </div>
            </div>
            <button
                wire:click="removePatient"
                class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50"
                title="Remove patient"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if(!$selectedPatient)
        {{-- Search --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4" x-data="{ open: false }">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search patients</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="searchTerm"
                    type="text"
                    placeholder="Search by name, email or phone..."
                    x-on:focus="open = true"
                    x-on:click.outside="open = false"
                    class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                />
            </div>

            {{-- Results dropdown --}}
            @if(count($patients) > 0)
                <div class="mt-2 border border-gray-100 rounded-lg overflow-hidden shadow-sm">
                    @foreach($patients as $patient)
                        <button
                            wire:click="selectPatient({{ $patient['id'] }})"
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0 transition text-left"
                        >
                            <div class="w-8 h-8 rounded-full bg-clinical/10 flex items-center justify-center text-clinical text-xs font-bold flex-none">
                                {{ strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</p>
                                <p class="text-xs text-gray-400 truncate">
                                    {{ $patient['email'] }}
                                    @if($patient['phone']) &bull; {{ $patient['phone'] }} @endif
                                </p>
                            </div>
                            @if($patient['reference_code'])
                                <span class="ml-auto text-xs font-mono bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">{{ $patient['reference_code'] }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            @elseif(strlen($searchTerm) >= 2)
                <div class="mt-2 px-4 py-3 text-sm text-gray-400 text-center border border-gray-100 rounded-lg">
                    No patients found matching "{{ $searchTerm }}"
                </div>
            @endif
        </div>

        {{-- Create new patient toggle --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-1 h-px bg-gray-100"></div>
            <span class="text-xs text-gray-400 flex-none">or</span>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>

        <button
            wire:click="$toggle('showNewPatientForm')"
            class="w-full border-2 border-dashed border-gray-200 hover:border-clinical/40 hover:bg-clinical/5 rounded-xl py-4 flex items-center justify-center gap-2 text-sm font-medium text-gray-500 hover:text-clinical transition"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create new patient
        </button>

        {{-- Inline new patient form --}}
        @if($showNewPatientForm)
            <div class="mt-4 bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">New patient details</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">First name <span class="text-red-500">*</span></label>
                        <input
                            wire:model="newFirstName"
                            type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                        />
                        @error('newFirstName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Last name <span class="text-red-500">*</span></label>
                        <input
                            wire:model="newLastName"
                            type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                        />
                        @error('newLastName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                        <input
                            wire:model="newEmail"
                            type="email"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                        />
                        @error('newEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Phone</label>
                        <input
                            wire:model="newPhone"
                            type="tel"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                        />
                    </div>
                </div>

                <div class="flex gap-3 mt-5">
                    <button
                        wire:click="createPatient"
                        class="flex-1 bg-clinical hover:bg-clinical-700 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition"
                    >
                        Create and select patient
                    </button>
                    <button
                        wire:click="$set('showNewPatientForm', false)"
                        class="border border-gray-200 text-gray-600 text-sm rounded-lg px-4 py-2.5 hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        @endif
    @endif

</div>
