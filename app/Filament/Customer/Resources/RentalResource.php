<?php

namespace App\Filament\Customer\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rental;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Customer\Resources\RentalResource\Pages;
use App\Filament\Customer\Resources\RentalResource\RelationManagers;
use App\Models\Item;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'rent';
    protected static ?string $navigationLabel = 'List Penyewaan';
    protected static ?string $pluralModelLabel = 'Sewa';
    protected static ?string $modelLabel = 'Sewa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('id_user')->default(Auth::id()),
                Hidden::make('status')->default('pending'),
                Section::make('Informasi Penyewa')
                    ->description('Informasi penyewa yang akan mengisi data ini secara otomatis.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Penyewa')
                                    ->default(Auth::user()->name)
                                    ->disabled(),
                                TextInput::make('phone')
                                    ->label('Nomor Telepon Penyewa')
                                    ->default(Auth::user()->phone)
                                    ->disabled(),
                                TextInput::make('address')
                                    ->label('Alamat Penyewa')
                                    ->default(Auth::user()->address)
                                    ->disabled(),
                                TextInput::make('city')
                                    ->label('Kota Penyewa')
                                    ->default(Auth::user()->city)
                                    ->disabled(),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(),
                Section::make('Tanggal Penyewaan')
                    ->description('Isi tanggal penyewaan alat yang akan disewa.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('rent_date')
                                    ->label('Tanggal Mulai Sewa')
                                    ->placeholder('Pilih tanggal mulai sewa')
                                    ->weekStartsOnMonday()
                                    ->minDate(now()->startOfDay())
                                    ->format('d-m-Y')
                                    ->default(now()->startOfDay())
                                    ->required()
                                    ->beforeOrEqual('return_date')
                                    ->live(),

                                DatePicker::make('return_date')
                                    ->label('Tanggal Selesai Sewa')
                                    ->placeholder('Pilih tanggal selesai sewa')
                                    ->weekStartsOnMonday()
                                    ->afterOrEqual('rent_date')
                                    ->format('d-m-Y')
                                    ->disabled(fn(Get $get): bool => !$get('rent_date'))
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set, ?string $old, ?string $state) {
                                            if ($state && $get('rent_date') && Carbon::parse($state)->lessThanOrEqualTo(Carbon::parse($get('rent_date')))) {
                                                dd('Damn this get triggered', $get('rent_date'), $state, Carbon::parse($state)->lessThanOrEqualTo(Carbon::parse($get('rent_date'))));
                                                $set('return_date', Carbon::parse($get('rent_date'))->addDays(1)->format('d-m-Y'));
                                            }
                                        }
                                    )
                                    ->required()
                            ])
                    ])
                    ->columns(1)
                    ->collapsible(),
                Section::make('Alat yang Disewa')
                    ->description('Isi data penyewaan alat yang akan disewa.')
                    ->schema([
                        Repeater::make('rentalDetails')
                            ->relationship('rentalDetails')
                            ->schema([
                                Select::make('id_item')
                                    ->label('Item')
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

                                TextInput::make('quantity')
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

                                TextInput::make('sub_total')
                                    ->readOnly()
                                    ->numeric()
                                    ->required(),
                            ])
                            ->addActionLabel('Tambah Alat')
                            ->columns(3),

                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('id_user', Auth::id());
            })
            ->columns([
                TextColumn::make('id_rental')
                    ->label('ID Penyewaan')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Status Penyewaan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('down_payment')
                    ->label('Uang Muka')
                    ->sortable()
                    ->searchable()
                    ->prefix('Rp. '),
                TextColumn::make('rent_date')
                    ->label('Tanggal Mulai Sewa')
                    ->sortable()
                    ->searchable()
                    ->date(),
                TextColumn::make('return_date')
                    ->label('Tanggal Selesai Sewa')
                    ->sortable()
                    ->searchable()
                    ->date(),
                TextColumn::make('late_date')
                    ->label('Tanggal Terlambat')
                    ->sortable()
                    ->searchable()
                    ->date(),
                TextColumn::make('total_fees')
                    ->label('Total Biaya')
                    ->sortable()
                    ->searchable()
                    ->prefix('Rp. '),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->sortable()
                    ->searchable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->sortable()
                    ->searchable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rented' => 'Disewa',
                        'rejected' => 'Ditolak',
                        'returned' => 'Dikembalikan',
                        'late' => 'Terlambat',
                    ])
                    ->placeholder('Semua Status'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Detail Penyewaan')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus Penyewaan')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->successNotificationTitle('Penyewaan berhasil dihapus'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit Penyewaan')
                        ->icon('heroicon-o-pencil-square')
                        ->requiresConfirmation()
                        ->successNotificationTitle('Penyewaan berhasil diperbarui'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak Ada Penyewaan')
            ->emptyStateDescription('Anda belum melakukan penyewaan apapun.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Sewa Alat')
                    ->icon('heroicon-o-briefcase')
                    ->color('primary'),
            ]);
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
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }
}
