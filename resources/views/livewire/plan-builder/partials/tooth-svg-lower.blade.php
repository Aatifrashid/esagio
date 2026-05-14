@php
    $fill = $hasConditions && $condColour ? $condColour : ($isSelected ? '#e0e7ff' : '#f8fafc');
    $stroke = $hasConditions && $condColour ? $condColour : ($isSelected ? '#6366f1' : '#cbd5e1');
    $rootStroke = $hasConditions && $condColour ? $condColour : '#d1d5db';
    $opacity = $hasConditions && $condColour ? '0.25' : '1';
@endphp

{{-- Lower teeth: crown at TOP, roots point DOWN --}}
@switch($type)
    @case('central')
        <path d="M10 12 Q10 4 12 2 Q16 -1 18 -1 Q20 -1 24 2 Q26 4 26 12 L27 28 Q27 32 22 32 L14 32 Q9 32 9 28 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M16 32 Q17 34 17 42 L17.5 62 Q18 68 18.5 62 L19 42 Q19 34 20 32" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.8"/>
        @break
    @case('lateral')
        <path d="M11 14 Q11 6 13 3 Q16 0 18 0 Q20 0 23 3 Q25 6 25 14 L26 30 Q26 34 22 34 L14 34 Q10 34 10 30 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M16.5 34 Q17 36 17.5 44 L18 64 Q18.5 66 19 64 L19.5 44 Q19.5 36 19.5 34" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.8"/>
        @break
    @case('canine')
        <path d="M11 10 Q11 4 14 1 Q16 -1 18 -1 Q20 -1 22 1 Q25 4 25 10 L25 30 Q25 34 22 34 L14 34 Q11 34 11 30 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M16 34 Q17 36 17 42 L17.5 64 Q18 68 18.5 64 L19 42 Q19 36 20 34" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.8"/>
        @break
    @case('premolar1')
        <path d="M9 16 Q9 8 12 4 Q15 0 18 0 Q21 0 24 4 Q27 8 27 16 L27 32 Q27 36 23 36 L13 36 Q9 36 9 32 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M14.5 36 Q15 38 15 46 L15.5 60 Q16 62 16.5 60 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M20.5 36 Q21 38 21 46 L21.5 60 Q22 62 22.5 60 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        @break
    @case('premolar2')
        <path d="M9 16 Q9 8 12 4 Q15 0 18 0 Q21 0 24 4 Q27 8 27 16 L27 32 Q27 36 23 36 L13 36 Q9 36 9 32 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M17.5 36 Q18 38 18 46 L18.5 62 Q19 64 19.5 62 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        @break
    @case('molar1')
        <path d="M7 14 Q7 6 11 2 Q14 -1 18 -1 Q22 -1 25 2 Q29 6 29 14 L29 34 Q29 38 24 38 L12 38 Q7 38 7 34 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M13 38 Q13.5 40 13.5 48 L14 60 Q14 62 14.5 60 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M18.5 38 Q19 40 19 46 L19.5 56 Q19.5 58 20 56 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M23.5 38 Q24 40 24 48 L24.5 60 Q24.5 62 25 60 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        @break
    @case('molar2')
        <path d="M8 14 Q8 6 12 2 Q15 -1 18 -1 Q21 -1 24 2 Q28 6 28 14 L28 34 Q28 38 24 38 L12 38 Q8 38 8 34 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M13.5 38 Q14 40 14 48 L14.5 60 Q14.5 62 15 60 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M21.5 38 Q22 40 22 48 L22.5 60 Q22.5 62 23 60 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        @break
    @case('molar3')
        <path d="M9 16 Q9 8 13 4 Q15 1 18 1 Q21 1 23 4 Q27 8 27 16 L27 34 Q27 38 23 38 L13 38 Q9 38 9 34 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        <path d="M14.5 38 Q15 40 15 46 L15.5 58 Q15.5 60 16 58 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M20.5 38 Q21 40 21 46 L21.5 58 Q21.5 60 22 58 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        @break
@endswitch
