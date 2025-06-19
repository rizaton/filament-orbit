<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Item;

use App\Models\Rental;
use App\Models\RentalDetail;
use App\Filament\Admin\Exports\RentalDetailExporter;
use App\Filament\Admin\Resources\RentalDetailResource\Pages;

use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;

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
                Forms\Components\Select::make('id_rental')
                    ->label('ID Sewa')
                    ->relationship('rental', 'id_rental')
                    ->placeholder('Pilih Sewa')
                    ->options(
                        Rental::with('user')->get()->mapWithKeys(function ($rental) {
                            return [
                                $rental->id_rental => 'RentalID# ' . $rental->id_rental . ' - ' . $rental->user->name,
                            ];
                        })
                    )
                    ->searchable()
                    ->required()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('id_item')
                    ->label('Pilih Nama alat')
                    ->options(Item::query()
                        ->where('stock', '>', 0)
                        ->where('is_available', true)
                        ->orderBy('name')
                        ->pluck('name', 'id_item'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if ($state) {
                            $item = Item::find($state);
                            if ($item) {
                                $set('sub_total', $item->rent_price);
                                $set('quantity', 1);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Alat')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if ($state && $get('id_item')) {
                            $item = Item::find($get('id_item'));
                            if ($item) {
                                $set('sub_total', $item->rent_price * (int)$state);
                            }
                        }
                    })->maxValue(fn(Get $get): int => Item::find($get('id_item'))->stock ?? 0)
                    ->live(),
                Forms\Components\TextInput::make('sub_total')
                    ->label('Sub Total')
                    ->readOnly()
                    ->numeric()
                    ->required(),
                Forms\Components\Toggle::make('is_returned')
                    ->label('Sudah Dikembalikan')
                    ->helperText('Tandai jika alat sudah dikembalikan oleh penyewa')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(RentalDetailExporter::class)
                    ->label('Ekspor Detail Sewa')
                    ->fileName('rental_details_export_' . now()->format('Y_m_d_H_i_s'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id_rental')
                    ->label('ID Sewa')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id_item')
                    ->label('ID Barang')
                    ->searchable()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rental.user.name')
                    ->label('Nama Penyewa')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\Filter::make('id_rental')
                    ->form([
                        Forms\Components\TextInput::make('id_rental')
                            ->label('ID Penyewaan')
                            ->numeric()
                            ->placeholder('Masukkan ID penyewaan untuk filter')
                            ->required(false)
                            ->maxLength(10),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['id_rental'],
                            fn(Builder $query, $id) => $query->where('id_rental', '=', $id),
                        );
                    }),
                Tables\Filters\Filter::make('item_by_category')
                    ->form([
                        Forms\Components\Select::make('id_category')
                            ->label('Kategori')
                            ->placeholder('Pilih Kategori')
                            ->options(\App\Models\Category::pluck('name', 'id_category'))
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('item_id', null)),
                        Forms\Components\Select::make('id_item')
                            ->label('Alat')
                            ->placeholder('Pilih Alat')
                            ->options(function (callable $get) {
                                $categoryId = $get('id_category');
                                if (!$categoryId) {
                                    return [];
                                }
                                return \App\Models\Item::where('id_category', $categoryId)
                                    ->pluck('name', 'id_category');
                            })
                            ->searchable()->searchPrompt('Cari Alat')
                            ->disabled(fn(callable $get) => !$get('id_category')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['id_item'],
                                fn(Builder $query, $idItem) => $query->where('id_item', $idItem),
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
                                $filters['id_rental'],
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
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Detail Sewa')
                        ->modalHeading(fn($record) => "Detail Sewa #{$record->id_rental}")
                        ->modalContent(fn($record) => view('filament.custom.rental-detail', [
                            'record' => $record,
                        ]))
                        ->form([]),

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
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalDetails::route('/'),
            'create' => Pages\CreateRentalDetail::route('/create'),
            'edit' => Pages\EditRentalDetail::route('/{record}/edit'),
        ];
    }
}
