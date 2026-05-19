<x-filament-panels::page>
    <style>
        header.fi-header { display: none !important; }
        .fi-page-content-ctn { max-width: 100% !important; }
    </style>

    <div style="display: flex; gap: 24px; min-height: calc(100vh - 80px);">

        {{-- LEFT SIDEBAR — Contact Info --}}
        <div style="width: 340px; min-width: 340px; display: flex; flex-direction: column; gap: 16px;">

            {{-- Profile Header Card --}}
            <div style="background-color: #1F2937; border-radius: 12px; padding: 24px; border: 1px solid #374151;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div style="background-color: #3B82F6; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; color: #fff; flex-shrink: 0;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 700; color: #F9FAFB; margin: 0;">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                        <p style="font-size: 13px; color: #9CA3AF; margin: 4px 0 0;">{{ $patient->reference_code }}</p>
                    </div>
                </div>

                {{-- Quick action buttons --}}
                <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                    @if($patient->phone)
                    <a href="tel:{{ $patient->phone }}" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px; background-color: #374151; border-radius: 8px; color: #D1D5DB; font-size: 12px; text-decoration: none; border: 1px solid #4B5563;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#374151'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Call
                    </a>
                    @endif
                    @if($patient->email)
                    <a href="mailto:{{ $patient->email }}" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px; background-color: #374151; border-radius: 8px; color: #D1D5DB; font-size: 12px; text-decoration: none; border: 1px solid #4B5563;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#374151'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email
                    </a>
                    @endif
                    @if($patient->whatsapp_number)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $patient->whatsapp_number) }}" target="_blank" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px; background-color: #374151; border-radius: 8px; color: #D1D5DB; font-size: 12px; text-decoration: none; border: 1px solid #4B5563;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#374151'">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.136.558 4.143 1.534 5.883L.053 23.71l5.964-1.567A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82c-2.02 0-3.935-.547-5.591-1.498l-.4-.238-3.538.93.944-3.452-.26-.414A9.81 9.81 0 012.18 12c0-5.424 4.396-9.82 9.82-9.82 5.424 0 9.82 4.396 9.82 9.82 0 5.424-4.396 9.82-9.82 9.82z"/></svg>
                        WhatsApp
                    </a>
                    @endif
                </div>

                {{-- Status badge --}}
                <div style="margin-bottom: 12px;">
                    @php
                        $statusColors = [
                            'new' => '#6B7280',
                            'contacted' => '#F59E0B',
                            'qualified' => '#3B82F6',
                            'proposal_sent' => '#8B5CF6',
                            'won' => '#10B981',
                            'lost' => '#EF4444',
                            'on_hold' => '#6B7280',
                        ];
                        $sc = $statusColors[$patient->status] ?? '#6B7280';
                    @endphp
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color: #fff; background-color: {{ $sc }};">
                        {{ ucwords(str_replace('_', ' ', $patient->status)) }}
                    </span>
                </div>

                {{-- Contact details --}}
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @if($patient->email)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" fill="none" stroke="#6B7280" viewBox="0 0 24 24" style="flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ $patient->email }}</span>
                    </div>
                    @endif
                    @if($patient->phone)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" fill="none" stroke="#6B7280" viewBox="0 0 24 24" style="flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ $patient->phone }}</span>
                    </div>
                    @endif
                    @if($patient->whatsapp_number)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" fill="#6B7280" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ $patient->whatsapp_number }}</span>
                    </div>
                    @endif
                    @if($patient->country_of_residence || $patient->city)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" fill="none" stroke="#6B7280" viewBox="0 0 24 24" style="flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ collect([$patient->city, $patient->country_of_residence])->filter()->implode(', ') }}</span>
                    </div>
                    @endif
                    @if($patient->date_of_birth)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" fill="none" stroke="#6B7280" viewBox="0 0 24 24" style="flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ $patient->date_of_birth->format('d M Y') }} ({{ $patient->date_of_birth->age }} yrs)</span>
                    </div>
                    @endif
                    @if($patient->gender)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" fill="none" stroke="#6B7280" viewBox="0 0 24 24" style="flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ ucfirst(str_replace('_', ' ', $patient->gender)) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Editable Details Card --}}
            <div style="background-color: #1F2937; border-radius: 12px; padding: 20px; border: 1px solid #374151;">
                <h3 style="font-size: 14px; font-weight: 600; color: #F9FAFB; margin: 0 0 16px;">Edit Details</h3>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach([
                        ['first_name', 'First Name', 'text'],
                        ['last_name', 'Last Name', 'text'],
                        ['email', 'Email', 'email'],
                        ['phone', 'Phone', 'tel'],
                        ['whatsapp_number', 'WhatsApp', 'tel'],
                        ['country_of_residence', 'Country', 'text'],
                        ['city', 'City', 'text'],
                        ['date_of_birth', 'Date of Birth', 'date'],
                    ] as [$field, $label, $type])
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">{{ $label }}</label>
                        <input type="{{ $type }}" wire:model.blur="{{ $field }}"
                            style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                    </div>
                    @endforeach

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Gender</label>
                        <select wire:model.blur="gender" style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                            <option value="">—</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="prefer_not_to_say">Prefer not to say</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Status</label>
                        <select wire:model.blur="status" style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="proposal_sent">Proposal Sent</option>
                            <option value="won">Won</option>
                            <option value="lost">Lost</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Source</label>
                        <select wire:model.blur="source" style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                            <option value="">—</option>
                            <option value="website">Website</option>
                            <option value="referral">Referral</option>
                            <option value="social_media">Social Media</option>
                            <option value="google_ads">Google Ads</option>
                            <option value="facebook_ads">Facebook Ads</option>
                            <option value="walk_in">Walk In</option>
                            <option value="email_campaign">Email Campaign</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Assigned To</label>
                        <select wire:model.blur="assigned_to" style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                            <option value="">—</option>
                            @foreach($this->getUsers() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Deal Value (£)</label>
                        <input type="number" step="0.01" wire:model.blur="deal_value"
                            style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Pipeline Stage</label>
                        <select wire:model.blur="pipeline_stage_id" style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                            <option value="">—</option>
                            @foreach($this->getStages() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Tags</label>
                        <input type="text" wire:model.blur="tags_string" placeholder="tag1, tag2, tag3"
                            style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 500; color: #9CA3AF; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Notes</label>
                        <textarea wire:model.blur="notes" rows="3"
                            style="width: 100%; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; resize: vertical; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'"></textarea>
                    </div>

                    <button wire:click="saveDetails" style="width: 100%; padding: 10px; background-color: #3B82F6; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;"
                        onmouseover="this.style.backgroundColor='#2563EB'" onmouseout="this.style.backgroundColor='#3B82F6'">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE — Stacked Sections --}}
        <div style="flex: 1; display: flex; flex-direction: column; gap: 20px; min-width: 0;">

            {{-- ACTIVITIES SECTION --}}
            <div style="background-color: #1F2937; border-radius: 12px; border: 1px solid #374151; overflow: hidden;">
                <div style="padding: 16px 20px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;">
                    <h3 style="font-size: 15px; font-weight: 600; color: #F9FAFB; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="#3B82F6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Activities
                        <span style="background-color: #374151; color: #9CA3AF; font-size: 11px; padding: 2px 8px; border-radius: 10px; font-weight: 500;">{{ $patient->activities->count() }}</span>
                    </h3>
                </div>

                {{-- Quick log form --}}
                <div style="padding: 16px 20px; border-bottom: 1px solid #374151; background-color: rgba(55,65,81,0.3);">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <select wire:model="activity_type" style="background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none;">
                            <option value="note">Note</option>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                            <option value="meeting">Meeting</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" wire:model="activity_subject" placeholder="Subject..." style="flex: 1; min-width: 200px; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                        <select wire:model="activity_outcome" style="background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none;">
                            <option value="">Outcome...</option>
                            <option value="positive">Positive</option>
                            <option value="neutral">Neutral</option>
                            <option value="negative">Negative</option>
                            <option value="no_answer">No Answer</option>
                        </select>
                        <button wire:click="logActivity" style="padding: 8px 16px; background-color: #3B82F6; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; white-space: nowrap;"
                            onmouseover="this.style.backgroundColor='#2563EB'" onmouseout="this.style.backgroundColor='#3B82F6'">
                            + Log
                        </button>
                    </div>
                    @error('activity_subject') <span style="font-size: 12px; color: #F87171; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- Activity list --}}
                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse($patient->activities->sortByDesc('occurred_at') as $activity)
                    <div style="padding: 14px 20px; border-bottom: 1px solid #374151; display: flex; align-items: flex-start; gap: 12px;"
                         onmouseover="this.style.backgroundColor='rgba(55,65,81,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                        @php
                            $typeIcons = ['call' => '#3B82F6', 'email' => '#10B981', 'meeting' => '#F59E0B', 'note' => '#6B7280', 'whatsapp' => '#25D366', 'other' => '#8B5CF6'];
                            $tc = $typeIcons[$activity->type] ?? '#6B7280';
                        @endphp
                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $tc }}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $tc }};"></div>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                                <span style="font-size: 13px; font-weight: 600; color: #F9FAFB;">{{ $activity->subject }}</span>
                                <span style="font-size: 11px; padding: 1px 8px; border-radius: 10px; background-color: {{ $tc }}20; color: {{ $tc }}; font-weight: 500;">{{ ucfirst($activity->type) }}</span>
                                @if($activity->outcome)
                                @php
                                    $outcomeColors = ['positive' => '#10B981', 'neutral' => '#6B7280', 'negative' => '#EF4444', 'no_answer' => '#F59E0B'];
                                    $oc = $outcomeColors[$activity->outcome] ?? '#6B7280';
                                @endphp
                                <span style="font-size: 11px; padding: 1px 8px; border-radius: 10px; background-color: {{ $oc }}20; color: {{ $oc }}; font-weight: 500;">{{ ucwords(str_replace('_', ' ', $activity->outcome)) }}</span>
                                @endif
                            </div>
                            <div style="font-size: 12px; color: #6B7280;">
                                {{ $activity->user?->name ?? 'System' }} &middot; {{ $activity->occurred_at?->diffForHumans() ?? $activity->created_at->diffForHumans() }}
                            </div>
                            @if($activity->description)
                            <p style="font-size: 13px; color: #9CA3AF; margin: 6px 0 0; line-height: 1.4;">{{ \Illuminate\Support\Str::limit(strip_tags($activity->description), 200) }}</p>
                            @endif
                        </div>
                        <button wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Delete this activity?" style="background: none; border: none; color: #4B5563; cursor: pointer; padding: 4px;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#4B5563'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    @empty
                    <div style="padding: 40px 20px; text-align: center; color: #6B7280; font-size: 13px;">No activities yet. Log one above.</div>
                    @endforelse
                </div>
            </div>

            {{-- TASKS SECTION --}}
            <div style="background-color: #1F2937; border-radius: 12px; border: 1px solid #374151; overflow: hidden;">
                <div style="padding: 16px 20px; border-bottom: 1px solid #374151;">
                    <h3 style="font-size: 15px; font-weight: 600; color: #F9FAFB; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="#10B981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Tasks
                        <span style="background-color: #374151; color: #9CA3AF; font-size: 11px; padding: 2px 8px; border-radius: 10px; font-weight: 500;">{{ $patient->tasks->count() }}</span>
                    </h3>
                </div>

                {{-- Quick add task --}}
                <div style="padding: 16px 20px; border-bottom: 1px solid #374151; background-color: rgba(55,65,81,0.3);">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="text" wire:model="task_title" placeholder="Task title..." style="flex: 1; min-width: 200px; background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                        <input type="date" wire:model="task_due_date" style="background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark;">
                        <select wire:model="task_priority" style="background-color: #374151; border: 1px solid #4B5563; border-radius: 8px; padding: 8px 10px; font-size: 13px; color: #F9FAFB; outline: none;">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <button wire:click="createTask" style="padding: 8px 16px; background-color: #10B981; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; white-space: nowrap;"
                            onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10B981'">
                            + Task
                        </button>
                    </div>
                    @error('task_title') <span style="font-size: 12px; color: #F87171; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- Task list --}}
                <div style="max-height: 350px; overflow-y: auto;">
                    @forelse($patient->tasks->sortBy('status')->sortBy('due_date') as $task)
                    @php
                        $priorityColors = ['low' => '#6B7280', 'medium' => '#3B82F6', 'high' => '#F59E0B', 'urgent' => '#EF4444'];
                        $pc = $priorityColors[$task->priority] ?? '#6B7280';
                        $done = $task->status === 'completed';
                    @endphp
                    <div style="padding: 12px 20px; border-bottom: 1px solid #374151; display: flex; align-items: center; gap: 12px; {{ $done ? 'opacity: 0.5;' : '' }}"
                         onmouseover="this.style.backgroundColor='rgba(55,65,81,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                        <button wire:click="toggleTaskStatus({{ $task->id }})" style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid {{ $done ? '#10B981' : '#4B5563' }}; background-color: {{ $done ? '#10B981' : 'transparent' }}; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 0;">
                            @if($done)
                            <svg width="12" height="12" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </button>
                        <div style="flex: 1; min-width: 0;">
                            <span style="font-size: 13px; font-weight: 500; color: #F9FAFB; {{ $done ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</span>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                                <span style="font-size: 11px; padding: 1px 8px; border-radius: 10px; background-color: {{ $pc }}20; color: {{ $pc }}; font-weight: 500;">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)
                                <span style="font-size: 11px; color: {{ $task->due_date->isPast() && !$done ? '#EF4444' : '#6B7280' }};">Due {{ $task->due_date->format('d M') }}</span>
                                @endif
                                @if($task->assignedUser)
                                <span style="font-size: 11px; color: #6B7280;">{{ $task->assignedUser->name }}</span>
                                @endif
                            </div>
                        </div>
                        <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete this task?" style="background: none; border: none; color: #4B5563; cursor: pointer; padding: 4px;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#4B5563'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    @empty
                    <div style="padding: 40px 20px; text-align: center; color: #6B7280; font-size: 13px;">No tasks yet. Add one above.</div>
                    @endforelse
                </div>
            </div>

            {{-- TREATMENT PLANS SECTION --}}
            <div style="background-color: #1F2937; border-radius: 12px; border: 1px solid #374151; overflow: hidden;">
                <div style="padding: 16px 20px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;">
                    <h3 style="font-size: 15px; font-weight: 600; color: #F9FAFB; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="#8B5CF6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Treatment Plans
                        <span style="background-color: #374151; color: #9CA3AF; font-size: 11px; padding: 2px 8px; border-radius: 10px; font-weight: 500;">{{ $patient->treatmentPlans->count() }}</span>
                    </h3>
                    <a href="{{ route('plan.builder', ['plan' => $patient->id]) }}" target="_blank"
                       style="padding: 6px 14px; background-color: #8B5CF6; color: #fff; border: none; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: none;"
                       onmouseover="this.style.backgroundColor='#7C3AED'" onmouseout="this.style.backgroundColor='#8B5CF6'">
                        + New Plan
                    </a>
                </div>

                @forelse($patient->treatmentPlans->sortByDesc('created_at') as $plan)
                @php
                    $planColors = ['draft' => '#6B7280', 'sent' => '#3B82F6', 'accepted' => '#10B981', 'declined' => '#EF4444', 'expired' => '#F59E0B'];
                    $plc = $planColors[$plan->status] ?? '#6B7280';
                @endphp
                <div style="padding: 14px 20px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;"
                     onmouseover="this.style.backgroundColor='rgba(55,65,81,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                    <div>
                        <div style="font-size: 13px; font-weight: 500; color: #F9FAFB;">{{ $plan->title }}</div>
                        <div style="font-size: 12px; color: #6B7280; margin-top: 2px;">
                            {{ $plan->reference_number ?? '' }} &middot; Created {{ $plan->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 11px; padding: 2px 10px; border-radius: 10px; background-color: {{ $plc }}20; color: {{ $plc }}; font-weight: 600;">{{ ucfirst($plan->status) }}</span>
                        <a href="{{ route('plan.builder', ['plan' => $plan->id]) }}" target="_blank" style="color: #6B7280; text-decoration: none;" onmouseover="this.style.color='#3B82F6'" onmouseout="this.style.color='#6B7280'">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div style="padding: 40px 20px; text-align: center; color: #6B7280; font-size: 13px;">No treatment plans yet.</div>
                @endforelse
            </div>

            {{-- CONVERSATIONS SECTION --}}
            <div style="background-color: #1F2937; border-radius: 12px; border: 1px solid #374151; overflow: hidden;">
                <div style="padding: 16px 20px; border-bottom: 1px solid #374151;">
                    <h3 style="font-size: 15px; font-weight: 600; color: #F9FAFB; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="#F59E0B" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Conversations
                        <span style="background-color: #374151; color: #9CA3AF; font-size: 11px; padding: 2px 8px; border-radius: 10px; font-weight: 500;">{{ $patient->conversations->count() }}</span>
                    </h3>
                </div>

                @forelse($patient->conversations->sortByDesc('last_message_at') as $convo)
                @php
                    $channelColors = ['whatsapp' => '#25D366', 'email' => '#3B82F6', 'sms' => '#F59E0B'];
                    $cc = $channelColors[$convo->channel] ?? '#6B7280';
                @endphp
                <div style="padding: 14px 20px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;"
                     onmouseover="this.style.backgroundColor='rgba(55,65,81,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 11px; padding: 2px 10px; border-radius: 10px; background-color: {{ $cc }}20; color: {{ $cc }}; font-weight: 600;">{{ ucfirst($convo->channel) }}</span>
                        <span style="font-size: 13px; color: #D1D5DB;">{{ $convo->channel_identifier }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 12px; color: #6B7280;">{{ $convo->messages->count() }} msgs</span>
                        <span style="font-size: 12px; color: #6B7280;">{{ $convo->last_message_at?->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div style="padding: 40px 20px; text-align: center; color: #6B7280; font-size: 13px;">No conversations yet.</div>
                @endforelse
            </div>

        </div>
    </div>
</x-filament-panels::page>
