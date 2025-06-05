<?php

namespace App\Filament\Resources;

use App\Models\Category;
use App\Filament\Exports\CategoryExporter;
use App\Filament\Resources\CategoryResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Inventori';
    protected static ?string $slug = 'inventory/categories';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'List Kategori';
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $breadcrumb = 'Kategori';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->required()
                    ->maxLength(255)
                    ->unique(Category::class, 'slug', ignoreRecord: true)
                    ->label('Slug Kategori'),
                Forms\Components\ColorPicker::make('color')
                    ->label('Warna Kategori')
                    ->default('#000000')
                    ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(CategoryExporter::class)
                    ->label('Ekspor Kategori')
                    ->fileName('category_export_' . now()->format('Y_m_d_H_i_s'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Alat')
                    ->counts('items')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna Kategori')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                Tables\Filters\Filter::make('items_count')
                    ->form([
                        Forms\Components\TextInput::make('items_count_less_than')
                            ->label('Kategori Item Kurang dari')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                        Forms\Components\TextInput::make('items_count_more_than')
                            ->label('Kategori Item Lebih dari')
                            ->numeric()
                            ->integer()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['items_count_less_than'],
                                fn(Builder $query, $items_count) => $query->where('items_count', '<', $items_count),
                            )
                            ->when(
                                $data['items_count_more_than'],
                                fn(Builder $query, $items_count) => $query->where('items_count', '>', $items_count),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make('View Category')
                        ->label('Lihat')
                        ->icon('heroicon-o-eye')
                        ->modalHeading(fn($record) => $record->name)
                        ->modalWidth('2xl')
                        ->modalContent(fn($record) => view('filament.custom.category-items', [
                            'name' => $record->name,
                            'slug' => $record->slug,
                            'color' => $record->color,
                            'items' => \App\Models\Item::where('category_id', $record->id_category)->get(),
                        ]))
                        ->form([]),
                    Tables\Actions\EditAction::make()
                        ->label('Ubah'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Kategori')
                        ->modalDescription('Apakah Anda yakin ingin menghapus kategori ini? Semua alat yang terkait dengan kategori ini akan kehilangan kategorinya.'),
                ])->label('Aksi')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada kategori yang ditemukan')
            ->emptyStateDescription('Silahkan buat kategori baru untuk mengelompokkan alat-alat yang ada di sistem.')
            ->emptyStateIcon('heroicon-o-rectangle-stack')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                    ->label('Buat Kategori Baru')
                    ->icon('heroicon-m-plus')
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
