<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\WebhookEndpointResource\Pages;
use App\Models\WebhookEndpoint;
use App\Services\Integrations\WebhookDispatcher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebhookEndpointResource extends Resource
{
    protected static ?string $model = WebhookEndpoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Integrations';

    protected static ?string $navigationLabel = 'Webhooks';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('url')
                ->label('Endpoint URL')
                ->url()
                ->required()
                ->maxLength(500),
            Forms\Components\TextInput::make('secret')
                ->label('Signing secret')
                ->default(fn () => bin2hex(random_bytes(32)))
                ->required()
                ->maxLength(64),
            Forms\Components\CheckboxList::make('events')
                ->options(array_combine(WebhookDispatcher::EVENTS, WebhookDispatcher::EVENTS))
                ->required()
                ->columns(2),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('url')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('events')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('failure_count')->sortable(),
                Tables\Columns\TextColumn::make('last_triggered_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhookEndpoints::route('/'),
            'create' => Pages\CreateWebhookEndpoint::route('/create'),
            'edit' => Pages\EditWebhookEndpoint::route('/{record}/edit'),
        ];
    }
}
