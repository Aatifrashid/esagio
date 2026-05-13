<?php

namespace App\Filament\Super\Resources;

use App\Filament\Super\Resources\SystemTemplateResource\Pages;
use App\Models\TreatmentCategory;
use App\Models\TreatmentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SystemTemplateResource extends Resource
{
    protected static ?string $model = TreatmentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'System Templates';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('clinic_id');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template Details')->schema([
                Forms\Components\TextInput::make('code')->required()->maxLength(50),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(TreatmentCategory::whereNull('clinic_id')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\Toggle::make('requires_imaging')->default(false),
                Forms\Components\TextInput::make('standard_duration_days')->numeric()->nullable(),
                Forms\Components\Textarea::make('description_short')->rows(2)->columnSpanFull(),
                Forms\Components\Textarea::make('description_long')->rows(4)->columnSpanFull(),
                Forms\Components\Textarea::make('recovery_info')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('guarantee_text')->rows(2)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('usage_count')->label('Uses')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemTemplates::route('/'),
            'create' => Pages\CreateSystemTemplate::route('/create'),
            'edit' => Pages\EditSystemTemplate::route('/{record}/edit'),
        ];
    }
}
