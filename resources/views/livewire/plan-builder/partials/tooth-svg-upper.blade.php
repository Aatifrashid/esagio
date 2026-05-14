@php
    $overlayColour = ($hasConditions && $condColour) ? $condColour : null;
    $selRing = $isSelected ? '#6366f1' : null;
@endphp

<image href="{{ asset('svg/teeth/upper-' . $type . '.svg') }}" x="0" y="0" width="36" height="70"/>

@if($overlayColour)
    <rect x="4" y="0" width="28" height="70" rx="4" fill="{{ $overlayColour }}" opacity="0.35"/>
@endif

@if($selRing)
    <rect x="3" y="-1" width="30" height="72" rx="5" fill="none" stroke="{{ $selRing }}" stroke-width="1.5" stroke-dasharray="3 2" opacity="0.6"/>
@endif
