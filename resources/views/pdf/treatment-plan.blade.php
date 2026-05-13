<!DOCTYPE html>
<html lang="{{ $plan->language ?? 'en' }}">
<head>
<meta charset="UTF-8">
<style>
/* ===== BASE ===== */
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10pt;
    color: #2c2c2c;
    line-height: 1.5;
}
h1 { font-size: 22pt; color: {{ $branding?->primary_colour ?? '#1a73e8' }}; margin-bottom: 8px; }
h2 { font-size: 15pt; color: {{ $branding?->primary_colour ?? '#1a73e8' }}; margin-bottom: 6px; margin-top: 4px; }
h3 { font-size: 11pt; color: #444; margin-bottom: 4px; }
p { margin-bottom: 6px; }
table { width: 100%; border-collapse: collapse; }

/* ===== PAGE BREAKS ===== */
.page-break { page-break-after: always; }

/* ===== COVER PAGE ===== */
.cover {
    text-align: center;
    padding: 60px 30px 40px 30px;
    background-color: {{ $branding?->primary_colour ?? '#1a73e8' }};
    color: #fff;
    min-height: 250mm;
}
.cover .logo-wrap { margin-bottom: 40px; }
.cover .logo-wrap img { max-width: 180px; max-height: 80px; }
.cover .logo-text { font-size: 26pt; font-weight: bold; color: #fff; }
.cover h1 { color: #fff; font-size: 28pt; margin-bottom: 10px; }
.cover .subtitle { font-size: 13pt; color: rgba(255,255,255,0.85); margin-bottom: 30px; }
.cover .meta-box {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    border-radius: 6px;
    padding: 16px 28px;
    margin-top: 20px;
}
.cover .meta-box p { color: #fff; margin-bottom: 4px; font-size: 10pt; }
.cover .meta-box strong { font-size: 11pt; }
.cover .clinic-name { font-size: 12pt; color: rgba(255,255,255,0.7); margin-top: 40px; }

/* ===== SECTION HEADER ===== */
.section-header {
    border-left: 4px solid {{ $branding?->primary_colour ?? '#1a73e8' }};
    padding-left: 10px;
    margin-bottom: 14px;
    margin-top: 20px;
}
.section-header h2 { margin-bottom: 2px; }
.section-header p { color: #777; font-size: 9pt; }

/* ===== WELCOME LETTER ===== */
.letter-body { padding: 10px 0; line-height: 1.7; }
.letter-body p { margin-bottom: 10px; }
.signature-line { margin-top: 24px; }
.signature-line p { margin-bottom: 2px; }

/* ===== TEAM GRID ===== */
.team-grid { width: 100%; }
.team-grid td { width: 33%; vertical-align: top; padding: 10px; }
.team-card { border: 1px solid #e8e8e8; border-radius: 6px; padding: 12px; text-align: center; }
.team-card img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: 8px; }
.team-card .avatar-placeholder {
    width: 70px; height: 70px; border-radius: 50%;
    background: {{ $branding?->primary_colour ?? '#1a73e8' }};
    color: #fff; font-size: 20pt; line-height: 70px;
    margin: 0 auto 8px auto; text-align: center;
}
.team-card h3 { font-size: 10pt; color: #222; margin-bottom: 2px; }
.team-card .role { font-size: 8.5pt; color: {{ $branding?->primary_colour ?? '#1a73e8' }}; }
.team-card .specialisation { font-size: 8pt; color: #777; margin-top: 4px; }
.team-card .years { font-size: 8pt; color: #999; margin-top: 2px; }

/* ===== TOOTH CHART ===== */
.tooth-chart-section { margin-bottom: 16px; }
.chart-legend { margin-top: 12px; }
.chart-legend table td { padding: 3px 8px; font-size: 8.5pt; }
.legend-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }
.tooth-chart-svg { margin: 12px auto; display: block; }
.chart-notes table td { padding: 4px 8px; font-size: 9pt; border-bottom: 1px solid #f0f0f0; }
.chart-notes table th { padding: 5px 8px; font-size: 9pt; background: #f7f7f7; text-align: left; }

/* ===== TREATMENT TABLE ===== */
.treatment-table th {
    background: {{ $branding?->primary_colour ?? '#1a73e8' }};
    color: #fff;
    padding: 8px 10px;
    text-align: left;
    font-size: 9pt;
}
.treatment-table td { padding: 8px 10px; font-size: 9pt; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
.treatment-table tr:nth-child(even) td { background: #fafafa; }
.optional-badge {
    font-size: 7.5pt;
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffc107;
    border-radius: 3px;
    padding: 1px 5px;
    margin-left: 4px;
}
.tooth-tag {
    font-size: 7.5pt;
    background: #e8f0fe;
    color: #1967d2;
    border-radius: 3px;
    padding: 1px 5px;
    margin-right: 2px;
    display: inline-block;
}

/* ===== PRICING ===== */
.pricing-table { width: 60%; margin-left: auto; }
.pricing-table td { padding: 7px 10px; font-size: 10pt; border-bottom: 1px solid #f0f0f0; }
.pricing-table .label-col { color: #555; }
.pricing-table .total-row td { font-weight: bold; font-size: 12pt; border-top: 2px solid #ddd; color: {{ $branding?->primary_colour ?? '#1a73e8' }}; }
.pricing-table .deposit-row td { font-size: 11pt; font-weight: bold; color: #2e7d32; }

/* ===== MATERIALS ===== */
.materials-table th { background: #f5f5f5; color: #333; padding: 7px 10px; font-size: 9pt; text-align: left; }
.materials-table td { padding: 7px 10px; font-size: 9pt; border-bottom: 1px solid #f0f0f0; }

/* ===== GUARANTEES ===== */
.guarantee-block { border: 1px solid #e0e0e0; border-radius: 6px; padding: 14px 16px; margin-bottom: 12px; }
.guarantee-block h3 { color: {{ $branding?->primary_colour ?? '#1a73e8' }}; margin-bottom: 6px; }
.legal-text { font-size: 8.5pt; color: #666; line-height: 1.6; margin-top: 10px; }

/* ===== NEXT STEPS ===== */
.step-row td { padding: 8px 10px; vertical-align: top; border-bottom: 1px solid #f0f0f0; }
.step-number {
    width: 28px; height: 28px; border-radius: 50%;
    background: {{ $branding?->primary_colour ?? '#1a73e8' }};
    color: #fff; font-size: 11pt; font-weight: bold;
    line-height: 28px; text-align: center; display: inline-block;
}
.qr-placeholder {
    border: 2px dashed #ccc;
    border-radius: 6px;
    width: 100px; height: 100px;
    text-align: center;
    padding-top: 28px;
    font-size: 8.5pt;
    color: #aaa;
    margin: 12px auto;
}
.accent-box {
    background: {{ $branding?->accent_colour ?? '#e8f0fe' }};
    border-radius: 6px;
    padding: 14px 16px;
    margin: 14px 0;
}
.info-row { margin-bottom: 6px; }
.info-label { color: #777; font-size: 9pt; }
.info-value { font-weight: bold; font-size: 10pt; }
</style>
</head>
<body>

{{-- ===== PAGE 1: COVER ===== --}}
<div class="cover">
    <div class="logo-wrap">
        @if($branding?->logo_light)
            <img src="{{ Storage::path($branding->logo_light) }}" alt="{{ $clinic->name }}">
        @elseif($clinic->logo_path)
            <img src="{{ Storage::path($clinic->logo_path) }}" alt="{{ $clinic->name }}">
        @else
            <div class="logo-text">{{ $clinic->name }}</div>
        @endif
    </div>

    <h1>Your Treatment Plan</h1>
    <div class="subtitle">A personalised dental treatment proposal</div>

    <div class="meta-box">
        <p><strong>Prepared for:</strong></p>
        <p><strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></p>
        <br>
        <p><strong>Reference:</strong> {{ $plan->reference_number ?? 'PLAN-' . $plan->id }}</p>
        <p><strong>Date:</strong> {{ now()->format('d F Y') }}</p>
        @if($plan->valid_until)
        <p><strong>Valid until:</strong> {{ $plan->valid_until->format('d F Y') }}</p>
        @endif
    </div>

    <div class="clinic-name">{{ $clinic->name }}
        @if($clinic->address) | {{ $clinic->address }}@endif
    </div>
</div>
<div class="page-break"></div>

{{-- ===== PAGE 2: WELCOME LETTER ===== --}}
<div class="section-header">
    <h2>Welcome</h2>
    <p>A personal message from our team</p>
</div>

<div class="letter-body">
    <p>Dear {{ $patient->first_name }},</p>

    @if($sections->where('type', 'welcome_letter')->first())
        {!! $sections->where('type', 'welcome_letter')->first()->content !!}
    @else
    <p>
        Thank you for choosing {{ $clinic->name }} for your dental care. We are delighted to present you
        with this personalised treatment plan, which has been carefully prepared following your
        consultation with our team.
    </p>
    <p>
        This document outlines the recommended treatments, associated costs, and the expected timeline
        for your care. Our goal is to provide you with the highest quality dental treatment in a
        comfortable and professional environment.
    </p>
    <p>
        Please take the time to review this plan carefully. If you have any questions or would like
        to discuss any aspect of your proposed treatment, do not hesitate to contact us. We are here
        to support you every step of the way.
    </p>
    @if($plan->notes_to_patient)
    <p>{{ $plan->notes_to_patient }}</p>
    @endif
    <p>We look forward to welcoming you and helping you achieve the smile you deserve.</p>
    @endif

    <div class="signature-line">
        <p>Warm regards,</p>
        <br>
        <p><strong>The Team at {{ $clinic->name }}</strong></p>
        @if($clinic->email)<p>{{ $clinic->email }}</p>@endif
        @if($clinic->phone)<p>{{ $clinic->phone }}</p>@endif
    </div>
</div>
<div class="page-break"></div>

{{-- ===== PAGE 3: MEET THE TEAM ===== --}}
@if($teamMembers->isNotEmpty())
<div class="section-header">
    <h2>Meet the Team</h2>
    <p>The dedicated professionals who will be caring for you</p>
</div>

@php $teamChunks = $teamMembers->values()->chunk(3); @endphp

@foreach($teamChunks as $chunk)
<table class="team-grid">
    <tr>
        @foreach($chunk as $member)
        <td>
            <div class="team-card">
                @if($member->photo_path)
                    <img src="{{ Storage::path($member->photo_path) }}" alt="{{ $member->first_name }}">
                @else
                    <div class="avatar-placeholder">{{ strtoupper(substr($member->first_name, 0, 1)) }}</div>
                @endif
                <h3>{{ $member->title ? $member->title . ' ' : '' }}{{ $member->first_name }} {{ $member->last_name }}</h3>
                <div class="role">{{ $member->role }}</div>
                @if($member->specialisation)
                <div class="specialisation">{{ $member->specialisation }}</div>
                @endif
                @if($member->years_experience)
                <div class="years">{{ $member->years_experience }} years experience</div>
                @endif
            </div>
        </td>
        @endforeach
        @for($i = $chunk->count(); $i < 3; $i++)
        <td></td>
        @endfor
    </tr>
</table>
@endforeach

<div class="page-break"></div>
@endif

{{-- ===== PAGE 4: DIAGNOSIS AND TOOTH CHART ===== --}}
<div class="section-header">
    <h2>Diagnosis</h2>
    <p>Current assessment of your dental health</p>
</div>

@if($sections->where('type', 'diagnosis')->first())
<div class="letter-body">
    {!! $sections->where('type', 'diagnosis')->first()->content !!}
</div>
@endif

@if($toothCharts->isNotEmpty())
<div class="tooth-chart-section">
    <h3>Dental Chart</h3>

    {{-- 2D SVG Tooth Chart --}}
    <div class="tooth-chart-svg">
        @php
            $chartData = [];
            foreach($toothCharts as $chart) {
                $chartData[$chart->tooth_number] = $chart;
            }
            $primaryCol = $branding?->primary_colour ?? '#1a73e8';
            $accentCol  = $branding?->accent_colour ?? '#4a90d9';

            $conditionColours = [
                'cavity'       => '#e53935',
                'crown'        => '#fb8c00',
                'implant'      => '#1e88e5',
                'extraction'   => '#8e24aa',
                'root_canal'   => '#43a047',
                'bridge'       => '#f4511e',
                'veneer'       => '#00acc1',
                'whitening'    => '#fdd835',
                'filling'      => '#6d4c41',
                'missing'      => '#9e9e9e',
                'healthy'      => '#c8e6c9',
            ];

            $upperTeeth = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
            $lowerTeeth = [48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];

            $toothColourFn = function(array $chartData, int $tooth, array $colours, string $primaryCol): string {
                if (isset($chartData[$tooth])) {
                    $conditions = $chartData[$tooth]->conditions ?? [];
                    if (!empty($conditions)) {
                        $first = is_array($conditions) ? array_values($conditions)[0] : $conditions;
                        return $colours[$first] ?? $primaryCol;
                    }
                    $planned = $chartData[$tooth]->planned_treatments ?? [];
                    if (!empty($planned)) {
                        return $primaryCol;
                    }
                }
                return '#e0e0e0';
            };
        @endphp

        <svg width="520" height="160" viewBox="0 0 520 160" xmlns="http://www.w3.org/2000/svg">
            <!-- Upper label -->
            <text x="260" y="12" text-anchor="middle" font-size="9" fill="#888">Upper Teeth</text>
            <!-- Lower label -->
            <text x="260" y="155" text-anchor="middle" font-size="9" fill="#888">Lower Teeth</text>

            <!-- Midline -->
            <line x1="260" y1="18" x2="260" y2="142" stroke="#ccc" stroke-width="1" stroke-dasharray="3,3"/>

            @php $startX = 20; $toothW = 26; $toothH = 20; $gap = 2; @endphp

            <!-- Upper teeth (row y=20) -->
            @foreach($upperTeeth as $idx => $num)
                @php
                    $x = $startX + $idx * ($toothW + $gap);
                    $col = $toothColourFn($chartData, $num, $conditionColours, $primaryCol);
                @endphp
                <rect x="{{ $x }}" y="20" width="{{ $toothW }}" height="{{ $toothH }}" rx="3" fill="{{ $col }}" stroke="#bbb" stroke-width="0.8"/>
                <text x="{{ $x + $toothW/2 }}" y="34" text-anchor="middle" font-size="6.5" fill="#555">{{ $num }}</text>
            @endforeach

            <!-- Lower teeth (row y=50) -->
            @foreach($lowerTeeth as $idx => $num)
                @php
                    $x = $startX + $idx * ($toothW + $gap);
                    $col = $toothColourFn($chartData, $num, $conditionColours, $primaryCol);
                @endphp
                <rect x="{{ $x }}" y="50" width="{{ $toothW }}" height="{{ $toothH }}" rx="3" fill="{{ $col }}" stroke="#bbb" stroke-width="0.8"/>
                <text x="{{ $x + $toothW/2 }}" y="64" text-anchor="middle" font-size="6.5" fill="#555">{{ $num }}</text>
            @endforeach

            <!-- Legend -->
            @php $legendItems = [['cavity','Cavity','#e53935'],['implant','Implant','#1e88e5'],['crown','Crown','#fb8c00'],['extraction','Extraction','#8e24aa'],['root_canal','Root Canal','#43a047'],['healthy','Healthy','#c8e6c9']]; @endphp
            @foreach($legendItems as $li => $legend)
                @php $lx = 20 + $li * 86; @endphp
                <rect x="{{ $lx }}" y="82" width="10" height="10" rx="2" fill="{{ $legend[2] }}"/>
                <text x="{{ $lx + 13 }}" y="91" font-size="8" fill="#555">{{ $legend[1] }}</text>
            @endforeach
        </svg>
    </div>

    @if($toothCharts->where('notes', '!=', null)->isNotEmpty())
    <div class="chart-notes">
        <table>
            <tr>
                <th>Tooth</th>
                <th>Conditions</th>
                <th>Planned Treatments</th>
                <th>Notes</th>
            </tr>
            @foreach($toothCharts->sortBy('tooth_number') as $chart)
            <tr>
                <td><strong>{{ $chart->tooth_number }}</strong></td>
                <td>{{ implode(', ', $chart->conditions ?? []) }}</td>
                <td>{{ implode(', ', $chart->planned_treatments ?? []) }}</td>
                <td>{{ $chart->notes }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif
</div>
@endif

<div class="page-break"></div>

{{-- ===== PAGE 5: TREATMENT PLAN ITEMS ===== --}}
<div class="section-header">
    <h2>Treatment Plan</h2>
    <p>Your full itemised treatment proposal</p>
</div>

@if($items->isNotEmpty())
<table class="treatment-table">
    <thead>
        <tr>
            <th width="35%">Treatment</th>
            <th width="20%">Teeth</th>
            <th width="15%">Qty</th>
            <th width="15%">Unit Price</th>
            <th width="15%">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>
                <strong>{{ $item->name }}</strong>
                @if($item->is_optional)
                <span class="optional-badge">Optional</span>
                @endif
                @if($item->description)
                <br><span style="color:#777; font-size:8.5pt;">{{ $item->description }}</span>
                @endif
            </td>
            <td>
                @if(!empty($item->tooth_positions))
                    @foreach($item->tooth_positions as $tooth)
                    <span class="tooth-tag">{{ $tooth }}</span>
                    @endforeach
                @else
                    <span style="color:#aaa;">All</span>
                @endif
            </td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $plan->currency ? \App\Services\PriceCalculator\CurrencyConverter::formatAmountStatic((float)$item->unit_price, $plan->currency) : number_format((float)$item->unit_price, 2) }}</td>
            <td><strong>{{ $plan->currency ? \App\Services\PriceCalculator\CurrencyConverter::formatAmountStatic((float)$item->line_total, $plan->currency) : number_format((float)$item->line_total, 2) }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color:#aaa; text-align:center; padding:20px;">No treatment items added to this plan.</p>
@endif

<div class="page-break"></div>

{{-- ===== PAGE 6: PRICING BREAKDOWN ===== --}}
<div class="section-header">
    <h2>Pricing Summary</h2>
    <p>A clear breakdown of all costs</p>
</div>

@php
    $subtotal = $items->where('is_optional', false)->sum('line_total');
    $optionalTotal = $items->where('is_optional', true)->sum('line_total');
    $currency = $plan->currency ?? 'GBP';
    $fmt = fn(float $v) => \App\Services\PriceCalculator\CurrencyConverter::formatAmountStatic($v, $currency);
@endphp

<table class="pricing-table">
    <tr>
        <td class="label-col">Subtotal (required treatments)</td>
        <td align="right">{{ $fmt((float)$subtotal) }}</td>
    </tr>
    @if($optionalTotal > 0)
    <tr>
        <td class="label-col">Optional treatments</td>
        <td align="right">{{ $fmt((float)$optionalTotal) }}</td>
    </tr>
    @endif
    @if($plan->deposit_amount > 0)
    <tr class="deposit-row">
        <td class="label-col">Deposit required</td>
        <td align="right">{{ $fmt((float)$plan->deposit_amount) }}</td>
    </tr>
    @endif
    <tr class="total-row">
        <td>Total</td>
        <td align="right">{{ $fmt((float)$plan->total_amount) }}</td>
    </tr>
</table>

@php $paymentSection = $sections->where('type', 'payment_terms')->first(); @endphp
@if($paymentSection)
<div class="accent-box" style="margin-top: 20px;">
    <h3>Payment Terms</h3>
    <div style="margin-top: 6px;">
        {!! $paymentSection->content !!}
    </div>
</div>
@else
<div class="accent-box" style="margin-top: 20px;">
    <h3>Payment Information</h3>
    <p style="margin-top: 6px; font-size: 9pt; color: #555;">
        A deposit of {{ $plan->deposit_amount > 0 ? $fmt((float)$plan->deposit_amount) : '30%' }}
        is required to confirm your booking. The remaining balance is due on the day of treatment.
        We accept payment by bank transfer, debit card, and credit card.
    </p>
</div>
@endif

<div class="page-break"></div>

{{-- ===== PAGE 7: MATERIALS AND BRANDS ===== --}}
<div class="section-header">
    <h2>Materials and Brands</h2>
    <p>We use only premium-quality materials</p>
</div>

@php $itemsWithMaterial = $items->filter(fn($i) => $i->material); @endphp

@if($itemsWithMaterial->isNotEmpty())
<table class="materials-table">
    <thead>
        <tr>
            <th>Treatment</th>
            <th>Material</th>
            <th>Brand / Variant</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemsWithMaterial as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->material->name }}</td>
            <td>{{ $item->variant_name ?? $item->material->brand ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="color: #777;">
    Our team uses certified, high-grade dental materials sourced from leading international
    suppliers. All materials are biocompatible, CE-marked or FDA-approved where applicable,
    and selected to deliver lasting, aesthetically excellent results.
</p>
@endif

@php $materialsSection = $sections->where('type', 'materials')->first(); @endphp
@if($materialsSection)
<div style="margin-top: 14px;">
    {!! $materialsSection->content !!}
</div>
@endif

<div class="page-break"></div>

{{-- ===== PAGE 8: GUARANTEES AND LEGAL ===== --}}
<div class="section-header">
    <h2>Guarantees and Terms</h2>
    <p>Our commitment to your care</p>
</div>

@php $guaranteesSection = $sections->where('type', 'guarantees')->first(); @endphp

@if($guaranteesSection)
<div class="guarantee-block">
    {!! $guaranteesSection->content !!}
</div>
@else
<div class="guarantee-block">
    <h3>Treatment Guarantee</h3>
    <p>
        All treatments carried out at {{ $clinic->name }} are performed to the highest clinical
        standards. We provide a guarantee on our workmanship for all restorative and cosmetic
        procedures, subject to regular attendance for check-up appointments and adherence to
        aftercare advice.
    </p>
</div>

<div class="guarantee-block">
    <h3>Implant Guarantee</h3>
    <p>
        Dental implants placed at our clinic carry a manufacturer warranty covering the implant
        fixture. Clinical outcomes are subject to good oral hygiene and compliance with post-operative
        care instructions provided at treatment.
    </p>
</div>
@endif

@php $legalSection = $sections->where('type', 'legal')->first(); @endphp
@if($legalSection)
<div class="legal-text">
    {!! $legalSection->content !!}
</div>
@else
<div class="legal-text">
    <p>
        This treatment plan has been prepared based on your clinical examination and any
        radiographs or photographs taken. Prices are subject to change if additional
        treatments are required during the course of care. This plan is valid until
        {{ $plan->valid_until ? $plan->valid_until->format('d F Y') : '90 days from the date of issue' }}.
        Any quoted fees exclude additional investigations, sedation, or emergency appointments
        unless expressly included above. By accepting this plan you confirm that the proposed
        treatments have been explained to you and that you consent to proceed.
    </p>
    @if($clinic->registration_number)
    <p>Registration number: {{ $clinic->registration_number }}
        @if($clinic->vat_number) | VAT: {{ $clinic->vat_number }}@endif
    </p>
    @endif
</div>
@endif

<div class="page-break"></div>

{{-- ===== PAGE 9: NEXT STEPS ===== --}}
<div class="section-header">
    <h2>Next Steps</h2>
    <p>How to proceed with your treatment</p>
</div>

<table>
    <tr class="step-row">
        <td width="50" valign="top">
            <div class="step-number">1</div>
        </td>
        <td valign="top">
            <h3>Review your plan</h3>
            <p>Take your time to read through all the information in this document. Note any questions you have for our team.</p>
        </td>
    </tr>
    <tr class="step-row">
        <td valign="top">
            <div class="step-number">2</div>
        </td>
        <td valign="top">
            <h3>Accept or discuss</h3>
            <p>
                You can accept this plan online or contact us to discuss any changes.
                @if($plan->public_token)
                Visit your personalised plan at: <strong>{{ url('/plans/' . $plan->public_token) }}</strong>
                @endif
            </p>
        </td>
    </tr>
    <tr class="step-row">
        <td valign="top">
            <div class="step-number">3</div>
        </td>
        <td valign="top">
            <h3>Pay your deposit</h3>
            <p>Once you're happy to proceed, a deposit of {{ $plan->deposit_amount > 0 ? $fmt((float)$plan->deposit_amount) : '30%' }} will secure your appointment.</p>
        </td>
    </tr>
    <tr class="step-row">
        <td valign="top">
            <div class="step-number">4</div>
        </td>
        <td valign="top">
            <h3>Attend your appointments</h3>
            <p>Our team will schedule your appointments and guide you through each stage of your treatment journey.</p>
        </td>
    </tr>
</table>

@if($plan->public_token)
<div style="text-align: center; margin-top: 24px;">
    <div class="qr-placeholder">
        Scan to view<br>your plan<br>online
    </div>
    <p style="font-size: 9pt; color: #888; margin-top: 6px;">
        {{ url('/plans/' . $plan->public_token) }}
    </p>
</div>
@endif

@php $nextStepsSection = $sections->where('type', 'next_steps')->first(); @endphp
@if($nextStepsSection)
<div style="margin-top: 14px;">
    {!! $nextStepsSection->content !!}
</div>
@endif

<div style="margin-top: 30px; text-align: center; color: #aaa; font-size: 8.5pt; border-top: 1px solid #eee; padding-top: 12px;">
    {{ $clinic->name }}
    @if($clinic->address) | {{ $clinic->address }}@endif
    @if($clinic->email) | {{ $clinic->email }}@endif
    @if($clinic->phone) | {{ $clinic->phone }}@endif
</div>

@if($isFreeTier)
<div style="text-align: center; margin-top: 10px; color: #ccc; font-size: 8pt;">
    Created with Esagio - esagio.com
</div>
@endif

</body>
</html>
