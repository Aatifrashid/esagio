<x-filament-panels::page>
    <style>
        header.fi-header { display: none !important; }
        .fi-page-content-ctn { max-width: 100% !important; }
        .fi-body-content { padding: 0 !important; }
        .fi-page > .fi-page-content-ctn > div { gap: 0 !important; }
    </style>

    <div style="display: flex; height: calc(100vh - 64px); overflow: hidden; margin: -24px;">

        {{-- COLUMN 1 — Contact Details (like GHL left panel) --}}
        <div style="width: 280px; min-width: 280px; border-right: 1px solid #374151; overflow-y: auto; background-color: #111827;">

            {{-- Header with back + name --}}
            <div style="padding: 16px; border-bottom: 1px solid #374151;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <a href="{{ url('/dashboard/patients') }}" style="color: #9CA3AF; text-decoration: none; display: flex;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#9CA3AF'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <span style="font-size: 14px; font-weight: 600; color: #F9FAFB;">Contact Details</span>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="background-color: #3B82F6; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; color: #fff; flex-shrink: 0;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 15px; font-weight: 600; color: #F9FAFB;">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                        <div style="font-size: 11px; color: #6B7280;">{{ $patient->reference_code }}</div>
                    </div>
                </div>
            </div>

            {{-- Contact fields --}}
            <div style="padding: 12px 16px;">
                <div style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">Contact</div>

                @foreach([
                    ['first_name', 'First name', 'text'],
                    ['last_name', 'Last name', 'text'],
                    ['email', 'Email', 'email'],
                    ['phone', 'Phone', 'tel'],
                    ['whatsapp_number', 'WhatsApp', 'tel'],
                    ['date_of_birth', 'Date of birth', 'date'],
                ] as [$field, $label, $type])
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">{{ $label }}</label>
                    <input type="{{ $type }}" wire:model.blur="{{ $field }}"
                        style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; color-scheme: dark; box-sizing: border-box;"
                        onfocus="this.style.borderBottomColor='#3B82F6'" onblur="this.style.borderBottomColor='#374151'">
                </div>
                @endforeach

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Gender</label>
                    <select wire:model.blur="gender" style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                        <option value="" style="background: #1F2937;">--</option>
                        <option value="male" style="background: #1F2937;">Male</option>
                        <option value="female" style="background: #1F2937;">Female</option>
                        <option value="other" style="background: #1F2937;">Other</option>
                    </select>
                </div>
            </div>

            {{-- CRM Details --}}
            <div style="padding: 0 16px 12px;">
                <div style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; padding-top: 12px; border-top: 1px solid #374151;">CRM Details</div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Status</label>
                    <select wire:model.blur="status" style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                        @foreach(['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'proposal_sent' => 'Proposal Sent', 'won' => 'Won', 'lost' => 'Lost', 'on_hold' => 'On Hold'] as $val => $lbl)
                        <option value="{{ $val }}" style="background: #1F2937;">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Source</label>
                    <select wire:model.blur="source" style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                        <option value="" style="background: #1F2937;">--</option>
                        @foreach(['website' => 'Website', 'referral' => 'Referral', 'social_media' => 'Social Media', 'google_ads' => 'Google Ads', 'facebook_ads' => 'Facebook Ads', 'walk_in' => 'Walk In', 'email_campaign' => 'Email Campaign', 'other' => 'Other'] as $val => $lbl)
                        <option value="{{ $val }}" style="background: #1F2937;">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Assigned to</label>
                    <select wire:model.blur="assigned_to" style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                        <option value="" style="background: #1F2937;">--</option>
                        @foreach($this->getUsers() as $id => $name)
                        <option value="{{ $id }}" style="background: #1F2937;">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Deal value</label>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <span style="font-size: 13px; color: #6B7280;">£</span>
                        <input type="number" step="0.01" wire:model.blur="deal_value"
                            style="flex: 1; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderBottomColor='#3B82F6'" onblur="this.style.borderBottomColor='#374151'">
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Pipeline stage</label>
                    <select wire:model.blur="pipeline_stage_id" style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;">
                        <option value="" style="background: #1F2937;">--</option>
                        @foreach($this->getStages() as $id => $name)
                        <option value="{{ $id }}" style="background: #1F2937;">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Tags</label>
                    <input type="text" wire:model.blur="tags_string" placeholder="tag1, tag2..."
                        style="width: 100%; background-color: transparent; border: none; border-bottom: 1px solid #374151; padding: 4px 0; font-size: 13px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                        onfocus="this.style.borderBottomColor='#3B82F6'" onblur="this.style.borderBottomColor='#374151'">
                </div>

                @if($patient->country_of_residence || $patient->city)
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 11px; color: #6B7280; margin-bottom: 3px;">Location</label>
                    <span style="font-size: 13px; color: #D1D5DB;">{{ collect([$patient->city, $patient->country_of_residence])->filter()->implode(', ') }}</span>
                </div>
                @endif
            </div>

            {{-- Notes --}}
            <div style="padding: 0 16px 12px;">
                <div style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; padding-top: 12px; border-top: 1px solid #374151;">Notes</div>
                <textarea wire:model.blur="notes" rows="3" placeholder="Add notes..."
                    style="width: 100%; background-color: #1F2937; border: 1px solid #374151; border-radius: 6px; padding: 8px; font-size: 13px; color: #F9FAFB; outline: none; resize: vertical; box-sizing: border-box;"
                    onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#374151'"></textarea>
            </div>

            {{-- Save + Meta --}}
            <div style="padding: 0 16px 16px;">
                <button wire:click="saveDetails" style="width: 100%; padding: 8px; background-color: #3B82F6; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; margin-bottom: 12px;"
                    onmouseover="this.style.backgroundColor='#2563EB'" onmouseout="this.style.backgroundColor='#3B82F6'">
                    Save Changes
                </button>
                <div style="font-size: 11px; color: #4B5563;">
                    Created {{ $patient->created_at->format('d M Y, g:i A') }}
                </div>
            </div>
        </div>

        {{-- COLUMN 2 — Middle: Conversations + Tasks + Plans --}}
        <div style="flex: 1; min-width: 0; overflow-y: auto; display: flex; flex-direction: column; border-right: 1px solid #374151;">

            {{-- Top bar with avatar + Send Message --}}
            <div style="padding: 12px 20px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; background-color: #111827;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="background-color: #3B82F6; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #fff;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <span style="font-size: 14px; font-weight: 600; color: #F9FAFB;">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                    @php
                        $statusColors = ['new' => '#6B7280', 'contacted' => '#F59E0B', 'qualified' => '#3B82F6', 'proposal_sent' => '#8B5CF6', 'won' => '#10B981', 'lost' => '#EF4444', 'on_hold' => '#6B7280'];
                        $sc = $statusColors[$patient->status] ?? '#6B7280';
                    @endphp
                    <span style="font-size: 11px; padding: 2px 10px; border-radius: 10px; background-color: {{ $sc }}; color: #fff; font-weight: 600;">{{ ucwords(str_replace('_', ' ', $patient->status)) }}</span>
                </div>
                <a href="{{ url('/dashboard/conversations') }}" style="display: flex; align-items: center; gap: 6px; padding: 7px 14px; background-color: #3B82F6; border-radius: 6px; color: #fff; font-size: 12px; font-weight: 600; text-decoration: none;"
                   onmouseover="this.style.backgroundColor='#2563EB'" onmouseout="this.style.backgroundColor='#3B82F6'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Send Message
                </a>
            </div>

            <div style="flex: 1; overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 16px;">

                {{-- CONVERSATIONS --}}
                <div style="background-color: #1F2937; border-radius: 10px; border: 1px solid #374151; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" fill="none" stroke="#F59E0B" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span style="font-size: 13px; font-weight: 600; color: #F9FAFB;">Conversations</span>
                        <span style="font-size: 11px; padding: 1px 6px; border-radius: 8px; background-color: #374151; color: #9CA3AF;">{{ $patient->conversations->count() }}</span>
                    </div>
                    @forelse($patient->conversations->sortByDesc('last_message_at') as $convo)
                    @php $cc = ['whatsapp' => '#25D366', 'email' => '#3B82F6', 'sms' => '#F59E0B'][$convo->channel] ?? '#6B7280'; @endphp
                    <div style="padding: 10px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;" onmouseover="this.style.backgroundColor='rgba(55,65,81,0.3)'" onmouseout="this.style.backgroundColor='transparent'">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 10px; padding: 2px 8px; border-radius: 8px; background-color: {{ $cc }}20; color: {{ $cc }}; font-weight: 600;">{{ ucfirst($convo->channel) }}</span>
                            <span style="font-size: 12px; color: #D1D5DB;">{{ $convo->channel_identifier }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 11px; color: #6B7280;">{{ $convo->messages->count() }} msgs</span>
                            <span style="font-size: 11px; color: #6B7280;">{{ $convo->last_message_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 24px 16px; text-align: center; color: #4B5563; font-size: 12px;">No conversations yet</div>
                    @endforelse
                </div>

                {{-- TASKS --}}
                <div style="background-color: #1F2937; border-radius: 10px; border: 1px solid #374151; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" fill="none" stroke="#10B981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span style="font-size: 13px; font-weight: 600; color: #F9FAFB;">Tasks</span>
                        <span style="font-size: 11px; padding: 1px 6px; border-radius: 8px; background-color: #374151; color: #9CA3AF;">{{ $patient->tasks->count() }}</span>
                    </div>
                    {{-- Quick add --}}
                    <div style="padding: 10px 16px; border-bottom: 1px solid #374151; display: flex; gap: 8px; flex-wrap: wrap; background-color: rgba(55,65,81,0.2);">
                        <input type="text" wire:model="task_title" placeholder="Task title..." style="flex: 1; min-width: 150px; background-color: #374151; border: 1px solid #4B5563; border-radius: 6px; padding: 6px 8px; font-size: 12px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                            onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                        <input type="date" wire:model="task_due_date" style="background-color: #374151; border: 1px solid #4B5563; border-radius: 6px; padding: 6px 8px; font-size: 12px; color: #F9FAFB; outline: none; color-scheme: dark;">
                        <select wire:model="task_priority" style="background-color: #374151; border: 1px solid #4B5563; border-radius: 6px; padding: 6px 8px; font-size: 12px; color: #F9FAFB; outline: none;">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <button wire:click="createTask" style="padding: 6px 12px; background-color: #10B981; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;"
                            onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10B981'">+ Task</button>
                    </div>
                    @error('task_title') <div style="padding: 4px 16px; font-size: 11px; color: #F87171;">{{ $message }}</div> @enderror
                    @forelse($patient->tasks->sortBy('status')->sortBy('due_date') as $task)
                    @php
                        $pc = ['low' => '#6B7280', 'medium' => '#3B82F6', 'high' => '#F59E0B', 'urgent' => '#EF4444'][$task->priority] ?? '#6B7280';
                        $done = $task->status === 'completed';
                    @endphp
                    <div style="padding: 8px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; gap: 10px; {{ $done ? 'opacity: 0.5;' : '' }}" onmouseover="this.style.backgroundColor='rgba(55,65,81,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                        <button wire:click="toggleTaskStatus({{ $task->id }})" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid {{ $done ? '#10B981' : '#4B5563' }}; background-color: {{ $done ? '#10B981' : 'transparent' }}; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 0;">
                            @if($done)<svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                        </button>
                        <div style="flex: 1; min-width: 0;">
                            <span style="font-size: 12px; font-weight: 500; color: #F9FAFB; {{ $done ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</span>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 1px;">
                                <span style="font-size: 10px; padding: 1px 6px; border-radius: 8px; background-color: {{ $pc }}20; color: {{ $pc }}; font-weight: 500;">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)<span style="font-size: 10px; color: {{ $task->due_date->isPast() && !$done ? '#EF4444' : '#6B7280' }};">{{ $task->due_date->format('d M') }}</span>@endif
                            </div>
                        </div>
                        <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete this task?" style="background: none; border: none; color: #4B5563; cursor: pointer; padding: 2px;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#4B5563'">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @empty
                    <div style="padding: 24px 16px; text-align: center; color: #4B5563; font-size: 12px;">No tasks yet</div>
                    @endforelse
                </div>

                {{-- TREATMENT PLANS --}}
                <div style="background-color: #1F2937; border-radius: 10px; border: 1px solid #374151; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" fill="none" stroke="#8B5CF6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span style="font-size: 13px; font-weight: 600; color: #F9FAFB;">Treatment Plans</span>
                            <span style="font-size: 11px; padding: 1px 6px; border-radius: 8px; background-color: #374151; color: #9CA3AF;">{{ $patient->treatmentPlans->count() }}</span>
                        </div>
                        <a href="{{ route('plan.builder', ['plan' => $patient->id]) }}" target="_blank"
                           style="padding: 4px 10px; background-color: #8B5CF6; color: #fff; border-radius: 6px; font-size: 11px; font-weight: 600; text-decoration: none;"
                           onmouseover="this.style.backgroundColor='#7C3AED'" onmouseout="this.style.backgroundColor='#8B5CF6'">+ New</a>
                    </div>
                    @forelse($patient->treatmentPlans->sortByDesc('created_at') as $plan)
                    @php $plc = ['draft' => '#6B7280', 'sent' => '#3B82F6', 'accepted' => '#10B981', 'declined' => '#EF4444', 'expired' => '#F59E0B'][$plan->status] ?? '#6B7280'; @endphp
                    <div style="padding: 10px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;" onmouseover="this.style.backgroundColor='rgba(55,65,81,0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                        <div>
                            <div style="font-size: 12px; font-weight: 500; color: #F9FAFB;">{{ $plan->title }}</div>
                            <div style="font-size: 11px; color: #6B7280;">{{ $plan->created_at->format('d M Y') }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 10px; padding: 2px 8px; border-radius: 8px; background-color: {{ $plc }}20; color: {{ $plc }}; font-weight: 600;">{{ ucfirst($plan->status) }}</span>
                            <a href="{{ route('plan.builder', ['plan' => $plan->id]) }}" target="_blank" style="color: #6B7280;" onmouseover="this.style.color='#3B82F6'" onmouseout="this.style.color='#6B7280'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 24px 16px; text-align: center; color: #4B5563; font-size: 12px;">No treatment plans yet</div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- COLUMN 3 — Activity Timeline (like GHL right panel) --}}
        <div style="width: 300px; min-width: 300px; overflow-y: auto; background-color: #111827;">

            {{-- Header --}}
            <div style="padding: 14px 16px; border-bottom: 1px solid #374151; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 14px; font-weight: 600; color: #F9FAFB;">Activity</span>
            </div>

            {{-- Quick log form --}}
            <div style="padding: 12px 16px; border-bottom: 1px solid #374151; background-color: rgba(55,65,81,0.2);">
                <div style="display: flex; gap: 6px; margin-bottom: 6px;">
                    <select wire:model="activity_type" style="flex: 1; background-color: #374151; border: 1px solid #4B5563; border-radius: 6px; padding: 6px 8px; font-size: 11px; color: #F9FAFB; outline: none;">
                        <option value="note">Note</option>
                        <option value="call">Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <select wire:model="activity_outcome" style="flex: 1; background-color: #374151; border: 1px solid #4B5563; border-radius: 6px; padding: 6px 8px; font-size: 11px; color: #F9FAFB; outline: none;">
                        <option value="">Outcome</option>
                        <option value="positive">Positive</option>
                        <option value="neutral">Neutral</option>
                        <option value="negative">Negative</option>
                        <option value="no_answer">No Answer</option>
                    </select>
                </div>
                <div style="display: flex; gap: 6px;">
                    <input type="text" wire:model="activity_subject" placeholder="Subject..." style="flex: 1; background-color: #374151; border: 1px solid #4B5563; border-radius: 6px; padding: 6px 8px; font-size: 11px; color: #F9FAFB; outline: none; box-sizing: border-box;"
                        onfocus="this.style.borderColor='#3B82F6'" onblur="this.style.borderColor='#4B5563'">
                    <button wire:click="logActivity" style="padding: 6px 10px; background-color: #3B82F6; color: #fff; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap;"
                        onmouseover="this.style.backgroundColor='#2563EB'" onmouseout="this.style.backgroundColor='#3B82F6'">+ Log</button>
                </div>
                @error('activity_subject') <span style="font-size: 10px; color: #F87171; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Timeline --}}
            <div style="padding: 0;">
                @forelse($patient->activities->sortByDesc('occurred_at') as $activity)
                @php
                    $tc = ['call' => '#3B82F6', 'email' => '#10B981', 'meeting' => '#F59E0B', 'note' => '#6B7280', 'whatsapp' => '#25D366', 'other' => '#8B5CF6'][$activity->type] ?? '#6B7280';
                    $oc = ['positive' => '#10B981', 'neutral' => '#6B7280', 'negative' => '#EF4444', 'no_answer' => '#F59E0B'][$activity->outcome ?? ''] ?? null;
                @endphp
                <div style="padding: 12px 16px; border-bottom: 1px solid #1F2937; display: flex; gap: 10px;" onmouseover="this.style.backgroundColor='rgba(55,65,81,0.15)'" onmouseout="this.style.backgroundColor='transparent'">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background-color: {{ $tc }}20; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background-color: {{ $tc }};"></div>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 12px; font-weight: 600; color: #E5E7EB; margin-bottom: 2px;">{{ $activity->subject }}</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 3px;">
                            <span style="font-size: 10px; padding: 1px 6px; border-radius: 6px; background-color: {{ $tc }}20; color: {{ $tc }}; font-weight: 500;">{{ ucfirst($activity->type) }}</span>
                            @if($oc)
                            <span style="font-size: 10px; padding: 1px 6px; border-radius: 6px; background-color: {{ $oc }}20; color: {{ $oc }}; font-weight: 500;">{{ ucwords(str_replace('_', ' ', $activity->outcome)) }}</span>
                            @endif
                        </div>
                        @if($activity->description)
                        <p style="font-size: 11px; color: #6B7280; margin: 2px 0 0; line-height: 1.3;">{{ \Illuminate\Support\Str::limit(strip_tags($activity->description), 120) }}</p>
                        @endif
                        <div style="font-size: 10px; color: #4B5563; margin-top: 3px;">{{ $activity->user?->name ?? 'System' }} &middot; {{ $activity->occurred_at?->diffForHumans() ?? $activity->created_at->diffForHumans() }}</div>
                    </div>
                    <button wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Delete?" style="background: none; border: none; color: #374151; cursor: pointer; padding: 2px; align-self: flex-start;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#374151'">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @empty
                <div style="padding: 40px 16px; text-align: center; color: #4B5563; font-size: 12px;">No activities yet.<br>Log one above.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-filament-panels::page>
