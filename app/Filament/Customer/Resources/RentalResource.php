<?php

namespace App\Filament\Customer\Resources;

use App\Models\Item;
use Filament\Tables;
use App\Models\Rental;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Customer\Resources\RentalResource\Pages;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'rent';
    protected static ?string $navigationLabel = 'Sewa Alat';
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
                                            if (
                                                $state && $get('rent_date') && Carbon::parse($state)
                                                ->lessThanOrEqualTo(Carbon::parse($get('rent_date')))
                                            ) {
                                                $set('return_date', Carbon::parse($get('rent_date')));
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
                            ->label('')
                            ->relationship('rentalDetails')
                            ->schema([
                                Select::make('id_item')
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
                                TextInput::make('quantity')
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
                                TextInput::make('sub_total')
                                    ->label('Sub Total')
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
                Section::make('Syarat dan Ketentuan Sewa Alat')
                    ->description('Syarat dan ketentuan sewa alat.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Checkbox::make('terms')
                                    ->label('Setuju dengan syarat dan ketentuan sewa alat')
                                    ->hintAction(
                                        Action::make('show-terms')
                                            ->hiddenLabel()
                                            ->icon('heroicon-o-information-circle')
                                            ->iconSize(IconSize::Large)
                                            ->tooltip('Lihat syarat dan ketentuan')
                                            ->modalHeading('')
                                            ->modalContent(new HtmlString(view('filament.custom.terms')->render()))
                                            ->modalCancelActionLabel(__('Kembali'))
                                            ->modalSubmitAction(false)
                                    )
                                    ->accepted()
                                    ->required(),
                                Checkbox::make('conduct')
                                    ->label('Setuju dengan tata tertib sewa alat')
                                    ->hintAction(
                                        Action::make('show-conduct')
                                            ->hiddenLabel()
                                            ->icon('heroicon-o-information-circle')
                                            ->iconSize(IconSize::Large)
                                            ->tooltip('Lihat tata tertib')
                                            ->modalHeading('')
                                            ->modalContent(new HtmlString(view('filament.custom.rules')->render()))
                                            ->modalCancelActionLabel(__('Kembali'))
                                            ->modalSubmitAction(false)
                                    )
                                    ->accepted()
                                    ->required(),
                            ]),
                    ])
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
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'rented' => 'Disewa',
                            'rejected' => 'Ditolak',
                            'returning' => 'Pengembalian',
                            'returned' => 'Dikembalikan',
                            'late' => 'Terlambat',
                            default => ucfirst($state),
                        };
                    })
                    ->color(fn($state) => match ($state) {
                        'pending' => Color::Orange,
                        'approved' => Color::Green,
                        'returned' => Color::Stone,
                        'rented' => Color::Blue,
                        'late', 'rejected' => Color::Red,
                        default => Color::Orange,
                    })
                    ->badge(),
                TextColumn::make('down_payment')
                    ->label('Uang Muka')
                    ->sortable()
                    ->searchable()
                    ->money('IDR', true),
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
                    ->money('IDR', true),
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
                        'returning' => 'Pengembalian',
                        'returned' => 'Dikembalikan',
                        'late' => 'Terlambat',
                    ])
                    ->placeholder('Semua Status'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make('Lihat Detail')
                        ->label('Lihat Data Sewa')
                        ->icon('heroicon-o-eye')
                        ->modalHeading(fn($record) => 'ID Peminjaman #' . $record->id_rental . ' - ' . $record->user->name)
                        ->modalWidth('2xl')
                        ->modalContent(fn($record) => view('filament.custom.rental-customer', [
                            'rental' => $record,
                            'details' => \App\Models\RentalDetail::where('id_rental', $record->id_rental)
                                ->with(['item'])
                                ->get()
                        ]))
                        ->form([]),
                    Tables\Actions\EditAction::make()
                        ->visible(fn($record) => $record->status === 'pending')
                        ->label('Ubah tanggal Sewa')
                        ->icon('heroicon-o-pencil')
                        ->requiresConfirmation()
                        ->successNotificationTitle('Tanggal sewa berhasil diperbarui'),
                    Tables\Actions\Action::make('return_request')
                        ->visible(fn($record) => $record->status === 'rented')
                        ->label('Ajukan Pengembalian')
                        ->icon('heroicon-o-arrow-right')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['status' => 'returning']);
                            return Pages\ListRentals::route('/');
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn($record) => !in_array($record->status, ['rented', 'late']))
                        ->label(fn($record) =>  in_array($record->status, ['pending', 'approved']) ? 'Batalkan Sewa' : 'Hapus Data Sewa')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->successNotificationTitle('Penyewaan berhasil dihapus'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak Ada Penyewaan')
            ->emptyStateDescription('Anda belum melakukan penyewaan alat apapun.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Sewa Alat')
                    ->icon('heroicon-o-briefcase')
                    ->color('primary'),
            ]);
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
