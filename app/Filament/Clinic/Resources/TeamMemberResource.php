<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\TeamMemberResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeamMemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Team';

    protected static ?string $modelLabel = 'Team Member';

    protected static ?string $pluralModelLabel = 'Team';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'team';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('clinic_id', auth()->user()->clinic_id)
            ->where('role', '!=', 'super_admin');
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isClinicAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Personal Details')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->maxLength(255)
                    ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave blank to keep current password' : null),
                Forms\Components\TextInput::make('title')
                    ->label('Title (Dr, Mr, etc.)')
                    ->maxLength(20),
                Forms\Components\TextInput::make('specialisation')
                    ->maxLength(255),
            ])->columns(2),

            Forms\Components\Section::make('Role & Access')->schema([
                Forms\Components\Select::make('role')
                    ->options([
                        'clinic_owner' => 'Clinic Owner — Full access to everything',
                        'clinic_admin' => 'Admin — Manage patients, team, settings',
                        'sales_staff' => 'Sales Staff — Manage assigned leads, patients & deals',
                        'dentist' => 'Dentist — View & manage assigned patients only',
                        'viewer' => 'Viewer — Read-only access to assigned data',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Inactive users cannot log in'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'clinic_owner' => 'Owner',
                        'clinic_admin' => 'Admin',
                        'sales_staff' => 'Sales Staff',
                        'dentist' => 'Dentist',
                        'treatment_coordinator' => 'Sales Staff', // legacy
                        'viewer' => 'Viewer',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state) => match ($state) {
                        'clinic_owner' => 'danger',
                        'clinic_admin' => 'warning',
                        'sales_staff', 'treatment_coordinator' => 'success',
                        'dentist' => 'info',
                        'viewer' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Last Login')
                    ->since()
                    ->sortable()
                    ->placeholder('Never'),
                Tables\Columns\TextColumn::make('patients_count')
                    ->label('Patients')
                    ->counts('patients')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('deactivate')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => $record->is_active && $record->id !== auth()->id())
                    ->action(fn (User $record) => $record->update(['is_active' => false])),
                Tables\Actions\Action::make('activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record) => !$record->is_active)
                    ->action(fn (User $record) => $record->update(['is_active' => true])),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
