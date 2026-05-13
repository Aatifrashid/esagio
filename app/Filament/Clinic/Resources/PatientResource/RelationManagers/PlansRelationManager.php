<?php

namespace App\Filament\Clinic\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PlansRelationManager extends RelationManager
{
    protected static string $relationship = 'treatmentPlans';

    protected static ?string $title = 'Treatment Plans';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'sent' => 'Sent',
                    'accepted' => 'Accepted',
                    'declined' => 'Declined',
                    'expired' => 'Expired',
                ])
                ->default('draft'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'primary' => 'sent',
                        'success' => 'accepted',
                        'danger' => 'declined',
                        'warning' => 'expired',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('open_builder')
                    ->label('Open Builder')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record) => route('plan.builder', ['plan' => $record->id]))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
