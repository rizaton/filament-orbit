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

    protected static ?int $navigationSort = 2;

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
                    ->numeric()
                    ->readOnly(true),
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rental.name')
                    ->label('Nama Penyewa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_id')
                    ->label('ID Barang')
                    ->searchable()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama Alat')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_returned')
                    ->label('Sudah Dikembalikan'),
                Tables\Columns\TextColumn::make('sub_total')
                    ->searchable()
                    ->label('Sub Total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_returned')
                    ->label('Status Pengembalian')
                    ->options([
                        true => 'Sudah Dikembalikan',
                        false => 'Belum Dikembalikan',
                    ]),
                Tables\Filters\Filter::make('quantity')
                    ->form([
                        Forms\Components\TextInput::make('quantity_less_than')
                            ->label('Jumlah Kurang dari')
                            ->placeholder('contoh: 5')
                            ->numeric()
                            ->integer()
                            ->minValue(0),

                        Forms\Components\TextInput::make('quantity_more_than')
                            ->label('Jumlah Lebih dari')
                            ->placeholder('contoh: 2')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['quantity_less_than'],
                                fn(Builder $query, $quantity) => $query->where('quantity', '<', $quantity),
                            )
                            ->when(
                                $data['quantity_more_than'],
                                fn(Builder $query, $quantity) => $query->where('quantity', '>', $quantity),
                            );
                    }),
                Tables\Filters\Filter::make('rental_id')
                    ->form([
                        Forms\Components\TextInput::make('rental_id')
                            ->label('ID Penyewaan')
                            ->numeric()
                            ->placeholder('Masukkan ID penyewaan untuk filter')
                            ->required(false)
                            ->maxLength(10),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['rental_id'],
                            fn(Builder $query, $id) => $query->where('rental_id', '=', $id),
                        );
                    }),
                Tables\Filters\Filter::make('item_by_category')
                    ->form([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->options(\App\Models\Category::pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('item_id', null)),

                        Forms\Components\Select::make('item_id')
                            ->label('Alat')
                            ->options(function (callable $get) {
                                $categoryId = $get('category_id');
                                if (!$categoryId) {
                                    return [];
                                }

                                return \App\Models\Item::where('category_id', $categoryId)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->disabled(fn(callable $get) => !$get('category_id')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['item_id'],
                                fn(Builder $query, $itemId) => $query->where('item_id', $itemId),
                            );
                    }),
                Tables\Filters\Filter::make('sub_total')
                    ->form([
                        Forms\Components\TextInput::make('sub_total_less_than')
                            ->label('Sub total Kurang dari')
                            ->placeholder('contoh: 100000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),

                        Forms\Components\TextInput::make('sub_total_more_than')
                            ->label('Sub total Lebih dari')
                            ->placeholder('contoh: 5000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['sub_total_less_than'],
                                fn(Builder $query, $sub_total) => $query->where('sub_total', '<', $sub_total),
                            )
                            ->when(
                                $data['sub_total_more_than'],
                                fn(Builder $query, $sub_total) => $query->where('sub_total', '>', $sub_total),
                            );
                    }),

            ])
            ->filtersFormColumns(4)
            ->filtersFormSchema(fn(array $filters): array => [
                Forms\Components\Grid::make()->columns(4)
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                $filters['rental_id'],
                                $filters['is_returned'],
                            ])->columnSpan(1),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                $filters['item_by_category'],
                                $filters['quantity'],
                                $filters['sub_total'],
                            ])->columnSpan(3)
                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Ubah Detail Sewa'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus Detail Sewa'),
                ]),
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
