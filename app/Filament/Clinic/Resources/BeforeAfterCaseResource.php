<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\BeforeAfterCaseResource\Pages;
use App\Models\BeforeAfterCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BeforeAfterCaseResource extends Resource
{
    protected static ?string $model = BeforeAfterCase::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Clinic Setup';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Case Details')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('patient_age_range')
                    ->label('Patient Age Range')
                    ->maxLength(50),
                Forms\Components\Select::make('patient_gender')
                    ->label('Patient Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->nullable(),
                Forms\Components\TextInput::make('treatment_duration_days')
                    ->label('Treatment Duration (days)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('treatment_value')
                    ->label('Treatment Value')
                    ->numeric()
                    ->prefix('£')
                    ->nullable(),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured'),
                Forms\Components\Toggle::make('consent_obtained')
                    ->label('Patient Consent Obtained')
                    ->required(),
            ])->columns(2),

            Forms\Components\Section::make('Images')->schema([
                Forms\Components\FileUpload::make('before_image_path')
                    ->label('Before Image')
                    ->image()
                    ->directory('before-after'),
                Forms\Components\FileUpload::make('after_image_path')
                    ->label('After Image')
                    ->image()
                    ->directory('before-after'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('treatment_value')
                    ->label('Value')
                    ->money('GBP')
                    ->sortable(),
                Tables\Columns\IconColumn::make('consent_obtained')
                    ->label('Consent')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
                Tables\Filters\TernaryFilter::make('consent_obtained')->label('Consent Obtained'),
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
            'index' => Pages\ListBeforeAfterCases::route('/'),
            'create' => Pages\CreateBeforeAfterCase::route('/create'),
            'edit' => Pages\EditBeforeAfterCase::route('/{record}/edit'),
        ];
    }
}
