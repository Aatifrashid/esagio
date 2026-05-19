<x-filament-panels::page>
    <style>
        header.fi-header { display: none !important; }
        .fi-page-content-ctn { max-width: 100% !important; }
        .fi-body-content { padding: 0 !important; }
        .fi-page > .fi-page-content-ctn > div { gap: 0 !important; }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        /* ── Dark theme (default) ── */
        .pv-root {
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            --bg-page: #0d1117;
            --bg-surface: #161b22;
            --bg-surface-raised: #1c2128;
            --bg-hover: rgba(177,186,196,0.06);
            --bg-input: #0d1117;
            --border-default: #30363d;
            --border-muted: #21262d;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --text-muted: #484f58;
            --accent: #2f81f7;
            --accent-hover: #1f6feb;
            --accent-subtle: rgba(47,129,247,0.1);
            --green: #3fb950;
            --green-subtle: rgba(63,185,80,0.1);
            --orange: #d29922;
            --orange-subtle: rgba(210,153,34,0.1);
            --red: #f85149;
            --red-subtle: rgba(248,81,73,0.1);
            --purple: #a371f7;
            --purple-subtle: rgba(163,113,247,0.1);
            --badge-bg: #30363d;
            --shadow: none;
            --toggle-bg: #30363d;
        }

        /* ── Light theme (HubSpot-inspired) ── */
        .pv-root.light {
            --bg-page: #f5f8fa;
            --bg-surface: #ffffff;
            --bg-surface-raised: #f5f8fa;
            --bg-hover: rgba(51,71,91,0.04);
            --bg-input: #f5f8fa;
            --border-default: #cbd6e2;
            --border-muted: #eaf0f6;
            --text-primary: #33475b;
            --text-secondary: #516f90;
            --text-muted: #7c98b6;
            --accent: #0091ae;
            --accent-hover: #007a91;
            --accent-subtle: rgba(0,145,174,0.08);
            --green: #00a4bd;
            --green-subtle: rgba(0,164,189,0.08);
            --orange: #f5c26b;
            --orange-subtle: rgba(245,194,107,0.1);
            --red: #f2545b;
            --red-subtle: rgba(242,84,91,0.08);
            --purple: #6a78d1;
            --purple-subtle: rgba(106,120,209,0.08);
            --badge-bg: #eaf0f6;
            --shadow: 0 1px 3px rgba(0,0,0,0.08);
            --toggle-bg: #eaf0f6;
        }

        .pv-root * { font-family: var(--font); box-sizing: border-box; }
        .pv-root input, .pv-root select, .pv-root textarea, .pv-root button { font-family: var(--font); }

        .pv-input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--border-default);
            padding: 5px 0;
            font-size: 13px;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.15s;
            color-scheme: dark;
        }
        .pv-root.light .pv-input { color-scheme: light; }
        .pv-input:focus { border-bottom-color: var(--accent); }
        .pv-input option { background: var(--bg-surface); color: var(--text-primary); }

        .pv-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-default);
            border-radius: 6px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .pv-card-header {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border-default);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pv-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
        }

        .pv-row {
            padding: 8px 14px;
            border-bottom: 1px solid var(--border-muted);
            display: flex;
            align-items: center;
            transition: background 0.1s;
        }
        .pv-row:hover { background: var(--bg-hover); }

        .pv-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .pv-btn-primary { background: var(--accent); color: #fff; }
        .pv-btn-primary:hover { background: var(--accent-hover); }
        .pv-btn-green { background: var(--green); color: #fff; }
        .pv-btn-green:hover { opacity: 0.9; }
        .pv-btn-purple { background: var(--purple); color: #fff; }
        .pv-btn-purple:hover { opacity: 0.9; }
        .pv-btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-default); }
        .pv-btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }

        .pv-section-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px 8px;
            border-top: 1px solid var(--border-muted);
        }
        .pv-section-label:first-child { border-top: none; }

        .pv-field-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 2px;
            display: block;
        }

        .pv-toggle {
            width: 36px; height: 20px;
            border-radius: 10px;
            background: var(--toggle-bg);
            border: 1px solid var(--border-default);
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }
        .pv-toggle::after {
            content: '';
            position: absolute;
            width: 14px; height: 14px;
            border-radius: 50%;
            background: var(--text-secondary);
            top: 2px; left: 2px;
            transition: transform 0.2s;
        }
        .pv-root.light .pv-toggle::after { transform: translateX(16px); background: var(--accent); }
    </style>

    <div class="pv-root" id="patientView" style="display: flex; height: calc(100vh - 64px); overflow: hidden; margin: -24px; background: var(--bg-page);">

        {{-- COLUMN 1 — Contact Details --}}
        <div style="width: 280px; min-width: 280px; border-right: 1px solid var(--border-default); overflow-y: auto; background: var(--bg-surface);">

            {{-- Header --}}
            <div style="padding: 14px 16px; border-bottom: 1px solid var(--border-default); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <a href="{{ url('/dashboard/patients') }}" style="color: var(--text-secondary); text-decoration: none; display: flex;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Contact Details</span>
                </div>
                {{-- Theme toggle --}}
                <div class="pv-toggle" onclick="document.getElementById('patientView').classList.toggle('light'); localStorage.setItem('pv-theme', document.getElementById('patientView').classList.contains('light') ? 'light' : 'dark');" title="Toggle light/dark"></div>
            </div>

            {{-- Avatar + name --}}
            <div style="padding: 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border-muted);">
                <div style="background: var(--accent); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: #fff; flex-shrink: 0;">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--text-primary); line-height: 1.3;">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">{{ $patient->reference_code }}</div>
                </div>
            </div>

            {{-- Contact section --}}
            <div class="pv-section-label" style="border-top: none;">Contact</div>
            <div style="padding: 0 16px 8px;">
                @foreach([
                    ['first_name', 'First name', 'text'],
                    ['last_name', 'Last name', 'text'],
                    ['email', 'Email', 'email'],
                    ['phone', 'Phone', 'tel'],
                    ['whatsapp_number', 'WhatsApp', 'tel'],
                    ['date_of_birth', 'Date of birth', 'date'],
                ] as [$field, $label, $type])
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">{{ $label }}</label>
                    <input type="{{ $type }}" wire:model.blur="{{ $field }}" class="pv-input">
                </div>
                @endforeach
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Gender</label>
                    <select wire:model.blur="gender" class="pv-input">
                        <option value="">--</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            {{-- CRM section --}}
            <div class="pv-section-label">CRM Details</div>
            <div style="padding: 0 16px 8px;">
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Status</label>
                    <select wire:model.blur="status" class="pv-input">
                        @foreach(['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'proposal_sent' => 'Proposal Sent', 'won' => 'Won', 'lost' => 'Lost', 'on_hold' => 'On Hold'] as $v => $l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Source</label>
                    <select wire:model.blur="source" class="pv-input">
                        <option value="">--</option>
                        @foreach(['website' => 'Website', 'referral' => 'Referral', 'social_media' => 'Social Media', 'google_ads' => 'Google Ads', 'facebook_ads' => 'Facebook Ads', 'walk_in' => 'Walk In', 'email_campaign' => 'Email Campaign', 'other' => 'Other'] as $v => $l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Assigned to</label>
                    <select wire:model.blur="assigned_to" class="pv-input">
                        <option value="">--</option>
                        @foreach($this->getUsers() as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Deal value</label>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <span style="font-size: 13px; color: var(--text-muted);">£</span>
                        <input type="number" step="0.01" wire:model.blur="deal_value" class="pv-input">
                    </div>
                </div>
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Pipeline stage</label>
                    <select wire:model.blur="pipeline_stage_id" class="pv-input">
                        <option value="">--</option>
                        @foreach($this->getStages() as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 8px;">
                    <label class="pv-field-label">Tags</label>
                    <input type="text" wire:model.blur="tags_string" placeholder="tag1, tag2..." class="pv-input">
                </div>
            </div>

            {{-- Notes --}}
            <div class="pv-section-label">Notes</div>
            <div style="padding: 0 16px 12px;">
                <textarea wire:model.blur="notes" rows="3" placeholder="Add notes..."
                    style="width: 100%; background: var(--bg-input); border: 1px solid var(--border-default); border-radius: 4px; padding: 8px; font-size: 13px; color: var(--text-primary); outline: none; resize: vertical; box-sizing: border-box; transition: border-color 0.15s;"
                    onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-default)'"></textarea>
            </div>

            {{-- Save --}}
            <div style="padding: 0 16px 16px;">
                <button wire:click="saveDetails" class="pv-btn pv-btn-primary" style="width: 100%; justify-content: center; padding: 8px;">
                    Save Changes
                </button>
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 10px;">
                    Created {{ $patient->created_at->format('d M Y, g:i A') }}
                </div>
            </div>
        </div>

        {{-- COLUMN 2 — Middle --}}
        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; border-right: 1px solid var(--border-default);">

            {{-- Top bar --}}
            <div style="padding: 10px 16px; border-bottom: 1px solid var(--border-default); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; background: var(--bg-surface);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="background: var(--accent); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #fff;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                    @php
                        $statusColors = ['new' => 'var(--text-muted)', 'contacted' => 'var(--orange)', 'qualified' => 'var(--accent)', 'proposal_sent' => 'var(--purple)', 'won' => 'var(--green)', 'lost' => 'var(--red)', 'on_hold' => 'var(--text-muted)'];
                        $sc = $statusColors[$patient->status] ?? 'var(--text-muted)';
                    @endphp
                    <span class="pv-badge" style="background: var(--badge-bg); color: {{ $sc }};">{{ ucwords(str_replace('_', ' ', $patient->status)) }}</span>
                </div>
                <a href="{{ url('/dashboard/conversations') }}" class="pv-btn pv-btn-primary" style="text-decoration: none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Send Message
                </a>
            </div>

            {{-- Scrollable content --}}
            <div style="flex: 1; overflow-y: auto; padding: 14px 16px; display: flex; flex-direction: column; gap: 14px; background: var(--bg-page);">

                {{-- Conversations --}}
                <div class="pv-card">
                    <div class="pv-card-header">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" fill="none" stroke="var(--orange)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span style="font-size: 12px; font-weight: 600; color: var(--text-primary);">Conversations</span>
                            <span class="pv-badge" style="background: var(--badge-bg); color: var(--text-secondary); font-size: 10px;">{{ $patient->conversations->count() }}</span>
                        </div>
                    </div>
                    @forelse($patient->conversations->sortByDesc('last_message_at') as $convo)
                    @php $cc = ['whatsapp' => 'var(--green)', 'email' => 'var(--accent)', 'sms' => 'var(--orange)'][$convo->channel] ?? 'var(--text-muted)'; @endphp
                    <div class="pv-row" style="justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="pv-badge" style="background: var(--green-subtle); color: {{ $cc }}; font-size: 10px;">{{ ucfirst($convo->channel) }}</span>
                            <span style="font-size: 12px; color: var(--text-primary);">{{ $convo->channel_identifier }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 11px; color: var(--text-muted);">{{ $convo->messages->count() }} msgs</span>
                            <span style="font-size: 11px; color: var(--text-muted);">{{ $convo->last_message_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 20px 14px; text-align: center; color: var(--text-muted); font-size: 12px;">No conversations yet</div>
                    @endforelse
                </div>

                {{-- Tasks --}}
                <div class="pv-card">
                    <div class="pv-card-header">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" fill="none" stroke="var(--green)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span style="font-size: 12px; font-weight: 600; color: var(--text-primary);">Tasks</span>
                            <span class="pv-badge" style="background: var(--badge-bg); color: var(--text-secondary); font-size: 10px;">{{ $patient->tasks->count() }}</span>
                        </div>
                    </div>
                    {{-- Quick add --}}
                    <div style="padding: 8px 14px; border-bottom: 1px solid var(--border-muted); display: flex; gap: 6px; flex-wrap: wrap; background: var(--bg-surface-raised);">
                        <input type="text" wire:model="task_title" placeholder="Task title..." class="pv-input" style="flex: 1; min-width: 140px; border: 1px solid var(--border-default); border-radius: 4px; padding: 5px 8px; background: var(--bg-input); border-bottom-width: 1px;">
                        <input type="date" wire:model="task_due_date" class="pv-input" style="width: auto; border: 1px solid var(--border-default); border-radius: 4px; padding: 5px 8px; background: var(--bg-input); border-bottom-width: 1px;">
                        <select wire:model="task_priority" class="pv-input" style="width: auto; border: 1px solid var(--border-default); border-radius: 4px; padding: 5px 8px; background: var(--bg-input); border-bottom-width: 1px;">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <button wire:click="createTask" class="pv-btn pv-btn-green">+ Task</button>
                    </div>
                    @error('task_title') <div style="padding: 4px 14px; font-size: 11px; color: var(--red);">{{ $message }}</div> @enderror
                    @forelse($patient->tasks->sortBy('status')->sortBy('due_date') as $task)
                    @php
                        $pc = ['low' => 'var(--text-muted)', 'medium' => 'var(--accent)', 'high' => 'var(--orange)', 'urgent' => 'var(--red)'][$task->priority] ?? 'var(--text-muted)';
                        $done = $task->status === 'completed';
                    @endphp
                    <div class="pv-row" style="gap: 10px; {{ $done ? 'opacity: 0.45;' : '' }}">
                        <button wire:click="toggleTaskStatus({{ $task->id }})" style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid {{ $done ? 'var(--green)' : 'var(--border-default)' }}; background: {{ $done ? 'var(--green)' : 'transparent' }}; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 0;">
                            @if($done)<svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                        </button>
                        <div style="flex: 1; min-width: 0;">
                            <span style="font-size: 12px; font-weight: 500; color: var(--text-primary); {{ $done ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</span>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 1px;">
                                <span class="pv-badge" style="background: var(--badge-bg); color: {{ $pc }}; font-size: 10px; padding: 1px 6px;">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)<span style="font-size: 10px; color: {{ $task->due_date->isPast() && !$done ? 'var(--red)' : 'var(--text-muted)' }};">{{ $task->due_date->format('d M') }}</span>@endif
                            </div>
                        </div>
                        <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete this task?" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 2px; transition: color 0.15s;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='var(--text-muted)'">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @empty
                    <div style="padding: 20px 14px; text-align: center; color: var(--text-muted); font-size: 12px;">No tasks yet</div>
                    @endforelse
                </div>

                {{-- Treatment Plans --}}
                <div class="pv-card">
                    <div class="pv-card-header">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" fill="none" stroke="var(--purple)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span style="font-size: 12px; font-weight: 600; color: var(--text-primary);">Treatment Plans</span>
                            <span class="pv-badge" style="background: var(--badge-bg); color: var(--text-secondary); font-size: 10px;">{{ $patient->treatmentPlans->count() }}</span>
                        </div>
                        <a href="{{ route('plan.builder', ['plan' => $patient->id]) }}" target="_blank" class="pv-btn pv-btn-purple" style="text-decoration: none; font-size: 11px;">+ New</a>
                    </div>
                    @forelse($patient->treatmentPlans->sortByDesc('created_at') as $plan)
                    @php $plc = ['draft' => 'var(--text-muted)', 'sent' => 'var(--accent)', 'accepted' => 'var(--green)', 'declined' => 'var(--red)', 'expired' => 'var(--orange)'][$plan->status] ?? 'var(--text-muted)'; @endphp
                    <div class="pv-row" style="justify-content: space-between;">
                        <div>
                            <div style="font-size: 12px; font-weight: 500; color: var(--text-primary);">{{ $plan->title }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $plan->created_at->format('d M Y') }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="pv-badge" style="background: var(--badge-bg); color: {{ $plc }}; font-size: 10px;">{{ ucfirst($plan->status) }}</span>
                            <a href="{{ route('plan.builder', ['plan' => $plan->id]) }}" target="_blank" style="color: var(--text-muted); transition: color 0.15s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 20px 14px; text-align: center; color: var(--text-muted); font-size: 12px;">No treatment plans yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- COLUMN 3 — Activity --}}
        <div style="width: 300px; min-width: 300px; overflow-y: auto; background: var(--bg-surface);">

            <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-default);">
                <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Activity</span>
            </div>

            {{-- Quick log --}}
            <div style="padding: 10px 14px; border-bottom: 1px solid var(--border-default); background: var(--bg-surface-raised);">
                <div style="display: flex; gap: 6px; margin-bottom: 6px;">
                    <select wire:model="activity_type" class="pv-input" style="flex: 1; border: 1px solid var(--border-default); border-radius: 4px; padding: 5px 6px; font-size: 11px; background: var(--bg-input); border-bottom-width: 1px;">
                        <option value="note">Note</option>
                        <option value="call">Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <select wire:model="activity_outcome" class="pv-input" style="flex: 1; border: 1px solid var(--border-default); border-radius: 4px; padding: 5px 6px; font-size: 11px; background: var(--bg-input); border-bottom-width: 1px;">
                        <option value="">Outcome</option>
                        <option value="positive">Positive</option>
                        <option value="neutral">Neutral</option>
                        <option value="negative">Negative</option>
                        <option value="no_answer">No Answer</option>
                    </select>
                </div>
                <div style="display: flex; gap: 6px;">
                    <input type="text" wire:model="activity_subject" placeholder="Subject..." class="pv-input" style="flex: 1; border: 1px solid var(--border-default); border-radius: 4px; padding: 5px 8px; font-size: 11px; background: var(--bg-input); border-bottom-width: 1px;">
                    <button wire:click="logActivity" class="pv-btn pv-btn-primary" style="font-size: 11px; padding: 5px 10px;">+ Log</button>
                </div>
                @error('activity_subject') <span style="font-size: 10px; color: var(--red); margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Timeline --}}
            @forelse($patient->activities->sortByDesc('occurred_at') as $activity)
            @php
                $tc = ['call' => 'var(--accent)', 'email' => 'var(--green)', 'meeting' => 'var(--orange)', 'note' => 'var(--text-muted)', 'whatsapp' => 'var(--green)', 'other' => 'var(--purple)'][$activity->type] ?? 'var(--text-muted)';
                $oc = ['positive' => 'var(--green)', 'neutral' => 'var(--text-muted)', 'negative' => 'var(--red)', 'no_answer' => 'var(--orange)'][$activity->outcome ?? ''] ?? null;
            @endphp
            <div style="padding: 10px 14px; border-bottom: 1px solid var(--border-muted); display: flex; gap: 10px; transition: background 0.1s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--accent-subtle); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: {{ $tc }};"></div>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 12px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">{{ $activity->subject }}</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 3px;">
                        <span class="pv-badge" style="background: var(--badge-bg); color: {{ $tc }}; font-size: 10px; padding: 1px 6px;">{{ ucfirst($activity->type) }}</span>
                        @if($oc)
                        <span class="pv-badge" style="background: var(--badge-bg); color: {{ $oc }}; font-size: 10px; padding: 1px 6px;">{{ ucwords(str_replace('_', ' ', $activity->outcome)) }}</span>
                        @endif
                    </div>
                    @if($activity->description)
                    <p style="font-size: 11px; color: var(--text-secondary); margin: 2px 0 0; line-height: 1.3;">{{ \Illuminate\Support\Str::limit(strip_tags($activity->description), 120) }}</p>
                    @endif
                    <div style="font-size: 10px; color: var(--text-muted); margin-top: 3px;">{{ $activity->user?->name ?? 'System' }} · {{ $activity->occurred_at?->diffForHumans() ?? $activity->created_at->diffForHumans() }}</div>
                </div>
                <button wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Delete?" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 2px; align-self: flex-start; transition: color 0.15s;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='var(--text-muted)'">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @empty
            <div style="padding: 40px 14px; text-align: center; color: var(--text-muted); font-size: 12px;">No activities yet.<br>Log one above.</div>
            @endforelse
        </div>
    </div>

    <script>
        // Restore saved theme
        if (localStorage.getItem('pv-theme') === 'light') {
            document.getElementById('patientView').classList.add('light');
        }
    </script>
</x-filament-panels::page>
