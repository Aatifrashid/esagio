<x-layouts.marketing>
    <x-slot:title>Interactive Demo</x-slot:title>
    <x-slot:description>Try the Esagio treatment plan builder without signing up. See how it works for your clinic.</x-slot:description>

    {{-- Demo banner --}}
    <div class="bg-accent text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span class="font-semibold text-lg font-display">Try Esagio - Interactive Demo</span>
            </div>
            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-2.5 bg-white text-accent font-semibold rounded-[5px] hover:bg-gray-100 transition text-sm">
                Sign Up Free
            </a>
        </div>
    </div>

    {{-- Demo notice --}}
    <div class="bg-amber-50 border-b border-amber-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-2 text-sm text-amber-800">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>This is a demo with sample data. Saving, sending and exporting are disabled. <a href="{{ route('register') }}" class="font-semibold underline hover:text-amber-900">Create a free account</a> to build real plans.</span>
            </div>
        </div>
    </div>

    {{-- Plan builder demo content --}}
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Plan header --}}
            <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold font-display text-navy tracking-tight">Treatment Plan</h1>
                        <p class="text-gray-500 mt-1 font-mono text-sm">{{ $demoClinic['name'] }} &middot; Ref: DEMO-001</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="badge badge-outline">Draft</span>
                        <button disabled class="btn btn-outline" title="Disabled in demo mode">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                            Save
                        </button>
                        <button disabled class="btn btn-outline" title="Disabled in demo mode">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Send to Patient
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left column: Patient + Tooth Chart --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Patient info --}}
                    <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold font-display text-navy mb-4">Patient Information</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 font-mono text-xs uppercase tracking-wider">Name</span>
                                <p class="font-medium text-gray-900">{{ $demoPatient['first_name'] }} {{ $demoPatient['last_name'] }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 font-mono text-xs uppercase tracking-wider">Email</span>
                                <p class="font-medium text-gray-900">{{ $demoPatient['email'] }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 font-mono text-xs uppercase tracking-wider">Phone</span>
                                <p class="font-medium text-gray-900">{{ $demoPatient['phone'] }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 font-mono text-xs uppercase tracking-wider">Date of Birth</span>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($demoPatient['date_of_birth'])->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tooth chart --}}
                    <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold font-display text-navy mb-4">Tooth Chart</h2>
                        <p class="text-sm text-gray-500 mb-6">Interactive dental chart showing current conditions and planned treatments.</p>

                        <div class="relative mx-auto" style="max-width: 700px;">
                            {{-- Upper arch --}}
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 text-center mb-2 font-mono uppercase tracking-wider">Upper Arch</p>
                                <div class="flex justify-center gap-1">
                                    @foreach([18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28] as $tooth)
                                        @php
                                            $condition = collect($toothChartConditions)->firstWhere('tooth_number', $tooth);
                                            $bgClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                            if ($condition) {
                                                if (in_array('decay', $condition['conditions'])) {
                                                    $bgClass = 'bg-red-100 text-red-700 border-red-300';
                                                } elseif (in_array('missing', $condition['conditions'])) {
                                                    $bgClass = 'bg-gray-300 text-gray-500 border-gray-400 line-through';
                                                } elseif (in_array('crown_needed', $condition['conditions'])) {
                                                    $bgClass = 'bg-amber-100 text-amber-700 border-amber-300';
                                                }
                                            }
                                        @endphp
                                        <div class="w-9 h-10 rounded-[5px] border-2 flex items-center justify-center text-xs font-semibold cursor-pointer hover:ring-2 hover:ring-accent/50 transition {{ $bgClass }}"
                                             title="{{ $condition ? $condition['notes'] : 'Tooth ' . $tooth . ': No issues' }}">
                                            {{ $tooth }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Lower arch --}}
                            <div>
                                <div class="flex justify-center gap-1">
                                    @foreach([48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38] as $tooth)
                                        @php
                                            $condition = collect($toothChartConditions)->firstWhere('tooth_number', $tooth);
                                            $bgClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                            if ($condition) {
                                                if (in_array('decay', $condition['conditions'])) {
                                                    $bgClass = 'bg-red-100 text-red-700 border-red-300';
                                                } elseif (in_array('missing', $condition['conditions'])) {
                                                    $bgClass = 'bg-gray-300 text-gray-500 border-gray-400 line-through';
                                                } elseif (in_array('crown_needed', $condition['conditions'])) {
                                                    $bgClass = 'bg-amber-100 text-amber-700 border-amber-300';
                                                }
                                            }
                                        @endphp
                                        <div class="w-9 h-10 rounded-[5px] border-2 flex items-center justify-center text-xs font-semibold cursor-pointer hover:ring-2 hover:ring-accent/50 transition {{ $bgClass }}"
                                             title="{{ $condition ? $condition['notes'] : 'Tooth ' . $tooth . ': No issues' }}">
                                            {{ $tooth }}
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-400 text-center mt-2 font-mono uppercase tracking-wider">Lower Arch</p>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div class="flex flex-wrap justify-center gap-4 mt-6 text-xs">
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-[3px] bg-gray-100 border-2 border-gray-200"></div>
                                <span class="text-gray-500">Healthy</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-[3px] bg-red-100 border-2 border-red-300"></div>
                                <span class="text-gray-500">Decay</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-[3px] bg-gray-300 border-2 border-gray-400"></div>
                                <span class="text-gray-500">Missing</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-4 h-4 rounded-[3px] bg-amber-100 border-2 border-amber-300"></div>
                                <span class="text-gray-500">Crown Needed</span>
                            </div>
                        </div>
                    </div>

                    {{-- Treatment items --}}
                    <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold font-display text-navy mb-4">Treatment Items</h2>
                        <div class="space-y-4">
                            @foreach($treatmentItems as $item)
                                <div class="border border-gray-200 rounded-[5px] p-4 hover:border-accent/30 transition {{ $item['is_optional'] ? 'border-dashed' : '' }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-semibold text-gray-900">{{ $item['name'] }}</h3>
                                                @if($item['is_optional'])
                                                    <span class="badge badge-outline text-[10px]">Optional</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">{{ $item['description'] }}</p>
                                            @if(!empty($item['tooth_positions']))
                                                <p class="text-xs text-gray-400 mt-2 font-mono">Tooth: {{ implode(', ', $item['tooth_positions']) }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-lg font-semibold text-navy">&pound;{{ number_format($item['line_total'], 2) }}</p>
                                            <p class="text-xs text-gray-400 font-mono">Qty: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Before/After images --}}
                    <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold font-display text-navy mb-4">Before &amp; After Examples</h2>
                        <p class="text-sm text-gray-500 mb-6">Sample reference images showing expected treatment outcomes.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($beforeAfterImages as $image)
                                <div class="border border-gray-200 rounded-[5px] overflow-hidden">
                                    <div class="grid grid-cols-2 gap-px bg-gray-200">
                                        <div class="bg-gray-100 aspect-[4/3] flex items-center justify-center text-gray-400 text-sm">
                                            <div class="text-center p-4">
                                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                Before
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 aspect-[4/3] flex items-center justify-center text-gray-400 text-sm">
                                            <div class="text-center p-4">
                                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                After
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="font-semibold text-sm text-gray-900">{{ $image['title'] }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $image['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right column: Summary --}}
                <div class="space-y-6">
                    {{-- Plan summary --}}
                    <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold font-display text-navy mb-4">Plan Summary</h2>

                        <div class="space-y-3 text-sm">
                            @foreach($treatmentItems as $item)
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-gray-600 {{ $item['is_optional'] ? 'italic' : '' }}">
                                        {{ $item['name'] }}
                                        @if($item['is_optional'])
                                            <span class="text-xs text-gray-400">(optional)</span>
                                        @endif
                                    </span>
                                    <span class="font-medium text-gray-900 flex-shrink-0 font-mono">&pound;{{ number_format($item['line_total'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 mt-4 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Total (excl. optional)</span>
                                <span class="font-display text-xl font-bold text-navy tracking-tight">&pound;{{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <button disabled class="btn btn-primary w-full" title="Disabled in demo mode">
                                Accept Plan (Demo)
                            </button>
                            <button disabled class="btn btn-outline w-full" title="Disabled in demo mode">
                                Request Changes (Demo)
                            </button>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 rounded-[5px]">
                            <p class="text-xs text-blue-700">
                                <strong>Demo mode:</strong> This plan uses sample data. Create a free account to build and send real treatment plans to your patients.
                            </p>
                        </div>
                    </div>

                    {{-- Clinic info --}}
                    <div class="bg-white rounded-[5px] shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Clinic Details</h3>
                        <div class="text-sm text-gray-600 space-y-1.5">
                            <p class="font-medium">{{ $demoClinic['name'] }}</p>
                            <p>{{ $demoClinic['email'] }}</p>
                            <p>{{ $demoClinic['phone'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom CTA --}}
    <section class="bg-navy text-white py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl font-extrabold tracking-tight mb-4">Ready to build plans that close cases?</h2>
            <p class="text-gray-300 mb-8 text-lg">Start free, no credit card required. Upgrade when you are ready.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start Free Today</a>
                <a href="{{ route('pricing') }}" class="btn btn-slate btn-lg">View Pricing</a>
            </div>
        </div>
    </section>
</x-layouts.marketing>
