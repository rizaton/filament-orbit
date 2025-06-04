<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Message;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Exports\MessageExporter;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\MessageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MessageResource\RelationManagers;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Pesan';
    protected static ?string $pluralModelLabel = 'List Pesan';
    protected static ?string $modelLabel = 'Pesan';
    protected static ?string $breadcrumb = 'Pesan';

    public static function form(Form $form): Form
    {
        return $form
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(MessageExporter::class)
            ])
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(MessageExporter::class)
                    ->label('Ekspor Pesan')
                    ->fileName('messages_export_' . now()->format('Y_m_d_H_i_s'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Tanggal pesan dibuat dari'),
                        Forms\Components\DatePicker::make('created_until')->label('Tanggal pesan sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading(fn($record) => "Pesan dari: {$record->name}")
                    ->modalContent(fn($record) => view('filament.custom.message-details', [
                        'record' => $record,
                    ]))->form([]),
            ])
            ->emptyStateHeading('Tidak ada pesan yang ditemukan')
            ->emptyStateDescription('Saat ini tidak ada pesan yang tersedia.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
        ];
    }
}
