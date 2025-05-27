<?php

namespace App\Filament\Resources;

use App\Models\RentalDetail;
use App\Filament\Resources\RentalDetailResource\Pages;
use App\Filament\Resources\RentalDetailResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentalDetailResource extends Resource
{
    protected static ?string $model = RentalDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Penyewaan';
    protected static ?string $slug = 'rent/rental-details';

    protected static ?string $navigationLabel = 'List Detail Sewa';
    protected static ?string $pluralModelLabel = 'List Detail Sewa';
    protected static ?string $modelLabel = 'Detail Sewa';
    protected static ?string $breadcrumb = 'Detail Sewa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rental_id')
                    ->relationship('rental', 'name')
                    ->required(),
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_returned')
                    ->required(),
                Forms\Components\TextInput::make('sub_total')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rental_id')
                    ->label('ID Sewa')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rental.name')
                    ->label('Nama Penyewa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_id')
                    ->label('ID Barang')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama Alat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_returned')
                    ->label('Sudah Dikembalikan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sub_total')
                    ->label('Sub Total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada detail sewa yang ditemukan')
            ->emptyStateDescription('Saat ini tidak ada detail sewa yang tersedia.')
            ->emptyStateIcon('heroicon-o-document-text');
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
            'index' => Pages\ListRentalDetails::route('/'),
            'create' => Pages\CreateRentalDetail::route('/create'),
            'edit' => Pages\EditRentalDetail::route('/{record}/edit'),
        ];
    }
}
