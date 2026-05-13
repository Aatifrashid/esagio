<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\PriceListResource\Pages;
use App\Filament\Clinic\Resources\PriceListResource\RelationManagers;
use App\Models\PriceList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PriceListResource extends Resource
{
    protected static ?string $model = PriceList::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-pound';

    protected static ?string $navigationGroup = 'Clinic Setup';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Price List Details')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('currency')
                    ->options([
                        'GBP' => 'GBP - Pound Sterling',
                        'EUR' => 'EUR - Euro',
                        'USD' => 'USD - US Dollar',
                        'TRY' => 'TRY - Turkish Lira',
                        'AED' => 'AED - UAE Dirham',
                    ])
                    ->required()
                    ->default('GBP'),
                Forms\Components\Toggle::make('is_default')
                    ->label('Default Price List'),
                Forms\Components\DatePicker::make('valid_from')
                    ->label('Valid From'),
                Forms\Components\DatePicker::make('valid_until')
                    ->label('Valid Until'),
                Forms\Components\Textarea::make('notes')
                    ->rows(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
                Tables\Columns\TextColumn::make('valid_from')
                    ->label('From')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Until')
                    ->date('d M Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PriceListItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriceLists::route('/'),
            'create' => Pages\CreatePriceList::route('/create'),
            'edit' => Pages\EditPriceList::route('/{record}/edit'),
        ];
    }
}
