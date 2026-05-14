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

{{-- Upper teeth: roots point UP (y=0 top), crown at bottom --}}
{{-- viewBox is 0 0 36 70 --}}

@switch($type)
    @case('central')
        {{-- Single tapered root --}}
        <path d="M15.5 2 Q17 0 18.5 0 Q20 0 20.5 2 L21 8 Q21.5 16 21 22 L20.5 28 Q20 30 18 30 Q16 30 15.5 28 L15 22 Q14.5 16 15 8 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M16 4 Q17.5 2 19 4 L19.5 12 Q19.8 20 19.5 26 Q18.5 28 17.5 26 Q16.2 20 16.5 12 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        {{-- Crown --}}
        <path d="M12 30 Q12 28 15 27 L21 27 Q24 28 24 30 L24.5 40 Q25 48 23 54 Q21 58 18 59 Q15 58 13 54 Q11 48 11.5 40 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M14 32 Q16 30 18 30 Q20 30 22 32 L22.5 40 Q23 46 21.5 51 Q20 54 18 54 Q16 54 14.5 51 Q13 46 13.5 40 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        {{-- Enamel junction line --}}
        <line x1="12.5" y1="30" x2="23.5" y2="30" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('lateral')
        <path d="M16 3 Q17.5 1 19 1 Q20 1 20.5 3 L21 10 Q21 18 20.5 24 Q20 27 18 28 Q16 27 15.5 24 Q15 18 15 10 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M16.5 5 Q18 3 19 5 L19.5 14 Q19.5 22 19 25 Q18 26.5 17 25 Q16.5 22 16.5 14 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        <path d="M12.5 28 Q12.5 26 15 25.5 L21 25.5 Q23.5 26 23.5 28 L24 38 Q24 46 22 51 Q20 55 18 55 Q16 55 14 51 Q12 46 12 38 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M14.5 30 Q16.5 28.5 18 28.5 Q19.5 28.5 21.5 30 L22 38 Q22 44 20.5 48 Q19.5 51 18 51 Q16.5 51 15.5 48 Q14 44 14 38 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="13" y1="28" x2="23" y2="28" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('canine')
        {{-- Long single root --}}
        <path d="M15 1 Q17 -1 19 -1 Q21 -1 21.5 1 L22 10 Q22.5 20 22 28 L21.5 32 Q20 34 18 34 Q16 34 14.5 32 L14 28 Q13.5 20 14 10 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M16 3 Q17.5 1 19 3 L19.5 14 Q20 24 19.5 30 Q18.5 32 17.5 30 Q16 24 16.5 14 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        {{-- Pointed crown --}}
        <path d="M11 34 Q11 32 14 31 L22 31 Q25 32 25 34 L25 44 Q25 52 22 57 Q20 62 18 62 Q16 62 14 57 Q11 52 11 44 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M14 36 Q16 34 18 34 Q20 34 22 36 L22 44 Q22 50 20.5 54 Q19 57 18 57 Q17 57 15.5 54 Q14 50 14 44 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="11.5" y1="34" x2="24.5" y2="34" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('premolar1')
        {{-- Two diverging roots --}}
        <path d="M11 3 Q12 0 13.5 0 Q14.5 0 15 3 L15.5 10 Q16 18 15.5 24 Q15 26 13 26 Q11.5 24 12 18 Q12 10 11.5 6 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M21 3 Q22 0 23.5 0 Q24.5 0 25 3 L24.5 10 Q24 18 24.5 24 Q25 26 23 26 Q21.5 24 22 18 Q22 10 21.5 6 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        {{-- Wider crown with cusps --}}
        <path d="M9 26 Q9 24 12 23 L24 23 Q27 24 27 26 L27.5 36 Q28 44 26 48 Q23 53 18 53 Q13 53 10 48 Q8 44 8.5 36 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M12 28 Q15 25.5 18 25.5 Q21 25.5 24 28 L24.5 36 Q25 42 23.5 46 Q21 49 18 49 Q15 49 12.5 46 Q11 42 11.5 36 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        {{-- Cusp groove --}}
        <line x1="18" y1="26" x2="18" y2="32" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="9.5" y1="26" x2="26.5" y2="26" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('premolar2')
        {{-- Single root --}}
        <path d="M15.5 2 Q17 0 18.5 0 Q20 0 20.5 2 L21 10 Q21.5 18 21 24 Q20 26 18 26 Q16 26 15 24 Q14.5 18 15 10 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M16.5 4 Q18 2 19 4 L19.5 14 Q19.5 20 19 23 Q18 24.5 17 23 Q16.5 20 16.5 14 Z" fill="{{ $rootDark }}" opacity="0.3"/>
        <path d="M9 26 Q9 24 12 23 L24 23 Q27 24 27 26 L27.5 36 Q28 44 26 48 Q23 53 18 53 Q13 53 10 48 Q8 44 8.5 36 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M12 28 Q15 25.5 18 25.5 Q21 25.5 24 28 L24.5 36 Q25 42 23.5 46 Q21 49 18 49 Q15 49 12.5 46 Q11 42 11.5 36 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="18" y1="26" x2="18" y2="31" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="9.5" y1="26" x2="26.5" y2="26" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('molar1')
        {{-- Three roots (two buccal + one palatal) --}}
        <path d="M8 2 Q9.5 -1 11 0 Q12 0 12.5 3 L13 10 Q13 18 12.5 22 Q12 24 10.5 24 Q9 22 9 18 Q8.5 10 8.5 5 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M16 3 Q17.5 0 19 0 Q20.5 0 20.5 3 L20 10 Q19.5 16 20 20 Q20 22 18 22 Q16 22 16 20 Q16.5 16 16.5 10 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M24 2 Q25.5 -1 27 0 Q28 0 28 3 L27.5 10 Q27 18 27.5 22 Q28 24 26.5 24 Q25 22 25 18 Q24.5 10 24.5 5 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        {{-- Wide crown --}}
        <path d="M6 24 Q6 22 10 21 L26 21 Q30 22 30 24 L30.5 34 Q31 42 29 47 Q26 52 18 52 Q10 52 7 47 Q5 42 5.5 34 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M10 26 Q14 23.5 18 23.5 Q22 23.5 26 26 L26.5 34 Q27 40 25.5 44 Q23 48 18 48 Q13 48 10.5 44 Q9 40 9.5 34 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        {{-- Cusp grooves --}}
        <path d="M14 24 L16 30 L18 26 L20 30 L22 24" fill="none" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="6.5" y1="24" x2="29.5" y2="24" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('molar2')
        {{-- Two roots --}}
        <path d="M10 2 Q11.5 -1 13 0 Q14 0 14 3 L13.5 10 Q13 18 13 22 Q13 24 11.5 24 Q10 22 10 18 Q10 10 10 5 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M22 2 Q23.5 -1 25 0 Q26 0 26 3 L25.5 10 Q25 18 25 22 Q25 24 23.5 24 Q22 22 22 18 Q22 10 22 5 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M7 24 Q7 22 11 21 L25 21 Q29 22 29 24 L29.5 34 Q30 42 28 47 Q25 52 18 52 Q11 52 8 47 Q6 42 6.5 34 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M11 26 Q14.5 23.5 18 23.5 Q21.5 23.5 25 26 L25.5 34 Q26 40 24.5 44 Q22 48 18 48 Q14 48 11.5 44 Q10 40 10.5 34 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <path d="M15 24 L16.5 29 L18 25 L19.5 29 L21 24" fill="none" stroke="{{ $enamelLine }}" stroke-width="0.5" opacity="0.5"/>
        <line x1="7.5" y1="24" x2="28.5" y2="24" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
        @break

    @case('molar3')
        {{-- Two shorter fused roots --}}
        <path d="M12 4 Q13.5 1 15 2 Q16 2 16 5 L15.5 12 Q15 18 15 22 Q15 24 13.5 24 Q12 22 12 18 Q12 12 12 7 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M20 4 Q21.5 1 23 2 Q24 2 24 5 L23.5 12 Q23 18 23 22 Q23 24 21.5 24 Q20 22 20 18 Q20 12 20 7 Z" fill="{{ $rootFill }}" stroke="{{ $outline }}" stroke-width="0.6"/>
        <path d="M8 24 Q8 22 12 21 L24 21 Q28 22 28 24 L28 34 Q28 42 26 46 Q23 50 18 50 Q13 50 10 46 Q8 42 8 34 Z" fill="{{ $crownFill }}" stroke="{{ $outline }}" stroke-width="0.8"/>
        <path d="M12 26 Q15 23.5 18 23.5 Q21 23.5 24 26 L24 34 Q24 40 22.5 43 Q21 46 18 46 Q15 46 13.5 43 Q12 40 12 34 Z" fill="{{ $crownHighlight }}" opacity="0.6"/>
        <line x1="8.5" y1="24" x2="27.5" y2="24" stroke="{{ $enamelLine }}" stroke-width="0.8" opacity="0.7"/>
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
