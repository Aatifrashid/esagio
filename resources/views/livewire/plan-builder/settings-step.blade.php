<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-clinical">Settings</h2>
        <p class="text-gray-500 text-sm mt-1">Configure currency, validity, deposit requirements, and access settings for this plan.</p>
    </div>

    <div class="space-y-5">

        {{-- Plan settings --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Plan settings</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Currency</label>
                    <select
                        wire:model="currency"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                    >
                        <option value="GBP">GBP - British Pound</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="USD">USD - US Dollar</option>
                        <option value="TRY">TRY - Turkish Lira</option>
                        <option value="AED">AED - UAE Dirham</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Language</label>
                    <select
                        wire:model="language"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                    >
                        <option value="en">English</option>
                        <option value="tr">Turkish</option>
                        <option value="ar">Arabic</option>
                        <option value="de">German</option>
                        <option value="fr">French</option>
                        <option value="es">Spanish</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Valid until</label>
                    <input
                        wire:model="validUntil"
                        type="date"
                        min="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                    />
                </div>
            </div>
        </div>

        {{-- Deposit settings --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Deposit</h3>
            <p class="text-xs text-gray-400 mb-4">Set a deposit amount either as a fixed value or percentage of the total.</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Percentage (%)</label>
                    <input
                        wire:model="depositPercent"
                        type="number"
                        min="0"
                        max="100"
                        step="1"
                        placeholder="e.g. 20"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                    />
                    @error('depositPercent') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Fixed amount</label>
                    <input
                        wire:model="depositFixed"
                        type="number"
                        min="0"
                        step="0.01"
                        placeholder="e.g. 500"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                    />
                    @error('depositFixed') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Access settings --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Access settings</h3>

            <div class="space-y-3 mb-4">
                @foreach(['open' => ['Open access', 'Anyone with the link can view the plan.'], 'password' => ['Password protected', 'Require a password to view the plan.'], 'expiring' => ['Expiring link', 'Link expires on the validity date above.']] as $type => [$label, $description])
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input
                            wire:model="accessType"
                            type="radio"
                            value="{{ $type }}"
                            class="mt-0.5 text-clinical focus:ring-clinical border-gray-300"
                        />
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $label }}</p>
                            <p class="text-xs text-gray-400">{{ $description }}</p>
                        </div>
                    </label>
                @endforeach
            </div>

            @if($accessType === 'password')
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Plan password</label>
                    <input
                        wire:model="planPassword"
                        type="text"
                        placeholder="Set a password for the patient to use..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical transition"
                    />
                </div>
            @endif
        </div>

        {{-- Internal notes --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Internal notes</h3>
            <p class="text-xs text-gray-400 mb-3">These notes are only visible to your team and are never shown to the patient.</p>
            <textarea
                wire:model.blur="internalNotes"
                rows="4"
                placeholder="Add any internal notes, reminders, or context for the team..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-clinical/20 focus:border-clinical resize-none transition"
            ></textarea>
        </div>

        <button
            wire:click="saveSettings"
            class="w-full bg-clinical hover:bg-clinical-700 text-white text-sm font-medium rounded-lg px-4 py-3 transition flex items-center justify-center gap-2"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save settings
        </button>

    </div>

</div>
