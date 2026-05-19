<?php

namespace App\Filament\Clinic\Resources\PatientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ConversationsRelationManager extends RelationManager
{
    protected static string $relationship = 'conversations';

    protected static ?string $title = 'Conversations';

    protected static ?string $icon = 'heroicon-o-chat-bubble-left-right';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('channel')
                    ->badge()
                    ->colors([
                        'success' => 'whatsapp',
                        'info' => 'email',
                        'warning' => 'sms',
                    ]),
                Tables\Columns\TextColumn::make('channel_identifier')
                    ->label('Contact'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'open',
                        'gray' => 'closed',
                        'warning' => 'archived',
                    ]),
                Tables\Columns\TextColumn::make('messages_count')
                    ->label('Messages')
                    ->counts('messages'),
                Tables\Columns\TextColumn::make('last_message_at')
                    ->label('Last Message')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('last_message_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('open_chat')
                    ->label('Open Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn ($record) => Inbox::getUrl() . '?conversation=' . $record->id)
                    ->openUrlInNewTab(),
            ]);
    }
}
