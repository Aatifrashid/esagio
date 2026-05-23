<?php

namespace App\Filament\Clinic\Resources\PriceListResource\RelationManagers;

use App\Models\TreatmentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                ->options(fn () => TreatmentTemplate::query()
                    ->orderBy('code')
                    ->get()
                    ->mapWithKeys(fn ($t) => [$t->id => "{$t->code} — {$t->name}"]))
                ->searchable()
                ->preload()
                ->nullable()
                ->live()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    if ($state && empty($get('variant_name'))) {
                        $template = TreatmentTemplate::find($state);
                        if ($template) {
                            $set('variant_name', $template->name);
                        }
                    }
                })
                ->helperText('Optional — link to a treatment template for reporting.')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('variant_name')
                ->label('Item Name')
                ->helperText('The name shown on quotes and invoices.')
                ->maxLength(255)
                ->required(),
            Forms\Components\TextInput::make('unit_price')
                ->numeric()
                ->prefix('£')
                ->required(),
            Forms\Components\Select::make('unit_label')
                ->label('Priced Per')
                ->options([
                    'per tooth' => 'Per Tooth',
                    'per jaw' => 'Per Jaw',
                    'per arch' => 'Per Arch',
                    'per implant' => 'Per Implant',
                    'per session' => 'Per Session',
                    'per procedure' => 'Per Procedure',
                    'fixed' => 'Fixed Price',
                ])
                ->default('per tooth')
                ->required(),
            Forms\Components\Textarea::make('notes')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('variant_name')
            ->columns([
                Tables\Columns\TextColumn::make('variant_name')
                    ->label('Item')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->treatmentTemplate?->code),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('GBP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_label')
                    ->label('Priced Per')
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('variant_name')
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
