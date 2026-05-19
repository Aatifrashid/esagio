<x-filament-panels::page>
    <style>
        /* Strip Filament page padding so calendar fills the screen */
        .fi-page-content-ctn { max-width: 100% !important; }
        .fi-body-content { padding: 0 !important; }
        .fi-page > .fi-page-content-ctn > div { gap: 0 !important; }
        header.fi-header { display: none !important; }
    </style>
    <div class="-mx-6 -mt-6 -mb-6">
        @livewire('crm.calendar-view')
    </div>
</x-filament-panels::page>
