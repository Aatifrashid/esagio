@php
    $fill = $hasConditions && $condColour ? $condColour : ($isSelected ? '#e0e7ff' : '#f8fafc');
    $stroke = $hasConditions && $condColour ? $condColour : ($isSelected ? '#6366f1' : '#cbd5e1');
    $rootStroke = $hasConditions && $condColour ? $condColour : '#d1d5db';
    $opacity = $hasConditions && $condColour ? '0.25' : '1';
@endphp

{{-- Upper teeth: roots point UP, crown at bottom --}}
@switch($type)
    @case('central')
        {{-- Root --}}
        <path d="M16 2 Q18 0 20 2 L19 28 Q18 30 17 28 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.8"/>
        {{-- Crown --}}
        <path d="M10 28 Q10 24 14 24 L22 24 Q26 24 26 28 L27 42 Q27 50 24 54 Q20 58 18 58 Q16 58 12 54 Q9 50 9 42 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('lateral')
        <path d="M16 3 Q18 1 19 3 L18.5 26 Q17.5 28 16.5 26 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.8"/>
        <path d="M11 26 Q11 22 14 22 L22 22 Q25 22 25 26 L26 40 Q26 48 23 52 Q20 56 18 56 Q16 56 13 52 Q10 48 10 40 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('canine')
        <path d="M16 1 Q18 0 20 1 L19 30 Q18 33 17 30 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.8"/>
        <path d="M11 30 Q11 26 14 26 L22 26 Q25 26 25 30 L25 42 Q25 50 22 55 Q20 60 18 60 Q16 60 14 55 Q11 50 11 42 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('premolar1')
        <path d="M14 2 Q15 0 16 2 L15.5 24 Q15 26 14.5 24 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M20 2 Q21 0 22 2 L21.5 24 Q21 26 20.5 24 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M9 24 Q9 20 13 20 L23 20 Q27 20 27 24 L27 38 Q27 46 24 50 Q21 54 18 54 Q15 54 12 50 Q9 46 9 38 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('premolar2')
        <path d="M17 2 Q18 0 19 2 L18.5 22 Q18 24 17.5 22 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M9 22 Q9 18 13 18 L23 18 Q27 18 27 22 L27 38 Q27 46 24 50 Q21 54 18 54 Q15 54 12 50 Q9 46 9 38 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('molar1')
        <path d="M12 2 Q13 0 14 2 L14 20 Q13.5 22 13 20 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M18 3 Q19 1 20 3 L19.5 18 Q19 20 18.5 18 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M23 2 Q24 0 25 2 L24.5 20 Q24 22 23.5 20 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M7 20 Q7 16 12 16 L24 16 Q29 16 29 20 L29 38 Q29 48 25 52 Q22 56 18 56 Q14 56 11 52 Q7 48 7 38 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('molar2')
        <path d="M13 3 Q14 1 15 3 L14.5 20 Q14 22 13.5 20 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M21 3 Q22 1 23 3 L22.5 20 Q22 22 21.5 20 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M8 20 Q8 16 12 16 L24 16 Q28 16 28 20 L28 38 Q28 48 24 52 Q21 56 18 56 Q15 56 12 52 Q8 48 8 38 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
    @case('molar3')
        <path d="M14 4 Q15 2 16 4 L15.5 20 Q15 22 14.5 20 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M20 4 Q21 2 22 4 L21.5 20 Q21 22 20.5 20 Z" fill="none" stroke="{{ $rootStroke }}" stroke-width="0.7"/>
        <path d="M9 20 Q9 16 13 16 L23 16 Q27 16 27 20 L27 36 Q27 44 24 48 Q21 52 18 52 Q15 52 12 48 Q9 44 9 36 Z" fill="{{ $fill }}" fill-opacity="{{ $opacity }}" stroke="{{ $stroke }}" stroke-width="1.2"/>
        @break
@endswitch
