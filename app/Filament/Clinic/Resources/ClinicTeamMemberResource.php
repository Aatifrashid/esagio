<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ClinicTeamMemberResource\Pages;
use App\Models\ClinicTeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClinicTeamMemberResource extends Resource
{
    protected static ?string $model = ClinicTeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Clinic Setup';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Team Member Details')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title (Dr, Mr, etc.)')
                    ->maxLength(20),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('role')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('specialisation')
                    ->maxLength(255),
                Forms\Components\TextInput::make('years_experience')
                    ->label('Years Experience')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('qualifications')
                    ->maxLength(255),
                Forms\Components\TextInput::make('languages_spoken')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\Textarea::make('bio')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('photo_path')
                    ->label('Photo')
                    ->image()
                    ->directory('team')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(fn ($state, $record) => trim($record->title.' '.$record->first_name.' '.$record->last_name))
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialisation')
                    ->limit(40),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
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
            'index' => Pages\ListClinicTeamMembers::route('/'),
            'create' => Pages\CreateClinicTeamMember::route('/create'),
            'edit' => Pages\EditClinicTeamMember::route('/{record}/edit'),
        ];
    }
}
