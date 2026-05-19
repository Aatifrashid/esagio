<?php

namespace App\Filament\Clinic\Pages;

use Filament\Pages\Page;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Calendar';

    protected static ?string $title = 'Calendar';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.clinic.pages.calendar';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
