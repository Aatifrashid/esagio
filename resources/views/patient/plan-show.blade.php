@extends('layouts.patient', [
    'title' => $plan->title . ' - ' . $plan->clinic->name,
    'planLanguage' => $plan->language,
    'clinicBranding' => $branding,
])

@push('head')
    <style>
        :root {
            --clinic-primary: {{ $branding?->primary_colour ?? '#0A2540' }};
            --clinic-accent:  {{ $branding?->accent_colour  ?? '#E8663D' }};
        }
        body { background: #FAFAF8; }
        .font-display { font-family: 'Fraunces', Georgia, serif; }
        .font-body    { font-family: 'Inter', system-ui, sans-serif; }
        .text-clinic-primary { color: var(--clinic-primary); }
        .text-clinic-accent  { color: var(--clinic-accent);  }
        .bg-clinic-primary   { background-color: var(--clinic-primary); }
        .bg-clinic-accent    { background-color: var(--clinic-accent); }
        .border-clinic-accent { border-color: var(--clinic-accent); }
        .ring-clinic-accent   { --tw-ring-color: var(--clinic-accent); }

        /* Scroll reveal — JS adds .reveal-ready to enable animations */
        .reveal-ready .reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.55s ease, transform 0.55s ease; }
        .reveal-ready .reveal.visible { opacity: 1; transform: translateY(0); }

        /* Tooth chart */
        .tooth { cursor: default; }
        .tooth-healthy  { fill: #E8F5E9; stroke: #A5D6A7; }
        .tooth-decay    { fill: #FFEBEE; stroke: #EF9A9A; }
        .tooth-missing  { fill: #F3E5F5; stroke: #CE93D8; }
        .tooth-crown    { fill: #FFF3E0; stroke: #FFCC80; }
        .tooth-implant  { fill: #E3F2FD; stroke: #90CAF9; }
        .tooth-root     { fill: #FCE4EC; stroke: #F48FB1; }
        .tooth-bridge   { fill: #E8EAF6; stroke: #9FA8DA; }
        .tooth-veneer   { fill: #E0F7FA; stroke: #80DEEA; }

        /* FAQ accordion */
        [x-cloak] { display: none !important; }

        /* Timeline */
        .timeline-line { background: linear-gradient(to bottom, var(--clinic-accent), var(--clinic-primary)); }

        /* Print */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .reveal { opacity: 1; transform: none; }
            section { break-inside: avoid; }
        }
    </style>
    @endpush

@section('content')
    <div class="font-body min-h-screen">

        @auth
            @if(auth()->user()->clinic_id === $plan->clinic_id)
            <div class="no-print" style="background:#111827;color:#fff;font-size:13px;position:sticky;top:0;z-index:9999;">
                <div style="max-width:1100px;margin:0 auto;padding:8px 24px;display:flex;align-items:center;justify-content:space-between;">
                    <span style="display:flex;align-items:center;gap:8px;color:#9ca3af;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Viewing as patient
                    </span>
                    <a href="{{ route('plan.builder', $plan) }}"
                       style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);padding:4px 12px;border-radius:6px;color:#fff;font-weight:500;text-decoration:none;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Back to Builder
                    </a>
                </div>
            </div>
            @endif
        @endauth

        {{-- ================================================================
             HERO
        ================================================================ --}}
        <section class="relative overflow-hidden bg-clinic-primary text-white no-print" id="hero">
            @if($branding?->cover_page_image)
                <img src="{{ Storage::url($branding->cover_page_image) }}"
                     alt=""
                     class="absolute inset-0 w-full h-full object-cover opacity-20"
                     loading="lazy" />
            @endif
            <div class="relative z-10 max-w-[1100px] mx-auto px-6 py-16 lg:py-24">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="flex-1">
                        @if($branding?->logo_light)
                            <img src="{{ Storage::url($branding->logo_light) }}"
                                 alt="{{ $plan->clinic->name }}"
                                 class="h-12 mb-8 object-contain"
                                 loading="lazy" />
                        @elseif($plan->clinic->logo_path)
                            <img src="{{ Storage::url($plan->clinic->logo_path) }}"
                                 alt="{{ $plan->clinic->name }}"
                                 class="h-12 mb-8 object-contain"
                                 loading="lazy" />
                        @else
                            <p class="text-sm uppercase tracking-widest text-white/60 mb-8 font-display">{{ $plan->clinic->name }}</p>
                        @endif

                        <h1 class="font-display text-3xl lg:text-5xl font-semibold leading-tight mb-4">
                            Your Treatment Plan
                        </h1>
                        <p class="font-display text-xl lg:text-2xl text-white/80 mb-6 italic">
                            {{ $plan->title }}
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm text-white/70">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $plan->patient->first_name }} {{ $plan->patient->last_name }}
                            </span>
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Ref: {{ $plan->reference_number }}
                            </span>
                            @if($plan->valid_until)
                                <span class="flex items-center gap-2 {{ $isExpired ? 'text-red-300' : '' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $isExpired ? 'Expired' : 'Valid until' }}: {{ $plan->valid_until->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-shrink-0 flex flex-col items-start lg:items-end gap-3">
                        <a href="{{ route('patient.plan.pdf', ['token' => $plan->public_token]) }}"
                           class="no-print inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Expired banner --}}
        @if($isExpired)
        <div class="bg-red-50 border-b border-red-200">
            <div class="max-w-[1100px] mx-auto px-6 py-4 flex items-center gap-3 text-red-700">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-sm font-medium">This treatment plan expired on {{ $plan->valid_until->format('d M Y') }}. Please contact us to request an updated plan.</p>
            </div>
        </div>
        @endif

        {{-- Flash message --}}
        @if(session('success'))
        <div class="bg-green-50 border-b border-green-200" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="max-w-[1100px] mx-auto px-6 py-4 flex items-center justify-between gap-3 text-green-700">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        {{-- Main content --}}
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6">

            {{-- ================================================================
                 WELCOME LETTER
            ================================================================ --}}
            @if($plan->notes_to_patient)
            <section class="py-16 reveal" id="welcome">
                <div class="max-w-2xl">
                    <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Welcome</p>
                    <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-6">A message from us</h2>
                    <div class="prose prose-lg text-slate-700 leading-relaxed">
                        {!! nl2br(e($plan->notes_to_patient)) !!}
                    </div>
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 MEET YOUR TEAM
            ================================================================ --}}
            @if($teamMembers->count())
            <section class="py-16 reveal" id="team">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Your Specialists</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Meet Your Team</h2>
                <div class="flex gap-5 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-thin scrollbar-thumb-slate-200">
                    @foreach($teamMembers as $member)
                    <div class="flex-shrink-0 w-56 snap-start bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                        @if($member->photo_path)
                            <img src="{{ Storage::url($member->photo_path) }}"
                                 alt="{{ $member->first_name }} {{ $member->last_name }}"
                                 class="w-full h-44 object-cover object-top"
                                 loading="lazy" />
                        @else
                            <div class="w-full h-44 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                <svg class="w-14 h-14 text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <p class="font-semibold text-clinic-primary text-sm">{{ $member->title ? $member->title . ' ' : '' }}{{ $member->first_name }} {{ $member->last_name }}</p>
                            @if($member->role)
                                <p class="text-xs text-slate-500 mt-0.5">{{ $member->role }}</p>
                            @endif
                            @if($member->specialisation)
                                <p class="text-xs text-clinic-accent mt-2 font-medium leading-snug">{{ $member->specialisation }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 DIAGNOSIS
            ================================================================ --}}
            @if($plan->toothCharts->count())
            <section class="py-16 reveal" id="diagnosis">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Clinical Assessment</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Your Diagnosis</h2>
                <div class="grid lg:grid-cols-2 gap-10 items-start">
                    <div>
                        {{-- Tooth Chart Legend --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @php
                                $conditionColours = [
                                    'decay'    => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'label' => 'Decay'],
                                    'missing'  => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Missing'],
                                    'crown'    => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Crown'],
                                    'implant'  => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'label' => 'Implant'],
                                    'root'     => ['bg' => 'bg-pink-100',   'text' => 'text-pink-700',   'label' => 'Root Treatment'],
                                    'bridge'   => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Bridge'],
                                    'veneer'   => ['bg' => 'bg-cyan-100',   'text' => 'text-cyan-700',   'label' => 'Veneer'],
                                    'healthy'  => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'label' => 'Healthy'],
                                ];
                                $usedConditions = $plan->toothCharts->flatMap(fn($c) => $c->conditions ?? [])->unique()->filter();
                            @endphp
                            @foreach($conditionColours as $key => $info)
                                @if($usedConditions->contains($key))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $info['bg'] }} {{ $info['text'] }}">
                                    <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                                    {{ $info['label'] }}
                                </span>
                                @endif
                            @endforeach
                        </div>

                        {{-- Tooth conditions list --}}
                        <div class="space-y-2">
                            @foreach($plan->toothCharts->sortBy('tooth_number') as $chart)
                            <div class="flex items-start gap-3 p-3 bg-white border border-slate-100 rounded-xl text-sm">
                                <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-clinic-primary text-white flex items-center justify-center text-xs font-bold">{{ $chart->tooth_number }}</span>
                                <div class="flex-1 min-w-0">
                                    @if(!empty($chart->conditions))
                                        <div class="flex flex-wrap gap-1 mb-1">
                                            @foreach($chart->conditions as $condition)
                                                @php $c = $conditionColours[$condition] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => $condition]; @endphp
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $c['bg'] }} {{ $c['text'] }}">{{ $c['label'] }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($chart->notes)
                                        <p class="text-slate-500 text-xs">{{ $chart->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SVG Tooth Chart --}}
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                        @include('patient.partials.tooth-chart-svg', ['toothCharts' => $plan->toothCharts, 'conditionColours' => $conditionColours])
                    </div>
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 TREATMENT TIMELINE
            ================================================================ --}}
            @if($plan->phases->count() > 1)
            <section class="py-16 reveal" id="timeline">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Treatment Journey</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Treatment Timeline</h2>
                <div class="relative">
                    {{-- Vertical line on desktop --}}
                    <div class="hidden lg:block absolute left-6 top-0 bottom-0 w-0.5 timeline-line rounded-full"></div>
                    <div class="space-y-6">
                        @foreach($plan->phases as $phase)
                        <div class="flex gap-6 lg:gap-10 items-start">
                            <div class="relative flex-shrink-0 w-12 h-12 rounded-full bg-clinic-primary text-white flex items-center justify-center font-display font-bold text-lg shadow-md z-10">
                                {{ $phase->phase_number }}
                            </div>
                            <div class="flex-1 bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                                <div class="flex flex-wrap items-start justify-between gap-3 mb-2">
                                    <h3 class="font-display font-semibold text-clinic-primary text-lg">{{ $phase->name }}</h3>
                                    @if($phase->estimated_duration_days)
                                        <span class="text-xs font-medium bg-slate-100 text-slate-600 px-3 py-1 rounded-full flex-shrink-0">
                                            ~{{ $phase->estimated_duration_days }} days
                                        </span>
                                    @endif
                                </div>
                                @if($phase->description)
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $phase->description }}</p>
                                @endif
                                @if($phase->estimated_start_offset_days)
                                    <p class="text-xs text-slate-400 mt-2">Starts approximately {{ $phase->estimated_start_offset_days }} days after treatment begins</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 TREATMENT PLAN ITEMS
            ================================================================ --}}
            @if($plan->items->count())
            <section class="py-16 reveal" id="treatment-plan">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Proposed Treatment</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Your Treatment Plan</h2>

                @php
                    $phases = $plan->phases->keyBy('phase_number');
                    $grouped = $plan->items->groupBy('procedure_phase');
                @endphp

                @foreach($grouped as $phaseNumber => $items)
                    @if($phases->has($phaseNumber) && $plan->phases->count() > 1)
                        <h3 class="font-display text-lg font-semibold text-clinic-primary mb-3 mt-6 flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-clinic-accent text-white text-sm flex items-center justify-center font-bold">{{ $phaseNumber }}</span>
                            {{ $phases[$phaseNumber]->name }}
                        </h3>
                    @endif
                    <div class="space-y-3 mb-6">
                        @foreach($items->sortBy('position') as $item)
                        @php $tpl = $item->template; @endphp
                        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm
                            {{ $item->is_optional ? 'border-dashed border-slate-200' : '' }}">
                            <div class="p-5">
                                <div class="flex flex-wrap items-start gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="font-semibold text-clinic-primary">{{ $item->name }}</span>
                                            @if($item->is_optional)
                                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Optional</span>
                                            @endif
                                            @if($item->material)
                                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium">{{ $item->material->brand ?? $item->material->name }}</span>
                                            @endif
                                            @if($item->variant_name)
                                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $item->variant_name }}</span>
                                            @endif
                                            @if($tpl?->requires_imaging)
                                                <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full font-medium">Imaging required</span>
                                            @endif
                                        </div>

                                        @php $desc = $tpl?->description_long ?: ($item->description ?: $tpl?->description_short); @endphp
                                        @if($desc)
                                            <p class="text-sm text-slate-600 leading-relaxed mb-2">{{ $desc }}</p>
                                        @endif

                                        @if(!empty($item->tooth_positions))
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                @foreach($item->tooth_positions as $tooth)
                                                    <span class="inline-block text-xs bg-clinic-primary text-white/90 px-2 py-0.5 rounded font-mono">T{{ $tooth }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($tpl?->standard_duration_days)
                                            <p class="text-xs text-slate-400">
                                                <span class="font-medium">Duration:</span>
                                                @if($tpl->standard_duration_days < 2) Same day
                                                @elseif($tpl->standard_duration_days <= 14) ~{{ $tpl->standard_duration_days }} days
                                                @elseif($tpl->standard_duration_days <= 60) ~{{ round($tpl->standard_duration_days / 7) }} weeks
                                                @else ~{{ round($tpl->standard_duration_days / 30) }} months
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-xs text-slate-400">{{ $item->quantity }} x {{ number_format($item->unit_price, 2) }}</p>
                                        <p class="text-lg font-bold text-clinic-primary font-display">{{ $plan->currency }} {{ number_format($item->line_total, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Procedure steps from template --}}
                            @if($tpl?->procedure_steps && is_array($tpl->procedure_steps) && count($tpl->procedure_steps) > 0)
                                <div class="border-t border-slate-50 bg-slate-50/50 px-5 py-3">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">How it works</p>
                                    <ol class="space-y-1.5">
                                        @foreach($tpl->procedure_steps as $step)
                                            <li class="flex items-start gap-2 text-xs text-slate-600">
                                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-clinic-accent/10 text-clinic-accent text-[10px] font-bold flex items-center justify-center mt-0.5">{{ $loop->iteration }}</span>
                                                <span>{{ $step }}</span>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            {{-- Recovery info from template --}}
                            @if($tpl?->recovery_info)
                                <div class="border-t border-slate-50 bg-amber-50/30 px-5 py-3">
                                    <p class="text-xs font-semibold text-amber-700 mb-1">Recovery</p>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $tpl->recovery_info }}</p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 TREATMENT OPTIONS
            ================================================================ --}}
            @if($plan->options->count() > 1)
            <section class="py-16 reveal" id="options">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Choices</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Treatment Options</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($plan->options->sortBy('sort_order') as $option)
                    <div class="relative bg-white border rounded-2xl p-6 shadow-sm flex flex-col
                        {{ $option->is_recommended ? 'border-clinic-accent ring-2 ring-clinic-accent' : 'border-slate-100' }}">
                        @if($option->is_recommended)
                            <span class="absolute -top-3 left-6 bg-clinic-accent text-white text-xs font-bold px-3 py-1 rounded-full">Recommended</span>
                        @endif
                        <h3 class="font-display font-semibold text-clinic-primary text-lg mb-2">{{ $option->option_label }}</h3>
                        @if($option->description)
                            <p class="text-sm text-slate-600 mb-4 leading-relaxed flex-1">{{ $option->description }}</p>
                        @endif
                        @if($option->items->count())
                            <ul class="space-y-1 mb-4">
                                @foreach($option->items->sortBy('position') as $optItem)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-clinic-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $optItem->name }}
                                    @if($optItem->material)
                                        <span class="text-xs text-slate-400 ml-1">{{ $optItem->material->brand ?? '' }}</span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <p class="text-2xl font-bold font-display text-clinic-primary">{{ $plan->currency }} {{ number_format($option->total_amount, 2) }}</p>
                            @if($option->savings_vs_premium && $option->savings_vs_premium > 0)
                                <p class="text-xs text-green-600 mt-1">Save {{ $plan->currency }} {{ number_format($option->savings_vs_premium, 2) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 PRICING BREAKDOWN
            ================================================================ --}}
            @if($plan->items->count())
            <section class="py-16 reveal" id="pricing">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Investment</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Pricing Breakdown</h2>
                <div class="max-w-md">
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                        @php
                            $subtotal = $plan->items->sum('line_total');
                            $optionalTotal = $plan->items->where('is_optional', true)->sum('line_total');
                        @endphp
                        <div class="divide-y divide-slate-100">
                            <div class="flex items-center justify-between px-6 py-4">
                                <span class="text-slate-600 text-sm">Subtotal</span>
                                <span class="font-medium text-slate-800">{{ $plan->currency }} {{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($optionalTotal > 0)
                            <div class="flex items-center justify-between px-6 py-4">
                                <span class="text-slate-600 text-sm">Optional items</span>
                                <span class="font-medium text-slate-400">+ {{ $plan->currency }} {{ number_format($optionalTotal, 2) }}</span>
                            </div>
                            @endif
                            @if($plan->deposit_amount > 0)
                            <div class="flex items-center justify-between px-6 py-4">
                                <span class="text-slate-600 text-sm">Deposit required</span>
                                <span class="font-medium text-amber-700">{{ $plan->currency }} {{ number_format($plan->deposit_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between px-6 py-5 bg-clinic-primary text-white">
                                <span class="font-display font-semibold text-lg">Total Treatment Cost</span>
                                <span class="font-display font-bold text-2xl">{{ $plan->currency }} {{ number_format($plan->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed">All prices are inclusive of materials and clinical fees. Finance options may be available. Please ask us for details.</p>
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 BEFORE AND AFTER GALLERY
            ================================================================ --}}
            @php
                $beforeAfterItems = collect(); // placeholder: would load from plan items
            @endphp
            <section class="py-16 reveal" id="gallery">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Results</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Before &amp; After</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($beforeAfterItems as $case)
                        <div class="space-y-2">
                            <div class="aspect-square rounded-xl overflow-hidden bg-slate-100">
                                <img src="{{ Storage::url($case->before_image_path) }}" alt="Before" loading="lazy" class="w-full h-full object-cover" />
                            </div>
                            <div class="aspect-square rounded-xl overflow-hidden bg-slate-100">
                                <img src="{{ Storage::url($case->after_image_path) }}" alt="After" loading="lazy" class="w-full h-full object-cover" />
                            </div>
                            <p class="text-xs text-slate-400 text-center">{{ $case->title }}</p>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 text-sm">Before and after images will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </section>
            <hr class="border-slate-100" />

            {{-- ================================================================
                 MATERIALS AND BRANDS
            ================================================================ --}}
            @php
                $materials = $plan->items->map(fn($i) => $i->material)->filter()->unique('id');
            @endphp
            @if($materials->count())
            <section class="py-16 reveal" id="materials" x-data="{ compareOpen: false }">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Quality Assurance</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-4">Materials &amp; Brands</h2>
                <p class="text-slate-500 text-sm mb-8 max-w-2xl">We use premium, clinically proven materials for every procedure. Compare the specifications below.</p>

                {{-- Material cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($materials as $material)
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        {{-- Header with image --}}
                        <div class="bg-gradient-to-br from-slate-50 to-white p-6 flex items-center gap-4 border-b border-slate-50">
                            @if($material->image_path)
                                <img src="{{ Storage::url($material->image_path) }}" alt="{{ $material->name }}" loading="lazy" class="h-14 w-14 object-contain rounded-xl bg-white p-1 shadow-sm" />
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center flex-none">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-clinic-primary text-sm">{{ $material->name }}</p>
                                @if($material->brand)
                                    <p class="text-xs text-slate-400 mt-0.5">by {{ $material->brand }}</p>
                                @endif
                                @if($material->is_premium)
                                    <span class="inline-flex items-center gap-1 mt-1.5 text-[10px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                                        Premium
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Specs --}}
                        <div class="p-5 space-y-3">
                            @if($material->category)
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400">Category</span>
                                <span class="font-medium text-slate-700">{{ $material->category }}</span>
                            </div>
                            @endif
                            @if($material->country_of_origin)
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400">Origin</span>
                                <span class="font-medium text-slate-700">{{ $material->country_of_origin }}</span>
                            </div>
                            @endif
                            @if($material->warranty_years)
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400">Warranty</span>
                                <span class="font-semibold text-green-600">{{ $material->warranty_years }} {{ Str::plural('year', $material->warranty_years) }}</span>
                            </div>
                            @endif
                            @if($material->description)
                            <p class="text-xs text-slate-500 leading-relaxed pt-2 border-t border-slate-50">{{ Str::limit($material->description, 120) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Compare table (toggle) --}}
                @if($materials->count() > 1)
                <div class="mt-6">
                    <button @click="compareOpen = !compareOpen" class="inline-flex items-center gap-2 text-sm font-medium text-clinic-accent hover:text-clinic-primary transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span x-text="compareOpen ? 'Hide comparison' : 'Compare all materials'"></span>
                        <svg class="w-4 h-4 transition-transform" :class="compareOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="compareOpen" x-collapse x-cloak class="mt-4">
                        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Specification</th>
                                        @foreach($materials as $material)
                                        <th class="text-center text-xs font-semibold text-clinic-primary px-5 py-3">{{ $material->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr>
                                        <td class="px-5 py-3 text-slate-500 text-xs font-medium">Brand</td>
                                        @foreach($materials as $material)
                                        <td class="px-5 py-3 text-center text-xs text-slate-700 font-medium">{{ $material->brand ?? '—' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="bg-slate-50/50">
                                        <td class="px-5 py-3 text-slate-500 text-xs font-medium">Category</td>
                                        @foreach($materials as $material)
                                        <td class="px-5 py-3 text-center text-xs text-slate-700">{{ $material->category ?? '—' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-3 text-slate-500 text-xs font-medium">Origin</td>
                                        @foreach($materials as $material)
                                        <td class="px-5 py-3 text-center text-xs text-slate-700">{{ $material->country_of_origin ?? '—' }}</td>
                                        @endforeach
                                    </tr>
                                    <tr class="bg-slate-50/50">
                                        <td class="px-5 py-3 text-slate-500 text-xs font-medium">Warranty</td>
                                        @foreach($materials as $material)
                                        <td class="px-5 py-3 text-center text-xs {{ $material->warranty_years ? 'text-green-600 font-semibold' : 'text-slate-400' }}">
                                            {{ $material->warranty_years ? $material->warranty_years . ' ' . Str::plural('year', $material->warranty_years) : '—' }}
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-3 text-slate-500 text-xs font-medium">Grade</td>
                                        @foreach($materials as $material)
                                        <td class="px-5 py-3 text-center">
                                            @if($material->is_premium)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                                                    Premium
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-500">Standard</span>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 GUARANTEES (auto-generated from template data)
            ================================================================ --}}
            @php
                $hasExplicitGuarantee = $plan->sections->where('type', 'guarantee')->where('is_visible', true)->isNotEmpty();
                $templateGuarantees = $hasExplicitGuarantee ? collect() : $plan->items
                    ->filter(fn($i) => $i->template?->guarantee_text)
                    ->map(fn($i) => ['name' => $i->name, 'guarantee' => $i->template->guarantee_text])
                    ->unique('guarantee');
            @endphp
            @if($templateGuarantees->isNotEmpty())
            <section class="py-16 reveal" id="guarantees">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Our Promise</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Treatment Guarantees</h2>
                <div class="space-y-4 max-w-2xl">
                    @foreach($templateGuarantees as $g)
                        <div class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-2xl p-6 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mt-0.5">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-clinic-primary text-sm mb-1">{{ $g['name'] }}</p>
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $g['guarantee'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 CUSTOM SECTIONS (guarantees, FAQs, custom)
            ================================================================ --}}
            @foreach($plan->sections->where('is_visible', true)->sortBy('sort_order') as $section)
            <section class="py-16 reveal" id="section-{{ $section->id }}">
                @if($section->type === 'faq')
                    <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Common Questions</p>
                    <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">{{ $section->title ?? 'Frequently Asked Questions' }}</h2>
                    @php
                        $faqs = is_array($section->content)
                            ? $section->content
                            : (json_decode($section->content, true) ?? []);
                    @endphp
                    @if(is_array($faqs) && isset($faqs[0]['question']))
                        <div class="space-y-3 max-w-2xl" x-data="{ open: null }">
                            @foreach($faqs as $i => $faq)
                            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                                <button
                                    class="w-full text-left px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition"
                                    @click="open = open === {{ $i }} ? null : {{ $i }}">
                                    <span class="font-semibold text-clinic-primary text-sm">{{ $faq['question'] }}</span>
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200"
                                         :class="open === {{ $i }} ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open === {{ $i }}" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-5">
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $faq['answer'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="prose prose-sm max-w-2xl text-slate-700">{!! nl2br(e((string) $section->content)) !!}</div>
                    @endif

                @elseif($section->type === 'guarantee')
                    <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Our Promise</p>
                    <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-6">{{ $section->title ?? 'Guarantees' }}</h2>
                    <div class="max-w-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 rounded-2xl p-8 shadow-sm">
                        <div class="prose prose-sm text-slate-700 leading-relaxed">{!! nl2br(e((string) $section->content)) !!}</div>
                    </div>

                @else
                    @if($section->title)
                        <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-6">{{ $section->title }}</h2>
                    @endif
                    <div class="max-w-2xl prose prose-sm text-slate-700 leading-relaxed">{!! nl2br(e((string) $section->content)) !!}</div>
                @endif
            </section>
            <hr class="border-slate-100" />
            @endforeach

            {{-- ================================================================
                 ACCEPT OR DECLINE
            ================================================================ --}}
            @if(!$isExpired)
            <section class="py-16 reveal" id="decision">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Your Decision</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Accept or Decline</h2>

                @if($plan->status === 'accepted')
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-8 max-w-lg">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-green-800 font-display text-lg">Plan Accepted</p>
                                @if($plan->patient_signed_at)
                                    <p class="text-sm text-green-600">Accepted on {{ $plan->patient_signed_at->format('d M Y \a\t H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm text-green-700">Thank you for accepting this treatment plan. Our team will be in touch shortly to arrange your appointments.</p>
                    </div>

                @elseif($plan->status === 'declined')
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 max-w-lg">
                        <p class="font-semibold text-slate-700 mb-2 font-display text-lg">Plan Declined</p>
                        @if($plan->decline_reason)
                            <p class="text-sm text-slate-500">Reason: {{ $plan->decline_reason }}</p>
                        @endif
                        <p class="text-sm text-slate-500 mt-3">If you change your mind, please contact us and we will be happy to discuss your options.</p>
                    </div>

                @else
                    <div class="grid lg:grid-cols-2 gap-8 max-w-3xl" x-data="{ mode: 'accept' }">
                        {{-- Toggle --}}
                        <div class="lg:col-span-2 flex gap-2">
                            <button @click="mode = 'accept'"
                                class="px-5 py-2 rounded-lg text-sm font-medium transition"
                                :class="mode === 'accept' ? 'bg-clinic-primary text-white shadow' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                Accept Plan
                            </button>
                            <button @click="mode = 'decline'"
                                class="px-5 py-2 rounded-lg text-sm font-medium transition"
                                :class="mode === 'decline' ? 'bg-red-600 text-white shadow' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                Decline Plan
                            </button>
                        </div>

                        {{-- Accept form --}}
                        <div x-show="mode === 'accept'" x-cloak class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">
                            <h3 class="font-display font-semibold text-clinic-primary text-xl mb-6">Accept this Treatment Plan</h3>
                            <form method="POST" action="{{ route('patient.plan.accept', ['token' => $plan->public_token]) }}">
                                @csrf
                                <div class="space-y-5">
                                    <div>
                                        <label for="full_name" class="block text-sm font-medium text-slate-700 mb-2">Your Full Name <span class="text-red-500">*</span></label>
                                        <input type="text"
                                               id="full_name"
                                               name="full_name"
                                               value="{{ old('full_name', $plan->patient->first_name . ' ' . $plan->patient->last_name) }}"
                                               required
                                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clinic-accent focus:border-transparent transition"
                                               placeholder="Enter your full name as it appears on your ID" />
                                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Signature placeholder --}}
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Signature <span class="text-red-500">*</span></label>
                                        <div id="signature-pad-container"
                                             class="w-full h-32 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center text-slate-400 text-sm">
                                            <span class="no-js-message">Signature pad will appear here</span>
                                            <canvas id="signature-pad" class="hidden w-full h-full rounded-xl"></canvas>
                                        </div>
                                        <input type="hidden" name="signature" id="signature-data" value="" />
                                        <div class="flex gap-2 mt-2">
                                            <button type="button" onclick="clearSignature()" class="text-xs text-slate-500 hover:text-slate-700 underline">Clear</button>
                                        </div>
                                        @error('signature') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <input type="checkbox"
                                               id="accept_confirm"
                                               required
                                               class="mt-1 rounded border-slate-300 text-clinic-accent focus:ring-clinic-accent cursor-pointer" />
                                        <label for="accept_confirm" class="text-sm text-slate-600 cursor-pointer leading-relaxed">
                                            I confirm that I have read and understood this treatment plan and I agree to proceed with the proposed treatment as outlined above.
                                        </label>
                                    </div>

                                    @if($errors->any())
                                        <div class="bg-red-50 text-red-700 text-sm rounded-xl px-4 py-3">
                                            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                                        </div>
                                    @endif

                                    <button type="submit"
                                            class="w-full bg-clinic-primary hover:opacity-90 text-white font-semibold py-3.5 px-6 rounded-xl transition text-sm shadow-md">
                                        Accept Treatment Plan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Decline form --}}
                        <div x-show="mode === 'decline'" x-cloak class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">
                            <h3 class="font-display font-semibold text-slate-700 text-xl mb-2">Decline this Plan</h3>
                            <p class="text-sm text-slate-500 mb-6">We are sorry to hear this. Please let us know if there is anything we can do differently.</p>
                            <form method="POST" action="{{ route('patient.plan.decline', ['token' => $plan->public_token]) }}">
                                @csrf
                                <div class="space-y-5">
                                    <div>
                                        <label for="decline_reason" class="block text-sm font-medium text-slate-700 mb-2">Reason (optional)</label>
                                        <textarea name="decline_reason"
                                                  id="decline_reason"
                                                  rows="4"
                                                  maxlength="1000"
                                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-transparent transition resize-none"
                                                  placeholder="e.g. Price too high, going with another clinic...">{{ old('decline_reason') }}</textarea>
                                    </div>
                                    <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3.5 px-6 rounded-xl transition text-sm">
                                        Decline Plan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </section>
            <hr class="border-slate-100" />
            @endif

            {{-- ================================================================
                 COMMENTS
            ================================================================ --}}
            <section class="py-16 reveal" id="comments">
                <p class="text-xs uppercase tracking-widest text-clinic-accent font-semibold mb-3">Conversation</p>
                <h2 class="font-display text-3xl text-clinic-primary font-semibold mb-8">Comments &amp; Questions</h2>
                <div class="max-w-2xl space-y-6">
                    {{-- Existing comments --}}
                    @if($plan->comments->where('is_internal', false)->count())
                        <div class="space-y-4">
                            @foreach($plan->comments->where('is_internal', false) as $comment)
                            <div class="flex gap-4 {{ $comment->author_type === 'patient' ? 'flex-row-reverse' : '' }}">
                                <div class="flex-shrink-0 w-9 h-9 rounded-full {{ $comment->author_type === 'patient' ? 'bg-clinic-accent' : 'bg-clinic-primary' }} flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($comment->author_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="flex-1 max-w-sm">
                                    <div class="{{ $comment->author_type === 'patient' ? 'bg-clinic-accent/10 border-clinic-accent/20' : 'bg-white border-slate-100' }} border rounded-2xl {{ $comment->author_type === 'patient' ? 'rounded-tr-sm' : 'rounded-tl-sm' }} px-5 py-4 shadow-sm">
                                        <p class="text-sm font-semibold {{ $comment->author_type === 'patient' ? 'text-clinic-accent' : 'text-clinic-primary' }} mb-1">
                                            {{ $comment->author_name ?? 'Clinic' }}
                                        </p>
                                        <p class="text-sm text-slate-700 leading-relaxed">{{ $comment->content }}</p>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1 {{ $comment->author_type === 'patient' ? 'text-right' : '' }}">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">No comments yet. Ask us anything about your plan.</p>
                    @endif

                    {{-- New comment form --}}
                    <form method="POST" action="{{ route('patient.plan.comment', ['token' => $plan->public_token]) }}" class="mt-6">
                        @csrf
                        <div class="flex gap-3 items-start">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-clinic-accent flex items-center justify-center text-white text-xs font-bold mt-1">
                                {{ strtoupper(substr($plan->patient->first_name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <textarea name="content"
                                          rows="3"
                                          required
                                          maxlength="2000"
                                          class="w-full border border-slate-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-clinic-accent focus:border-transparent transition resize-none bg-white shadow-sm"
                                          placeholder="Ask a question or leave a comment...">{{ old('content') }}</textarea>
                                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                            class="bg-clinic-primary text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:opacity-90 transition shadow-sm">
                                        Send Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

        </div>{{-- end max-width container --}}

        {{-- ================================================================
             FOOTER
        ================================================================ --}}
        <footer class="mt-8 border-t border-slate-100 py-8 no-print">
            <div class="max-w-[1100px] mx-auto px-6 flex flex-wrap items-center justify-between gap-4 text-sm text-slate-400">
                <p>{{ $plan->clinic->name }}</p>
                @if($freeTier)
                    <a href="https://esagio.com" target="_blank" rel="noopener" class="flex items-center gap-1.5 text-slate-400 hover:text-slate-600 transition">
                        <span>Powered by</span>
                        <span class="font-semibold text-slate-600">Esagio</span>
                    </a>
                @endif
            </div>
        </footer>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('reveal-ready');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visible');
                        observer.unobserve(e.target);
                    }
                });
            }, { threshold: 0.08 });

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        });

        // Signature pad placeholder
        (function () {
            const container = document.getElementById('signature-pad-container');
            const canvas    = document.getElementById('signature-pad');
            const input     = document.getElementById('signature-data');
            if (!container || !canvas) return;

            container.querySelector('.no-js-message').style.display = 'none';
            canvas.classList.remove('hidden');

            // Basic canvas drawing
            const ctx = canvas.getContext('2d');
            let drawing = false;

            function resize() {
                const rect = container.getBoundingClientRect();
                canvas.width  = rect.width;
                canvas.height = rect.height || 128;
                ctx.strokeStyle = '#0A2540';
                ctx.lineWidth   = 2;
                ctx.lineCap     = 'round';
                ctx.lineJoin    = 'round';
            }
            resize();

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const src  = e.touches ? e.touches[0] : e;
                return { x: src.clientX - rect.left, y: src.clientY - rect.top };
            }

            canvas.addEventListener('mousedown',  e => { drawing = true; const p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
            canvas.addEventListener('mousemove',  e => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
            canvas.addEventListener('mouseup',    () => { drawing = false; input.value = canvas.toDataURL(); });
            canvas.addEventListener('touchstart', e => { e.preventDefault(); drawing = true; const p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }, { passive: false });
            canvas.addEventListener('touchmove',  e => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }, { passive: false });
            canvas.addEventListener('touchend',   () => { drawing = false; input.value = canvas.toDataURL(); });

            window.clearSignature = function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                input.value = '';
            };
        })();
    </script>
    @endpush
@endsection
