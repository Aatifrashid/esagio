{{--
    Tooth Chart SVG - Universal Numbering System (1-32)
    Upper: 1-16 (right to left from patient perspective)
    Lower: 17-32 (left to right from patient perspective)
--}}
@php
    $chartMap = $toothCharts->keyBy('tooth_number');

    function toothClass(int $num, $chartMap): string {
        $chart = $chartMap->get($num);
        if (!$chart || empty($chart->conditions)) return 'tooth-healthy';
        $primary = $chart->conditions[0] ?? 'healthy';
        return 'tooth-' . $primary;
    }

    function toothTitle(int $num, $chartMap): string {
        $chart = $chartMap->get($num);
        if (!$chart) return 'Tooth ' . $num;
        $conds = implode(', ', $chart->conditions ?? []);
        return 'Tooth ' . $num . ($conds ? ': ' . $conds : '');
    }
@endphp

<div class="w-full overflow-x-auto">
    <svg viewBox="0 0 440 280" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-sm mx-auto" aria-label="Dental tooth chart">
        <style>
            .tooth-svg { stroke-width: 1.5; rx: 4; }
            .tooth-num { font-family: 'Inter', sans-serif; font-size: 7px; fill: #475569; text-anchor: middle; dominant-baseline: middle; }
            .jaw-label { font-family: 'Fraunces', serif; font-size: 9px; fill: #94a3b8; text-anchor: middle; }
        </style>

        <!-- Upper jaw label -->
        <text x="220" y="14" class="jaw-label">Upper</text>
        <!-- Lower jaw label -->
        <text x="220" y="270" class="jaw-label">Lower</text>

        <!-- Centre line -->
        <line x1="220" y1="20" x2="220" y2="260" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="3,3"/>

        <!-- Upper teeth: 1-16 -->
        @php
            $upperTeeth = array_merge(range(1, 8), range(9, 16));
            // x positions: 8 teeth each side, centred at 220
            // Right side (teeth 1-8): from centre outward to left visually
            // Left side (teeth 9-16): from centre outward to right visually
            $w = 22; $h = 28; $gap = 2;
            $startX = 220 - 8 * ($w + $gap) / 2;
        @endphp
        @foreach(range(1, 16) as $i)
        @php
            $toothNum = $i;
            // teeth 1-8 right side: index 0-7 from right to left
            // teeth 9-16 left side: index 0-7 from left
            if ($i <= 8) {
                $idx = 8 - $i; // 0 = tooth 8 (nearest centre), 7 = tooth 1
            } else {
                $idx = $i - 9; // 0 = tooth 9 (nearest centre), 7 = tooth 16
            }
            $xPos = ($i <= 8)
                ? 220 - ($idx + 1) * ($w + $gap) + $gap
                : 220 + $idx * ($w + $gap) + $gap;
            $yPos = 30;
            $class = toothClass($toothNum, $chartMap);
            $title = toothTitle($toothNum, $chartMap);
        @endphp
        <g class="tooth" role="img" aria-label="{{ $title }}">
            <title>{{ $title }}</title>
            <rect x="{{ $xPos }}" y="{{ $yPos }}" width="{{ $w }}" height="{{ $h }}" rx="4" class="{{ $class }} tooth-svg" />
            <text x="{{ $xPos + $w/2 }}" y="{{ $yPos + $h/2 }}" class="tooth-num">{{ $toothNum }}</text>
        </g>
        @endforeach

        <!-- Lower teeth: 17-32 -->
        @foreach(range(17, 32) as $i)
        @php
            $toothNum = $i;
            // teeth 17-24 left side (visually right from patient view)
            // teeth 25-32 right side (visually left from patient view)
            if ($i <= 24) {
                $idx = $i - 17; // 0 = tooth 17 (nearest centre)
                $xPos = 220 + $idx * ($w + $gap) + $gap;
            } else {
                $idx = 32 - $i; // 0 = tooth 32, 7 = tooth 25 nearest centre
                $xPos = 220 - ($idx + 1) * ($w + $gap) + $gap;
            }
            $yPos = 222;
            $class = toothClass($toothNum, $chartMap);
            $title = toothTitle($toothNum, $chartMap);
        @endphp
        <g class="tooth" role="img" aria-label="{{ $title }}">
            <title>{{ $title }}</title>
            <rect x="{{ $xPos }}" y="{{ $yPos }}" width="{{ $w }}" height="{{ $h }}" rx="4" class="{{ $class }} tooth-svg" />
            <text x="{{ $xPos + $w/2 }}" y="{{ $yPos + $h/2 }}" class="tooth-num">{{ $toothNum }}</text>
        </g>
        @endforeach

        <!-- Arch indicators -->
        <path d="M 44 58 Q 220 130 396 58" fill="none" stroke="#e2e8f0" stroke-width="2"/>
        <path d="M 44 222 Q 220 150 396 222" fill="none" stroke="#e2e8f0" stroke-width="2"/>
    </svg>
</div>
