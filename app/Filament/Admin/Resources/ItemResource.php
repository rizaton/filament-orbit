<?php

namespace App\Filament\Admin\Resources;

use App\Models\Item;
use App\Models\Category;
use App\Filament\Admin\Exports\ItemExporter;
use App\Filament\Admin\Resources\ItemResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Inventori';
    protected static ?string $slug = 'inventory/items';
    protected static ?string $navigationLabel = 'Alat';
    protected static ?string $pluralModelLabel = 'List Alat-alat';
    protected static ?string $modelLabel = 'Alat';
    protected static ?string $breadcrumb = 'Alat';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Alat')
                    ->live(onBlur: true)
                    ->unique(Item::class, 'name', ignoreRecord: true)
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Alat')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Item::class, 'slug', ignoreRecord: true)
                    ->label('Slug Alat')
                    ->dehydrateStateUsing(fn(string $state): string => md5($state)),
                Forms\Components\TextInput::make('stock')
                    ->label('Stok')
                    ->required()
                    ->integer()
                    ->maxLength(4)
                    ->numeric(),
                Forms\Components\Select::make('id_category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->live(onBlur: true)
                            ->unique(Category::class, 'name', ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->label('Category Slug'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Category Color')
                            ->default('#000000')
                            ->required(),
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_available')
                    ->label('Tersedia')
                    ->required(),
                Forms\Components\TextInput::make('rent_price')
                    ->label('Harga Sewa')
                    ->maxLength(15)
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar')
                    ->image(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(ItemExporter::class)
                    ->label('Ekspor Alat')
                    ->fileName('items_export_' . now()->format('Y_m_d_H_i_s'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Alat')
                    ->searchable()
                    ->description(fn(Item $record): string => Str::limit($record->description ?? 'Tidak ada deskripsi', 20))
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_available')
                    ->label('Tersedia'),
                Tables\Columns\TextColumn::make('rent_price')
                    ->label('Harga Sewa')
                    ->money('IDR')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
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
                Tables\Filters\Filter::make('stock')
                    ->form([
                        Forms\Components\TextInput::make('stock_less_than')
                            ->label('Stok Kurang dari')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                        Forms\Components\TextInput::make('stock_more_than')
                            ->label('Stok Lebih dari')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['stock_less_than'],
                                fn(Builder $query, $stock) => $query->where('stock', '<', $stock),
                            )
                            ->when(
                                $data['stock_more_than'],
                                fn(Builder $query, $stock) => $query->where('stock', '>', $stock),
                            );
                    }),
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
            ->filtersFormColumns(3)
            ->filtersFormSchema(fn(array $filters): array => [
                Forms\Components\Grid::make()->columns(3)
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                $filters['is_available'],
                                $filters['category'],
                            ])->columnSpan(1),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                $filters['stock'],
                                $filters['rent_price'],
                            ])->columnSpan(2)
                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat')
                        ->modalHeading(fn($record) => "Detail Alat: {$record->name}")
                        ->modalContent(fn($record) => view('filament.custom.item-details', [
                            'record' => $record,
                        ]))
                        ->form([]),
                    Tables\Actions\EditAction::make()
                        ->label('Ubah'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak ada alat yang ditemukan')
            ->emptyStateDescription('Silahkan buat alat baru untuk memulai.')
            ->emptyStateIcon('heroicon-o-cube')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
