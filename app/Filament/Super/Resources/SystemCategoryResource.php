<?php

namespace App\Filament\Super\Resources;

use App\Filament\Super\Resources\SystemCategoryResource\Pages;
use App\Models\TreatmentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SystemCategoryResource extends Resource
{
    protected static ?string $model = TreatmentCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'System Categories';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('clinic_id');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Category Details')->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(100)->unique(ignoreRecord: true),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(TreatmentCategory::whereNull('clinic_id')->whereNull('parent_id')->pluck('name', 'id'))
                    ->nullable(),
                Forms\Components\Textarea::make('description')->rows(2),
                Forms\Components\TextInput::make('icon')->maxLength(100),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\TextColumn::make('parent.name')->label('Parent'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemCategories::route('/'),
            'create' => Pages\CreateSystemCategory::route('/create'),
            'edit' => Pages\EditSystemCategory::route('/{record}/edit'),
        ];
    }
}
