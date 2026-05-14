@php
    $crownFill = '#f5f0e8';
    $crownHighlight = '#fffef9';
    $rootFill = '#d4b896';
    $rootDark = '#c4a070';
    $enamelLine = '#e8ddd0';
    $outline = '#b8a08a';

    $overlayColour = ($hasConditions && $condColour) ? $condColour : null;
    $selRing = $isSelected ? '#6366f1' : null;
@endphp

{{-- Lower teeth: crown at TOP (y=0), roots point DOWN --}}
{{-- viewBox is 0 0 36 70 --}}

@switch($type)
    @case('central')
        {{-- Crown (top) --}}
        <path d="M12 11 Q12 3 14 1 Q16 -1 18 -1 Q20 -1 22 1 Q24 3 24 11 L24.5 24 Q24.5 28 21 28 L15 28 Q11.5 28 11.5 24 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M14 8 Q16 3 18 3 Q20 3 22 8 L22 20 Q22 25 20 25 L16 25 Q14 25 14 20 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="12" y1="28" x2="24" y2="28" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Single root (down) --}}
        <path d="M15.5 28 Q16 30 16 36 Q15.5 44 16 52 L16.5 62 Q17 68 17.5 68 Q18 68 18.5 68 L19 62 Q19.5 52 20 44 Q20.5 36 20 30 L20.5 28" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M17 32 Q17.5 30 18 32 L18.5 44 Q18.5 56 18 64 Q17.5 56 17.5 44 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        @break

    @case('lateral')
        <path d="M12.5 12 Q12.5 4 14.5 2 Q16 0 18 0 Q20 0 21.5 2 Q23.5 4 23.5 12 L24 24 Q24 28 21 28 L15 28 Q12 28 12 24 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M14.5 9 Q16.5 4 18 4 Q19.5 4 21.5 9 L21.5 20 Q21.5 25 20 25 L16 25 Q14.5 25 14.5 20 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="12.5" y1="28" x2="23.5" y2="28" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        <path d="M15.5 28 Q16 30 16 38 Q16 48 16.5 58 L17 66 Q17.5 69 18 69 Q18.5 69 19 66 L19.5 58 Q20 48 20 38 Q20 30 20.5 28" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M17 32 Q17.5 30 18 32 L18.5 46 Q18.5 58 18 64 Q17.5 58 17.5 46 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        @break

    @case('canine')
        {{-- Pointed crown --}}
        <path d="M11.5 12 Q11.5 4 14 1 Q16 -1 18 -1 Q20 -1 22 1 Q24.5 4 24.5 12 L25 26 Q25 30 22 30 L14 30 Q11 30 11 26 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M14 9 Q16 2 18 2 Q20 2 22 9 L22 22 Q22 27 20 27 L16 27 Q14 27 14 22 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="11.5" y1="30" x2="24.5" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Long root --}}
        <path d="M14.5 30 Q15 32 15 40 Q14.5 50 15.5 58 L16.5 66 Q17 70 18 70 Q19 70 19.5 66 L20.5 58 Q21.5 50 21 40 Q21 32 21.5 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M17 34 Q17.5 32 18 34 L18.5 48 Q18.5 60 18 66 Q17.5 60 17.5 48 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        @break

    @case('premolar1')
        <path d="M9.5 14 Q9.5 6 12.5 3 Q15 0 18 0 Q21 0 23.5 3 Q26.5 6 26.5 14 L27 26 Q27 30 23 30 L13 30 Q9 30 9 26 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M13 11 Q15.5 4 18 4 Q20.5 4 23 11 L23 22 Q23 27 21 27 L15 27 Q13 27 13 22 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="18" y1="2" x2="18" y2="8" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="10" y1="30" x2="26" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Two roots --}}
        <path d="M12.5 30 Q13 32 13 38 Q12.5 46 13 54 L13.5 60 Q14 63 14.5 60 L15 54 Q15.5 46 15 38 Q15 32 15.5 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M20.5 30 Q21 32 21 38 Q20.5 46 21 54 L21.5 60 Q22 63 22.5 60 L23 54 Q23.5 46 23 38 Q23 32 23.5 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        @break

    @case('premolar2')
        <path d="M9.5 14 Q9.5 6 12.5 3 Q15 0 18 0 Q21 0 23.5 3 Q26.5 6 26.5 14 L27 26 Q27 30 23 30 L13 30 Q9 30 9 26 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M13 11 Q15.5 4 18 4 Q20.5 4 23 11 L23 22 Q23 27 21 27 L15 27 Q13 27 13 22 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="18" y1="2" x2="18" y2="7" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="10" y1="30" x2="26" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Single root --}}
        <path d="M15.5 30 Q16 32 16 40 Q15.5 50 16 58 L17 66 Q17.5 69 18 69 Q18.5 69 19 66 L20 58 Q20.5 50 20 40 Q20 32 20.5 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M17 34 Q17.5 32 18 34 L18.5 50 Q18.5 60 18 65 Q17.5 60 17.5 50 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        @break

    @case('molar1')
        {{-- Wide crown --}}
        <path d="M6 14 Q6 5 10 2 Q13 -1 18 -1 Q23 -1 26 2 Q30 5 30 14 L30.5 26 Q30.5 30 26 30 L10 30 Q5.5 30 5.5 26 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M10 10 Q14 2 18 2 Q22 2 26 10 L26 22 Q26 27 23 27 L13 27 Q10 27 10 22 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <path d="M14 1 L16 7 L18 3 L20 7 L22 1" fill="none" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="6" y1="30" x2="30" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Three roots --}}
        <path d="M9 30 Q9.5 32 9.5 38 Q9 46 9.5 54 L10 60 Q10.5 64 11 60 L11.5 54 Q12 46 11.5 38 Q11.5 32 12 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M16 30 Q16.5 32 16.5 36 Q16 42 16.5 48 L17 54 Q17.5 57 18 54 L18.5 48 Q19 42 18.5 36 Q18.5 32 19 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M24 30 Q24.5 32 24.5 38 Q24 46 24.5 54 L25 60 Q25.5 64 26 60 L26.5 54 Q27 46 26.5 38 Q26.5 32 27 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        @break

    @case('molar2')
        <path d="M7 14 Q7 5 11 2 Q14 -1 18 -1 Q22 -1 25 2 Q29 5 29 14 L29.5 26 Q29.5 30 25 30 L11 30 Q6.5 30 6.5 26 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M11 10 Q14.5 2 18 2 Q21.5 2 25 10 L25 22 Q25 27 22 27 L14 27 Q11 27 11 22 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <path d="M15 1 L16.5 6 L18 2 L19.5 6 L21 1" fill="none" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="7" y1="30" x2="29" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Two roots --}}
        <path d="M11 30 Q11.5 32 11.5 40 Q11 48 11.5 56 L12 62 Q12.5 66 13 62 L13.5 56 Q14 48 13.5 40 Q13.5 32 14 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M22 30 Q22.5 32 22.5 40 Q22 48 22.5 56 L23 62 Q23.5 66 24 62 L24.5 56 Q25 48 24.5 40 Q24.5 32 25 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        @break

    @case('molar3')
        <path d="M8.5 16 Q8.5 7 12 3 Q15 0 18 0 Q21 0 24 3 Q27.5 7 27.5 16 L28 26 Q28 30 24 30 L12 30 Q8 30 8 26 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M12 12 Q15 4 18 4 Q21 4 24 12 L24 22 Q24 27 21 27 L15 27 Q12 27 12 22 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="9" y1="30" x2="27" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        {{-- Two fused roots --}}
        <path d="M13 30 Q13.5 32 13.5 38 Q13 46 13.5 52 L14 58 Q14.5 61 15 58 L15 52 Q15 46 15 38 Q15 32 15.5 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M20.5 30 Q21 32 21 38 Q20.5 46 21 52 L21.5 58 Q22 61 22.5 58 L22.5 52 Q23 46 22.5 38 Q22.5 32 23 30" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        @break
@endswitch

{{-- Condition overlay --}}
@if($overlayColour)
    <rect x="4" y="0" width="28" height="70" rx="4" fill="{{ $overlayColour }}" opacity="0.35"/>
@endif

{{-- Selection ring --}}
@if($selRing)
    <rect x="3" y="-1" width="30" height="72" rx="5" fill="none" stroke="{{ $selRing }}" stroke-width="1.5" stroke-dasharray="3 2" opacity="0.6"/>
@endif
