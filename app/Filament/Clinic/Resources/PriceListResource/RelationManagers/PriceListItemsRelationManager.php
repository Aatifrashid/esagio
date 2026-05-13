<?php

namespace App\Filament\Clinic\Resources\PriceListResource\RelationManagers;

use App\Models\TreatmentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PriceListItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('treatment_template_id')
                ->label('Treatment')
                ->options(TreatmentTemplate::pluck('name', 'id'))
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('variant_name')
                ->label('Variant')
                ->maxLength(255),
            Forms\Components\TextInput::make('unit_price')
                ->numeric()
                ->prefix('£')
                ->required(),
            Forms\Components\TextInput::make('unit_label')
                ->label('Unit')
                ->default('per tooth')
                ->maxLength(100),
            Forms\Components\Textarea::make('notes')
                ->rows(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('variant_name')
            ->columns([
                Tables\Columns\TextColumn::make('treatmentTemplate.name')
                    ->label('Treatment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('variant_name')
                    ->label('Variant'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('GBP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_label')
                    ->label('Unit'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
