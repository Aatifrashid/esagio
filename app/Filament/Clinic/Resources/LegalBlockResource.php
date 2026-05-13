<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\LegalBlockResource\Pages;
use App\Models\LegalBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LegalBlockResource extends Resource
{
    protected static ?string $model = LegalBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationGroup = 'Clinic Setup';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Legal Block')->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('language')
                    ->options([
                        'en' => 'English',
                        'de' => 'German',
                        'fr' => 'French',
                        'es' => 'Spanish',
                        'ar' => 'Arabic',
                        'tr' => 'Turkish',
                        'ru' => 'Russian',
                    ])
                    ->default('en')
                    ->required(),
                Forms\Components\TextInput::make('jurisdiction')
                    ->maxLength(100),
                Forms\Components\Toggle::make('is_default')
                    ->label('Default Block'),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('language')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'de' => 'German',
                        'fr' => 'French',
                        'es' => 'Spanish',
                        'ar' => 'Arabic',
                        'tr' => 'Turkish',
                        'ru' => 'Russian',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLegalBlocks::route('/'),
            'create' => Pages\CreateLegalBlock::route('/create'),
            'edit' => Pages\EditLegalBlock::route('/{record}/edit'),
        ];
    }
}
