<?php

namespace App\Filament\Customer\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Customer\Resources\ItemResource\Pages;
use App\Filament\Customer\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $slug = 'items';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Alat-Alat';
    protected static ?string $modelLabel = 'Alat';
    protected static ?string $breadcrumb = 'Alat';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar Alat')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Alat')
                    ->searchable()
                    ->description(fn(Item $record): string => Str::limit($record->description ?? 'Tidak ada deskripsi', 20))
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Jumlah Alat Tersedia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_price')
                    ->label('Harga Sewa')
                    ->prefix('Rp. ')
                    ->money('IDR')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Ketersediaan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
            ])
            ->extremePaginationLinks()
            ->filters([
                Tables\Filters\SelectFilter::make('is_available')
                    ->options([
                        '1' => 'Tersedia',
                        '0' => 'Tidak Tersedia',
                    ])
                    ->label('Ketersediaan'),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\Filter::make('rent_price')
                    ->form([
                        Forms\Components\TextInput::make('rent_price_less_than')
                            ->label('Harga sewa Kurang dari')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                        Forms\Components\TextInput::make('rent_price_more_than')
                            ->label('Harga sewa Lebih dari')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['rent_price_less_than'],
                                fn(Builder $query, $rent_price) => $query->where('rent_price', '<', $rent_price),
                            )
                            ->when(
                                $data['rent_price_more_than'],
                                fn(Builder $query, $rent_price) => $query->where('rent_price', '>', $rent_price),
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->filtersFormSchema(fn(array $filters): array => [
                Forms\Components\Grid::make()->columns(2)
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                $filters['is_available'],
                                $filters['category'],
                            ])->columnSpan(1),
                        Forms\Components\Grid::make(1)
                            ->schema([
                                $filters['rent_price'],
                            ])->columnSpan(1)
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading(fn($record) => "Detail Alat: {$record->name}")
                    ->modalContent(fn($record) => view('filament.custom.item-customer', [
                        'record' => $record,
                    ]))
                    ->form([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageItems::route('/'),
        ];
    }
}
