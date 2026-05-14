<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold font-['Fraunces'] text-[#0A2540]">Set Up Your Clinic</h1>
                    <p class="text-sm text-gray-500 mt-1">Step {{ $currentStep }} of {{ $totalSteps }}: {{ $stepLabels[$currentStep] }}</p>
                </div>
                <img src="{{ asset('svg/logo.svg') }}" alt="Esagio" class="h-7">
            </div>

            {{-- Progress bar --}}
            <div class="flex items-center gap-2">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <button
                        wire:click="goToStep({{ $i }})"
                        @if($i > $currentStep) disabled @endif
                        class="flex-1 h-2 rounded-full transition-all {{ $i <= $currentStep ? 'bg-[#E8663D]' : 'bg-gray-200' }} {{ $i <= $currentStep ? 'cursor-pointer' : 'cursor-default' }}"
                        title="{{ $stepLabels[$i] }}"
                    ></button>
                @endfor
            </div>

            {{-- Step labels --}}
            <div class="flex justify-between mt-2">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <span class="text-xs {{ $i === $currentStep ? 'text-[#E8663D] font-semibold' : 'text-gray-400' }}">
                        {{ $stepLabels[$i] }}
                    </span>
                @endfor
            </div>
        </div>
    </div>

    {{-- Step content --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">

            {{-- Step 1: Clinic Profile --}}
            @if($currentStep === 1)
                <h2 class="text-xl font-semibold font-['Fraunces'] text-[#0A2540] mb-2">Clinic Profile</h2>
                <p class="text-sm text-gray-500 mb-6">Tell us about your clinic so we can personalise your experience.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="clinicName" class="block text-sm font-medium text-gray-700 mb-1">Clinic Name *</label>
                        <input type="text" wire:model="clinicName" id="clinicName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="e.g. Bright Smile Dental">
                        @error('clinicName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="clinicEmail" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" wire:model="clinicEmail" id="clinicEmail" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="hello@clinic.com">
                        @error('clinicEmail') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="clinicPhone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                        <input type="text" wire:model="clinicPhone" id="clinicPhone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="+44 20 1234 5678">
                        @error('clinicPhone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="clinicCountry" class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                        <select wire:model="clinicCountry" id="clinicCountry" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]">
                            <option value="GB">United Kingdom</option>
                            <option value="TR">Turkey</option>
                            <option value="ES">Spain</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="US">United States</option>
                            <option value="AE">United Arab Emirates</option>
                        </select>
                    </div>
                    <div>
                        <label for="clinicTimezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone *</label>
                        <select wire:model="clinicTimezone" id="clinicTimezone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]">
                            <option value="Europe/London">Europe/London (GMT)</option>
                            <option value="Europe/Istanbul">Europe/Istanbul (GMT+3)</option>
                            <option value="Europe/Madrid">Europe/Madrid (CET)</option>
                            <option value="Europe/Berlin">Europe/Berlin (CET)</option>
                            <option value="Europe/Paris">Europe/Paris (CET)</option>
                            <option value="America/New_York">America/New York (EST)</option>
                            <option value="Asia/Dubai">Asia/Dubai (GST)</option>
                        </select>
                    </div>
                    <div>
                        <label for="clinicCurrency" class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                        <select wire:model="clinicCurrency" id="clinicCurrency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]">
                            <option value="GBP">GBP (&pound;)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (&euro;)</option>
                            <option value="TRY">TRY (&pound;)</option>
                            <option value="AED">AED (AED)</option>
                        </select>
                    </div>
                    <div>
                        <label for="clinicLanguage" class="block text-sm font-medium text-gray-700 mb-1">Language *</label>
                        <select wire:model="clinicLanguage" id="clinicLanguage" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]">
                            <option value="en">English</option>
                            <option value="tr">Turkish</option>
                            <option value="es">Spanish</option>
                            <option value="de">German</option>
                            <option value="fr">French</option>
                        </select>
                    </div>
                </div>

            {{-- Step 2: Branding --}}
            @elseif($currentStep === 2)
                <h2 class="text-xl font-semibold font-['Fraunces'] text-[#0A2540] mb-2">Branding</h2>
                <p class="text-sm text-gray-500 mb-6">Customise how your treatment plans look to patients. You can change these later.</p>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Clinic Logo</label>
                        <div class="flex items-center gap-4">
                            @if($logoUpload)
                                <img src="{{ $logoUpload->temporaryUrl() }}" class="w-16 h-16 object-contain rounded-lg border border-gray-200" alt="Logo preview">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            <div>
                                <input type="file" wire:model="logoUpload" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#0A2540] file:text-white hover:file:bg-[#0A2540]/90">
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG or SVG. Max 2MB.</p>
                            </div>
                        </div>
                        @error('logoUpload') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="primaryColour" class="block text-sm font-medium text-gray-700 mb-1">Primary Colour</label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model="primaryColour" id="primaryColour" class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                                <input type="text" wire:model="primaryColour" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D] text-sm" placeholder="#0A2540">
                            </div>
                        </div>
                        <div>
                            <label for="accentColour" class="block text-sm font-medium text-gray-700 mb-1">Accent Colour</label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model="accentColour" id="accentColour" class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                                <input type="text" wire:model="accentColour" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D] text-sm" placeholder="#E8663D">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="fontChoice" class="block text-sm font-medium text-gray-700 mb-1">Font Family</label>
                        <select wire:model="fontChoice" id="fontChoice" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]">
                            <option value="Inter">Inter (Default)</option>
                            <option value="Poppins">Poppins</option>
                            <option value="DM Sans">DM Sans</option>
                            <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                            <option value="Outfit">Outfit</option>
                        </select>
                    </div>
                </div>

            {{-- Step 3: Team Members --}}
            @elseif($currentStep === 3)
                <h2 class="text-xl font-semibold font-['Fraunces'] text-[#0A2540] mb-2">Team Members</h2>
                <p class="text-sm text-gray-500 mb-6">Add your dentists and team. They will appear on treatment plans.</p>

                <div class="space-y-4">
                    @foreach($teamMembers as $index => $member)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">Team Member {{ $index + 1 }}</span>
                                <button wire:click="removeTeamMember({{ $index }})" class="text-sm text-red-600 hover:text-red-700">Remove</button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <input type="text" wire:model="teamMembers.{{ $index }}.first_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D] text-sm" placeholder="First name">
                                </div>
                                <div>
                                    <input type="text" wire:model="teamMembers.{{ $index }}.last_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D] text-sm" placeholder="Last name">
                                </div>
                                <div>
                                    <select wire:model="teamMembers.{{ $index }}.role" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D] text-sm">
                                        <option value="dentist">Dentist</option>
                                        <option value="hygienist">Hygienist</option>
                                        <option value="orthodontist">Orthodontist</option>
                                        <option value="surgeon">Oral Surgeon</option>
                                        <option value="receptionist">Receptionist</option>
                                        <option value="manager">Practice Manager</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(count($teamMembers) < 3)
                        <button wire:click="addTeamMember" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-500 hover:border-[#E8663D] hover:text-[#E8663D] transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            Add Team Member
                        </button>
                    @endif
                </div>

            {{-- Step 4: First Template --}}
            @elseif($currentStep === 4)
                <h2 class="text-xl font-semibold font-['Fraunces'] text-[#0A2540] mb-2">Your First Template</h2>
                <p class="text-sm text-gray-500 mb-6">Choose a system template or create your own. Templates speed up plan creation.</p>

                @if(count($systemTemplates) > 0)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">System Templates</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($systemTemplates as $id => $name)
                                <button
                                    wire:click="$set('selectedSystemTemplate', {{ $id }})"
                                    class="text-left p-4 border-2 rounded-lg transition {{ $selectedSystemTemplate === $id ? 'border-[#E8663D] bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}"
                                >
                                    <span class="text-sm font-medium text-gray-900">{{ $name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-6">
                    <label for="templateName" class="block text-sm font-medium text-gray-700 mb-1">Or Create a Custom Template</label>
                    <input type="text" wire:model="templateName" id="templateName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="e.g. Implant Treatment Plan">
                    <p class="text-xs text-gray-400 mt-1">You can add treatments and details later in the dashboard.</p>
                </div>

            {{-- Step 5: First Patient --}}
            @elseif($currentStep === 5)
                <h2 class="text-xl font-semibold font-['Fraunces'] text-[#0A2540] mb-2">Add Your First Patient</h2>
                <p class="text-sm text-gray-500 mb-6">Add a patient to start building their treatment plan straight away.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="patientFirstName" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" wire:model="patientFirstName" id="patientFirstName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="John">
                        @error('patientFirstName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="patientLastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" wire:model="patientLastName" id="patientLastName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="Smith">
                        @error('patientLastName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="patientEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="patientEmail" id="patientEmail" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="john@example.com">
                        @error('patientEmail') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="patientPhone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" wire:model="patientPhone" id="patientPhone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]" placeholder="+44 7700 900000">
                        @error('patientPhone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="patientDob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" wire:model="patientDob" id="patientDob" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#E8663D] focus:ring-[#E8663D]">
                        @error('patientDob') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif
        </div>

        {{-- Navigation buttons --}}
        <div class="flex items-center justify-between mt-6">
            <div>
                @if($currentStep > 1)
                    <button wire:click="previousStep" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        Back
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-3">
                @if(in_array($currentStep, $optionalSteps))
                    <button wire:click="skipStep" class="text-sm text-gray-500 hover:text-gray-700 transition">
                        Skip this step
                    </button>
                @endif

                @if($currentStep < $totalSteps)
                    <button wire:click="nextStep" class="inline-flex items-center px-6 py-2.5 bg-[#E8663D] text-white font-semibold rounded-lg hover:bg-[#E8663D]/90 transition text-sm">
                        Continue
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                @else
                    <button wire:click="completeOnboarding" class="inline-flex items-center px-6 py-2.5 bg-[#0A2540] text-white font-semibold rounded-lg hover:bg-[#0A2540]/90 transition text-sm">
                        Complete Setup
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
