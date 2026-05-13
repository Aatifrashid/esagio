<?php

namespace App\Filament\Super\Widgets;

use App\Models\Clinic;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentClinicsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recently Registered Clinics';

    public function table(Table $table): Table
    {
        return $table
            ->query(Clinic::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\BadgeColumn::make('plan_tier')
                    ->colors([
                        'secondary' => 'free',
                        'primary' => 'starter',
                        'success' => 'professional',
                        'warning' => 'agency',
                    ]),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Joined'),
            ]);
    }
}
