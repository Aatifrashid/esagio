<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 20mm 15mm 25mm 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            line-height: 1.5;
        }
        .header {
            border-bottom: 3px solid {{ $accentColour }};
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .clinic-name {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #888;
            margin-bottom: 4px;
        }
        .plan-title {
            font-size: 22px;
            font-weight: bold;
            color: {{ $primaryColour }};
            margin: 0 0 4px 0;
        }
        .plan-subtitle {
            font-size: 13px;
            color: #555;
            font-style: italic;
            margin: 0 0 8px 0;
        }
        .meta {
            font-size: 9px;
            color: #888;
        }
        .meta span { margin-right: 15px; }

        h2 {
            font-size: 15px;
            color: {{ $primaryColour }};
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 6px;
            margin: 25px 0 12px 0;
        }
        h2 .label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: {{ $accentColour }};
            display: block;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .welcome-text {
            font-size: 11px;
            color: #444;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .item-table th {
            background: {{ $primaryColour }};
            color: #fff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 10px;
            text-align: left;
        }
        .item-table th:last-child,
        .item-table td:last-child {
            text-align: right;
        }
        .item-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }
        .item-table tr:nth-child(even) td {
            background: #fafafa;
        }
        .item-name {
            font-weight: 600;
            color: {{ $primaryColour }};
        }
        .item-desc {
            font-size: 9px;
            color: #777;
            margin-top: 2px;
        }
        .item-badge {
            display: inline-block;
            font-size: 7px;
            padding: 1px 5px;
            border-radius: 3px;
            margin-left: 4px;
            font-weight: 600;
        }
        .badge-optional {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-material {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .total-row {
            background: {{ $primaryColour }} !important;
            color: #fff !important;
        }
        .total-row td {
            border: none !important;
            font-weight: bold;
            font-size: 13px;
            padding: 10px;
        }

        .steps-box {
            background: #f8f9fa;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 10px 12px;
            margin: 8px 0;
            page-break-inside: avoid;
        }
        .steps-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .step {
            font-size: 9.5px;
            color: #555;
            margin-bottom: 3px;
            padding-left: 5px;
        }
        .step-num {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: {{ $accentColour }}22;
            color: {{ $accentColour }};
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            line-height: 16px;
            margin-right: 5px;
        }

        .recovery-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 4px;
            padding: 8px 12px;
            margin: 5px 0 15px 0;
            page-break-inside: avoid;
        }
        .recovery-title {
            font-size: 9px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 3px;
        }
        .recovery-text {
            font-size: 9px;
            color: #666;
        }

        .guarantee-item {
            border-left: 3px solid {{ $accentColour }};
            padding: 6px 10px;
            margin-bottom: 8px;
            background: #f0fdf4;
        }
        .guarantee-name {
            font-size: 10px;
            font-weight: 600;
            color: {{ $primaryColour }};
        }
        .guarantee-text {
            font-size: 9px;
            color: #555;
        }

        .pricing-summary {
            width: 280px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .pricing-summary td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .pricing-summary .total-row td {
            font-size: 14px;
            padding: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8px;
            color: #aaa;
            text-align: center;
            padding: 8px 15mm;
            border-top: 1px solid #eee;
        }

        .disclaimer {
            font-size: 8px;
            color: #999;
            margin-top: 20px;
            line-height: 1.6;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid #e5e5e5;
            margin: 20px 0;
        }

        .phase-header {
            font-size: 12px;
            font-weight: 600;
            color: {{ $primaryColour }};
            margin: 15px 0 8px 0;
            padding: 4px 8px;
            background: {{ $accentColour }}11;
            border-left: 3px solid {{ $accentColour }};
        }
    </style>
</head>
<body>
    <div class="footer">
        {{ $plan->clinic->name }} &bull; {{ $plan->reference_number }} &bull; Generated {{ now()->format('d M Y') }} &bull; Page {PAGENO} of {nbpg}
    </div>

    {{-- HEADER --}}
    <div class="header">
        <div class="clinic-name">{{ $plan->clinic->name }}</div>
        <h1 class="plan-title">Treatment Plan</h1>
        @if($plan->title)
            <p class="plan-subtitle">{{ $plan->title }}</p>
        @endif
        <div class="meta">
            <span>Patient: {{ $plan->patient->first_name }} {{ $plan->patient->last_name }}</span>
            <span>Ref: {{ $plan->reference_number }}</span>
            <span>Date: {{ $plan->created_at->format('d M Y') }}</span>
            @if($plan->valid_until)
                <span>Valid until: {{ $plan->valid_until->format('d M Y') }}</span>
            @endif
        </div>
    </div>

    {{-- WELCOME MESSAGE --}}
    @php
        $welcomeSection = $plan->sections->where('type', 'welcome')->where('is_visible', true)->first();
    @endphp
    @if($welcomeSection && $welcomeSection->body)
        <div class="welcome-text">{!! nl2br(e($welcomeSection->body)) !!}</div>
        <hr class="section-divider">
    @endif

    {{-- TREATMENT ITEMS --}}
    @if($plan->items->count())
        <h2><span class="label">Proposed Treatment</span>Your Treatment Plan</h2>

        @php
            $phases = $plan->phases->keyBy('phase_number');
            $grouped = $plan->items->groupBy('procedure_phase');
        @endphp

        @foreach($grouped as $phaseNumber => $items)
            @if($phases->has($phaseNumber) && $plan->phases->count() > 1)
                <div class="phase-header">Phase {{ $phaseNumber }}: {{ $phases[$phaseNumber]->name }}</div>
            @endif

            <table class="item-table">
                <thead>
                    <tr>
                        <th style="width:45%">Treatment</th>
                        <th style="width:10%">Qty</th>
                        <th style="width:20%">Unit Price</th>
                        <th style="width:25%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items->sortBy('position') as $item)
                    @php $tpl = $item->template; @endphp
                    <tr>
                        <td>
                            <span class="item-name">{{ $item->name }}</span>
                            @if($item->is_optional)<span class="item-badge badge-optional">Optional</span>@endif
                            @if($item->material)<span class="item-badge badge-material">{{ $item->material->brand ?? $item->material->name }}</span>@endif
                            @php $desc = $tpl?->description_short ?: $item->description; @endphp
                            @if($desc)<div class="item-desc">{{ Str::limit($desc, 120) }}</div>@endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $plan->currency }} {{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $plan->currency }} {{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Procedure steps & recovery for each item --}}
            @foreach($items->sortBy('position') as $item)
                @php $tpl = $item->template; @endphp
                @if($tpl?->procedure_steps && is_array($tpl->procedure_steps) && count($tpl->procedure_steps) > 0)
                    <div class="steps-box">
                        <div class="steps-title">{{ $item->name }} — How it works</div>
                        @foreach($tpl->procedure_steps as $step)
                            <div class="step"><span class="step-num">{{ $loop->iteration }}</span> {{ $step }}</div>
                        @endforeach
                    </div>
                @endif
                @if($tpl?->recovery_info)
                    <div class="recovery-box">
                        <div class="recovery-title">{{ $item->name }} — Recovery</div>
                        <div class="recovery-text">{{ $tpl->recovery_info }}</div>
                    </div>
                @endif
            @endforeach
        @endforeach

        <hr class="section-divider">

        {{-- PRICING SUMMARY --}}
        <h2><span class="label">Investment</span>Pricing Summary</h2>
        @php
            $subtotal = $plan->items->sum('line_total');
            $optionalTotal = $plan->items->where('is_optional', true)->sum('line_total');
        @endphp
        <table class="pricing-summary">
            <tr>
                <td>Subtotal</td>
                <td style="text-align:right">{{ $plan->currency }} {{ number_format($subtotal, 2) }}</td>
            </tr>
            @if($optionalTotal > 0)
            <tr>
                <td style="color:#999">Optional items included</td>
                <td style="text-align:right; color:#999">+ {{ $plan->currency }} {{ number_format($optionalTotal, 2) }}</td>
            </tr>
            @endif
            @if($plan->deposit_amount > 0)
            <tr>
                <td>Deposit required</td>
                <td style="text-align:right; color:#92400e">{{ $plan->currency }} {{ number_format($plan->deposit_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total</td>
                <td style="text-align:right">{{ $plan->currency }} {{ number_format($plan->total_amount, 2) }}</td>
            </tr>
        </table>
        <p style="font-size:8px; color:#999; margin-top:5px;">All prices are inclusive of materials and clinical fees.</p>
    @endif

    {{-- GUARANTEES --}}
    @php
        $templateGuarantees = $plan->items
            ->filter(fn($i) => $i->template?->guarantee_text)
            ->map(fn($i) => ['name' => $i->name, 'guarantee' => $i->template->guarantee_text])
            ->unique('guarantee');
    @endphp
    @if($templateGuarantees->isNotEmpty())
        <hr class="section-divider">
        <h2><span class="label">Our Promise</span>Treatment Guarantees</h2>
        @foreach($templateGuarantees as $g)
            <div class="guarantee-item">
                <div class="guarantee-name">{{ $g['name'] }}</div>
                <div class="guarantee-text">{{ $g['guarantee'] }}</div>
            </div>
        @endforeach
    @endif

    {{-- LEGAL / DISCLAIMER --}}
    <div class="disclaimer">
        @php
            $legalSections = $plan->sections->where('type', 'legal')->where('is_visible', true);
        @endphp
        @foreach($legalSections as $legal)
            <strong>{{ $legal->heading }}</strong><br>
            {{ $legal->body }}<br><br>
        @endforeach
        Results may vary between patients. All treatment is subject to a full clinical assessment. This treatment plan does not constitute a guarantee of outcome.
    </div>
</body>
</html>
