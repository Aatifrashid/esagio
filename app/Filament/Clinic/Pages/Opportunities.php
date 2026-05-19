<?php

namespace App\Filament\Clinic\Pages;

use Filament\Pages\Page;

class Opportunities extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Opportunities';

    protected static ?string $title = 'Opportunities';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.clinic.pages.opportunities';

    protected static ?string $slug = 'opportunities';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
