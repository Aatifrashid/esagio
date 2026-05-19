<x-filament-panels::page>
    <style>
        header.fi-header { display: none !important; }
        .fi-page-content-ctn { max-width: 100% !important; }
        .fi-body-content { padding: 0 !important; }
        .fi-page > .fi-page-content-ctn > div { gap: 0 !important; }

        .pv * { font-family: 'Lexend Deca', -apple-system, BlinkMacSystemFont, sans-serif; box-sizing: border-box; margin: 0; }

        .pv {
            --bg: #f5f5f5;
            --surface: #ffffff;
            --surface2: #f9fafb;
            --border: #e5e7eb;
            --border-light: #f0f0f0;
            --t1: #1a1a1a;
            --t2: #374151;
            --t3: #6b7280;
            --t4: #9ca3af;
            --accent: #E8663D;
            --accent-h: #d4572f;
            --accent-bg: rgba(232,102,61,0.08);
            --green: #059669;
            --green-bg: rgba(5,150,105,0.08);
            --orange: #d97706;
            --orange-bg: rgba(217,119,6,0.08);
            --red: #dc2626;
            --red-bg: rgba(220,38,38,0.08);
            --purple: #7c3aed;
            --purple-bg: rgba(124,58,237,0.08);
            --input-bg: #f9fafb;
            --scheme: light;
        }

        /* Inputs */
        .pv input, .pv textarea, .pv select {
            font-family: 'Lexend Deca', sans-serif;
            color-scheme: var(--scheme);
        }

        .pv-field {
            padding: 10px 16px;
            border-bottom: 1px solid var(--border-light);
        }
        .pv-field:hover { background: rgba(0,0,0,0.015); }

        .pv-field label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: var(--t4);
            margin-bottom: 3px;
            letter-spacing: 0.01em;
        }

        .pv-field input,
        .pv-field textarea {
            width: 100%;
            background: transparent;
            border: none;
            padding: 2px 0;
            font-size: 13px;
            font-weight: 400;
            color: var(--t1);
            outline: none;
        }
        .pv-field input::placeholder,
        .pv-field textarea::placeholder {
            color: var(--t4);
        }
        .pv-field input:focus,
        .pv-field textarea:focus {
            color: var(--accent);
        }

        /* Custom select wrapper */
        .pv-select-wrap {
            position: relative;
        }
        .pv-select-wrap select {
            width: 100%;
            background: transparent;
            border: none;
            padding: 2px 20px 2px 0;
            font-size: 13px;
            font-weight: 400;
            color: var(--t1);
            outline: none;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .pv-select-wrap::after {
            content: '';
            position: absolute;
            right: 2px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--t4);
            pointer-events: none;
        }
        .pv-select-wrap select option {
            background: var(--surface);
            color: var(--t1);
        }

        /* Boxed input (for task/activity forms) */
        .pv-box-input {
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 12px;
            color: var(--t1);
            outline: none;
            width: 100%;
            transition: border-color 0.15s;
        }
        .pv-box-input:focus {
            border-color: var(--accent);
        }
        .pv-box-input option {
            background: var(--surface);
            color: var(--t1);
        }
        select.pv-box-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%239ca3af' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }

        /* Card */
        .pv-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }
        .pv-card-head {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-light);
        }
        .pv-card-head h3 {
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Badge */
        .pv-pill {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.02em;
        }

        /* Row item */
        .pv-item {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border-light);
            transition: background 0.1s;
        }
        .pv-item:last-child { border-bottom: none; }
        .pv-item:hover { background: rgba(0,0,0,0.015); }

        /* Buttons */
        .pv-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }
        .pv-btn-blue { background: var(--accent); color: #fff; }
        .pv-btn-blue:hover { background: var(--accent-h); }
        .pv-btn-green { background: var(--green); color: #fff; }
        .pv-btn-green:hover { opacity: 0.9; }
        .pv-btn-purple { background: var(--purple); color: #fff; }
        .pv-btn-purple:hover { opacity: 0.9; }
        .pv-btn-ghost {
            background: transparent;
            color: var(--t3);
            border: 1px solid var(--border);
        }
        .pv-btn-ghost:hover { background: var(--surface2); color: var(--t1); }

        /* Section divider label */
        .pv-divider {
            padding: 14px 16px 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Delete/close icon btn */
        .pv-icon-btn {
            background: none;
            border: none;
            color: var(--t4);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            display: flex;
            transition: all 0.15s;
            flex-shrink: 0;
        }
        .pv-icon-btn:hover { background: var(--red-bg); color: var(--red); }


        /* Empty state */
        .pv-empty {
            padding: 32px 16px;
            text-align: center;
            color: var(--t4);
            font-size: 12px;
        }

        /* Activity dot */
        .pv-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .pv-dot-wrap {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Scrollbar */
        .pv ::-webkit-scrollbar { width: 4px; }
        .pv ::-webkit-scrollbar-track { background: transparent; }
        .pv ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
    </style>

    <div class="pv" id="pvRoot" style="display: flex; height: calc(100vh - 64px); overflow: hidden; margin: -24px; background: var(--bg);">

        {{-- ═══ LEFT COLUMN ═══ --}}
        <div style="width: 300px; min-width: 300px; border-right: 1px solid var(--border); display: flex; flex-direction: column; background: var(--surface);">

            {{-- Header --}}
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="{{ url('/dashboard/patients') }}" style="color: var(--t3); text-decoration: none; display: flex; padding: 4px; border-radius: 4px; transition: background 0.15s;" onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='transparent'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <span style="font-size: 14px; font-weight: 600; color: var(--t1);">Contact Details</span>
                </div>
            </div>

            {{-- Scrollable fields --}}
            <div style="flex: 1; overflow-y: auto;">

                {{-- Contact card --}}
                <div style="padding: 20px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #E8663D, #f59e0b); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-size: 16px; font-weight: 700; color: var(--t1); line-height: 1.3;">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                        <div style="font-size: 11px; color: var(--t4); margin-top: 2px;">{{ $patient->reference_code }}</div>
                    </div>
                </div>

                {{-- Tags --}}
                @if($patient->tags && count($patient->tags))
                <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach($patient->tags as $tag)
                    <span style="font-size: 11px; padding: 3px 10px; border-radius: 12px; background: var(--surface2); color: var(--t2); border: 1px solid var(--border);">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Contact fields --}}
                <div class="pv-divider">Contact</div>

                <div class="pv-field">
                    <label>First name</label>
                    <input type="text" wire:model.blur="first_name" placeholder="--">
                </div>
                <div class="pv-field">
                    <label>Last name</label>
                    <input type="text" wire:model.blur="last_name" placeholder="--">
                </div>
                <div class="pv-field">
                    <label>Email</label>
                    <input type="email" wire:model.blur="email" placeholder="--">
                </div>
                <div class="pv-field">
                    <label>Phone</label>
                    <input type="tel" wire:model.blur="phone" placeholder="--">
                </div>
                <div class="pv-field">
                    <label>WhatsApp</label>
                    <input type="tel" wire:model.blur="whatsapp_number" placeholder="--">
                </div>
                <div class="pv-field">
                    <label>Date of birth</label>
                    <input type="date" wire:model.blur="date_of_birth">
                </div>
                <div class="pv-field">
                    <label>Gender</label>
                    <div class="pv-select-wrap">
                        <select wire:model.blur="gender">
                            <option value="">--</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                {{-- CRM fields --}}
                <div class="pv-divider">CRM Details</div>

                <div class="pv-field">
                    <label>Status</label>
                    <div class="pv-select-wrap">
                        <select wire:model.blur="status">
                            @foreach(['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'proposal_sent' => 'Proposal Sent', 'won' => 'Won', 'lost' => 'Lost', 'on_hold' => 'On Hold'] as $v => $l)
                            <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pv-field">
                    <label>Source</label>
                    <div class="pv-select-wrap">
                        <select wire:model.blur="source">
                            <option value="">--</option>
                            @foreach(['website' => 'Website', 'referral' => 'Referral', 'social_media' => 'Social Media', 'google_ads' => 'Google Ads', 'facebook_ads' => 'Facebook Ads', 'walk_in' => 'Walk In', 'email_campaign' => 'Email Campaign', 'other' => 'Other'] as $v => $l)
                            <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pv-field">
                    <label>Assigned to</label>
                    <div class="pv-select-wrap">
                        <select wire:model.blur="assigned_to">
                            <option value="">--</option>
                            @foreach($this->getUsers() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pv-field">
                    <label>Deal value</label>
                    <div style="display: flex; align-items: center; gap: 2px;">
                        <span style="font-size: 13px; color: var(--t4);">£</span>
                        <input type="number" step="0.01" wire:model.blur="deal_value" placeholder="0.00" style="flex: 1;">
                    </div>
                </div>
                <div class="pv-field">
                    <label>Pipeline stage</label>
                    <div class="pv-select-wrap">
                        <select wire:model.blur="pipeline_stage_id">
                            <option value="">--</option>
                            @foreach($this->getStages() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pv-field">
                    <label>Tags</label>
                    <input type="text" wire:model.blur="tags_string" placeholder="tag1, tag2...">
                </div>

                {{-- Notes --}}
                <div class="pv-divider">Notes</div>
                <div style="padding: 8px 16px 16px;">
                    <textarea wire:model.blur="notes" rows="3" placeholder="Add notes..." style="width: 100%; background: var(--input-bg); border: 1px solid var(--border); border-radius: 6px; padding: 10px; font-size: 12px; color: var(--t1); outline: none; resize: vertical; transition: border-color 0.15s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                </div>

                {{-- Save --}}
                <div style="padding: 0 16px 20px;">
                    <button wire:click="saveDetails" class="pv-btn pv-btn-blue" style="width: 100%; justify-content: center; padding: 9px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                    <div style="font-size: 10px; color: var(--t4); margin-top: 12px; text-align: center;">
                        Created {{ $patient->created_at->format('d M Y, g:i A') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ MIDDLE COLUMN ═══ --}}
        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; border-right: 1px solid var(--border);">

            {{-- Top bar --}}
            <div style="padding: 12px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; background: var(--surface);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #E8663D, #f59e0b); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <span style="font-size: 14px; font-weight: 600; color: var(--t1);">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                        @php
                            $sc = ['new' => '--t4', 'contacted' => '--orange', 'qualified' => '--accent', 'proposal_sent' => '--purple', 'won' => '--green', 'lost' => '--red', 'on_hold' => '--t4'];
                            $scv = $sc[$patient->status] ?? '--t4';
                            $scbg = str_replace('--', '--', $scv) . '-bg';
                        @endphp
                        <span class="pv-pill" style="margin-left: 8px; background: var({{ $scv == '--t4' ? '--surface2' : $scbg }}); color: var({{ $scv }});">{{ ucwords(str_replace('_', ' ', $patient->status)) }}</span>
                    </div>
                </div>
                <a href="{{ url('/dashboard/conversations') }}" class="pv-btn pv-btn-blue" style="text-decoration: none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Send Message
                </a>
            </div>

            {{-- Content area — Conversations only --}}
            <div style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 16px; background: var(--bg);">

                {{-- Conversations --}}
                <div class="pv-card" style="flex: 1; display: flex; flex-direction: column;">
                    <div class="pv-card-head">
                        <h3>
                            <svg width="16" height="16" fill="none" stroke="var(--orange)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Conversations
                            <span class="pv-pill" style="background: var(--surface2); color: var(--t3);">{{ $patient->conversations->count() }}</span>
                        </h3>
                    </div>
                    <div style="flex: 1; overflow-y: auto;">
                        @forelse($patient->conversations->sortByDesc('last_message_at') as $convo)
                        @php $cc = ['whatsapp' => '--green', 'email' => '--accent', 'sms' => '--orange'][$convo->channel] ?? '--t4'; @endphp
                        <div class="pv-item" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span class="pv-pill" style="background: var({{ $cc }}-bg); color: var({{ $cc }});">{{ ucfirst($convo->channel) }}</span>
                                <span style="font-size: 13px; color: var(--t1);">{{ $convo->channel_identifier }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 11px; color: var(--t4);">{{ $convo->messages->count() }} msgs</span>
                                <span style="font-size: 11px; color: var(--t4);">{{ $convo->last_message_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="pv-empty" style="padding: 48px 16px;">
                            <svg width="32" height="32" fill="none" stroke="var(--t4)" viewBox="0 0 24 24" style="margin: 0 auto 12px; display: block; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            No conversations yet.<br>Start one using Send Message above.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══ RIGHT COLUMN — Activity + Tasks + Treatment Plans ═══ --}}
        <div style="width: 360px; min-width: 360px; display: flex; flex-direction: column; background: var(--surface);">

            <div style="flex: 1; overflow-y: auto;">

                {{-- Activity section --}}
                <div style="padding: 12px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0; position: sticky; top: 0; background: var(--surface); z-index: 1;">
                    <span style="font-size: 14px; font-weight: 600; color: var(--t1);">Activity</span>
                </div>

                {{-- Quick log form --}}
                <div style="padding: 12px 16px; border-bottom: 1px solid var(--border); background: var(--surface2); flex-shrink: 0;">
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <select wire:model="activity_type" class="pv-box-input" style="flex: 1;">
                            <option value="note">Note</option>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                            <option value="meeting">Meeting</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                        <select wire:model="activity_outcome" class="pv-box-input" style="flex: 1;">
                            <option value="">Outcome</option>
                            <option value="positive">Positive</option>
                            <option value="neutral">Neutral</option>
                            <option value="negative">Negative</option>
                            <option value="no_answer">No Answer</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" wire:model="activity_subject" placeholder="Subject..." class="pv-box-input" style="flex: 1;">
                        <button wire:click="logActivity" class="pv-btn pv-btn-blue" style="padding: 7px 14px;">+ Log</button>
                    </div>
                    @error('activity_subject') <span style="font-size: 10px; color: var(--red); margin-top: 6px; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- Timeline --}}
                @forelse($patient->activities->sortByDesc('occurred_at') as $activity)
                @php
                    $tc = ['call' => '--accent', 'email' => '--green', 'meeting' => '--orange', 'note' => '--t4', 'whatsapp' => '--green', 'other' => '--purple'][$activity->type] ?? '--t4';
                    $oc = ['positive' => '--green', 'neutral' => '--t4', 'negative' => '--red', 'no_answer' => '--orange'][$activity->outcome ?? ''] ?? null;
                @endphp
                <div style="padding: 14px 16px; border-bottom: 1px solid var(--border-light); display: flex; gap: 12px; transition: background 0.1s;" onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='transparent'">
                    <div class="pv-dot-wrap" style="background: var({{ $tc }}-bg, var(--surface2)); margin-top: 2px;">
                        <div class="pv-dot" style="background: var({{ $tc }});"></div>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 13px; font-weight: 600; color: var(--t1); margin-bottom: 4px;">{{ $activity->subject }}</div>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 4px;">
                            <span class="pv-pill" style="background: var({{ $tc }}-bg, var(--surface2)); color: var({{ $tc }});">{{ ucfirst($activity->type) }}</span>
                            @if($oc)
                            <span class="pv-pill" style="background: var({{ $oc }}-bg, var(--surface2)); color: var({{ $oc }});">{{ ucwords(str_replace('_', ' ', $activity->outcome)) }}</span>
                            @endif
                        </div>
                        @if($activity->description)
                        <p style="font-size: 12px; color: var(--t3); margin: 4px 0 0; line-height: 1.4;">{{ \Illuminate\Support\Str::limit(strip_tags($activity->description), 120) }}</p>
                        @endif
                        <div style="font-size: 11px; color: var(--t4); margin-top: 6px;">{{ $activity->user?->name ?? 'System' }} &middot; {{ $activity->occurred_at?->diffForHumans() ?? $activity->created_at->diffForHumans() }}</div>
                    </div>
                    <button wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Delete this activity?" class="pv-icon-btn" style="align-self: flex-start;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @empty
                <div class="pv-empty">
                    No activities yet. Log one above.
                </div>
                @endforelse

                {{-- Tasks section --}}
                <div style="margin-top: 8px; border-top: 2px solid var(--border);">
                    <div class="pv-card-head">
                        <h3>
                            <svg width="16" height="16" fill="none" stroke="var(--green)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Tasks
                            <span class="pv-pill" style="background: var(--surface2); color: var(--t3);">{{ $patient->tasks->count() }}</span>
                        </h3>
                    </div>

                    {{-- Quick add form --}}
                    <div style="padding: 10px 16px; border-bottom: 1px solid var(--border-light); display: flex; gap: 8px; flex-wrap: wrap; background: var(--surface2);">
                        <input type="text" wire:model="task_title" placeholder="Task title..." class="pv-box-input" style="flex: 1; min-width: 120px;">
                        <input type="date" wire:model="task_due_date" class="pv-box-input" style="width: auto; flex: none;">
                        <select wire:model="task_priority" class="pv-box-input" style="width: auto; flex: none;">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <button wire:click="createTask" class="pv-btn pv-btn-green" style="font-size: 12px;">+ Task</button>
                    </div>
                    @error('task_title') <div style="padding: 6px 16px; font-size: 11px; color: var(--red);">{{ $message }}</div> @enderror

                    @forelse($patient->tasks->sortBy('status')->sortBy('due_date') as $task)
                    @php
                        $pc = ['low' => '--t4', 'medium' => '--accent', 'high' => '--orange', 'urgent' => '--red'][$task->priority] ?? '--t4';
                        $done = $task->status === 'completed';
                    @endphp
                    <div class="pv-item" style="{{ $done ? 'opacity: 0.4;' : '' }}">
                        <button wire:click="toggleTaskStatus({{ $task->id }})" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid {{ $done ? 'var(--green)' : 'var(--border)' }}; background: {{ $done ? 'var(--green)' : 'transparent' }}; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 0;">
                            @if($done)<svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                        </button>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 13px; font-weight: 500; color: var(--t1); {{ $done ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 3px;">
                                <span class="pv-pill" style="background: var({{ $pc }}-bg, var(--surface2)); color: var({{ $pc }});">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)
                                <span style="font-size: 11px; color: {{ $task->due_date->isPast() && !$done ? 'var(--red)' : 'var(--t4)' }};">{{ $task->due_date->format('d M') }}</span>
                                @endif
                            </div>
                        </div>
                        <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete this task?" class="pv-icon-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @empty
                    <div class="pv-empty">No tasks yet</div>
                    @endforelse
                </div>

                {{-- Treatment Plans section --}}
                <div style="margin-top: 8px; border-top: 2px solid var(--border);">
                    <div class="pv-card-head">
                        <h3>
                            <svg width="16" height="16" fill="none" stroke="var(--purple)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Treatment Plans
                            <span class="pv-pill" style="background: var(--surface2); color: var(--t3);">{{ $patient->treatmentPlans->count() }}</span>
                        </h3>
                        <a href="{{ route('plan.builder', ['plan' => $patient->id]) }}" target="_blank" class="pv-btn pv-btn-purple" style="font-size: 11px; padding: 5px 12px;">+ New</a>
                    </div>
                    @forelse($patient->treatmentPlans->sortByDesc('created_at') as $plan)
                    @php $plc = ['draft' => '--t4', 'sent' => '--accent', 'accepted' => '--green', 'declined' => '--red', 'expired' => '--orange'][$plan->status] ?? '--t4'; @endphp
                    <div class="pv-item" style="justify-content: space-between;">
                        <div>
                            <div style="font-size: 13px; font-weight: 500; color: var(--t1);">{{ $plan->title }}</div>
                            <div style="font-size: 11px; color: var(--t4); margin-top: 2px;">{{ $plan->created_at->format('d M Y') }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="pv-pill" style="background: var({{ $plc }}-bg, var(--surface2)); color: var({{ $plc }});">{{ ucfirst($plan->status) }}</span>
                            <a href="{{ route('plan.builder', ['plan' => $plan->id]) }}" target="_blank" class="pv-icon-btn" style="color: var(--t4);">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="pv-empty">No treatment plans yet</div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

</x-filament-panels::page>
