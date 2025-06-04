<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;

use App\Models\Rental;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\RentalResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RentalResource\RelationManagers;
use PhpParser\Node\Stmt\Label;

use App\Filament\Exports\RentalExporter;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Penyewaan';
    protected static ?string $slug = 'rent/rentals';

    protected static ?string $navigationLabel = 'List Penyewaan';
    protected static ?string $pluralModelLabel = 'List Penyewaan';
    protected static ?string $modelLabel = 'Penyewaan';
    protected static ?string $breadcrumb = 'Penyewaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('performed_by')
                    ->label('Performed By')
                    ->options(User::all()->pluck('name', 'id'))
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('User Name')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('User Email')
                            ->email()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->required(),
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'rented' => 'Rented',
                        'returned' => 'Returned',
                        'late' => 'Late',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('down_payment')
                    ->label('DP')
                    ->maxLength(15)
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('rent_date')
                    ->minDate(now())
                    ->label('Tanggal Sewa')
                    ->required(),
                Forms\Components\DatePicker::make('return_date')
                    ->label('Tanggal Kembali')
                    ->minDate(now())
                    ->required(),
                Forms\Components\DatePicker::make('late_date')
                    ->minDate(now())
                    ->label('Tanggal Keterlambatan')
                    ->helperText('Tanggal keterlambatan hanya diisi jika status penyewaan adalah "Late"')
                    ->nullable(),
                Forms\Components\TextInput::make('late_fees')
                    ->disabled()
                    ->label('Denda Terlambat')
                    ->placeholder('Denda Terlambat')
                    ->helperText('Denda akan dihitung otomatis berdasarkan tanggal keterlambatan')
                    ->maxLength(15)
                    ->numeric(),
                Forms\Components\TextInput::make('total_fees')
                    ->label('Total Pembayaran')
                    ->placeholder('Total Pembayaran')
                    ->helperText('Total pembayaran akan dihitung otomatis berdasarkan DP dan denda keterlambatan')
                    ->disabled()
                    ->maxLength(15)
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(RentalExporter::class)
                    ->label('Ekspor Data Penyewaan')
                    ->fileName('rentals_export_' . now()->format('Y_m_d_H_i_s'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Sewa')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Diproses oleh')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'returned' => 'Dikembalikan',
                        'late' => 'Terlambat',
                    ]),
                Tables\Columns\TextColumn::make('down_payment')
                    ->label('DP')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_date')
                    ->label('Tanggal sewa')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->label('Tanggal kembali')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('late_date')
                    ->label('Tanggal keterlambatan')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('late_fees')
                    ->label('Denda Terlambat')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_fees')
                    ->label('Total pembayaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'returned' => 'Dikembalikan',
                        'late' => 'Terlambat',
                    ])->label('Status'),
                Tables\Filters\SelectFilter::make('user.name')
                    ->label('Diproses oleh')
                    ->relationship('user', 'name'),
                Tables\Filters\Filter::make('rent_date')
                    ->form([
                        Forms\Components\DatePicker::make('rent_from')->label('Tanggal sewa dari'),
                        Forms\Components\DatePicker::make('rent_until')->label('Tanggal sewa sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['rent_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('rent_date', '>=', $date),
                            )
                            ->when(
                                $data['rent_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('rent_date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('return_date')
                    ->form([
                        Forms\Components\DatePicker::make('return_from')->label('Tanggal kembali dari'),
                        Forms\Components\DatePicker::make('return_until')->label('Tanggal kembali sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['return_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('return_date', '>=', $date),
                            )
                            ->when(
                                $data['return_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('return_date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('late_date')
                    ->form([
                        Forms\Components\DatePicker::make('late_from')->label('Tanggal terlambat dari'),
                        Forms\Components\DatePicker::make('late_until')->label('Tanggal terlambat sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['late_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('late_date', '>=', $date),
                            )
                            ->when(
                                $data['late_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('late_date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('down_payment')
                    ->form([
                        Forms\Components\TextInput::make('down_payment_less_than')
                            ->label('Down Payment Kurang dari')
                            ->placeholder('contoh: 100000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),

                        Forms\Components\TextInput::make('down_payment_more_than')
                            ->label('Down Payment Lebih dari')
                            ->placeholder('contoh: 5000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['down_payment_less_than'],
                                fn(Builder $query, $down_payment) => $query->where('down_payment', '<', $down_payment),
                            )
                            ->when(
                                $data['down_payment_more_than'],
                                fn(Builder $query, $down_payment) => $query->where('down_payment', '>', $down_payment),
                            );
                    }),
                Tables\Filters\Filter::make('late_fees')
                    ->form([
                        Forms\Components\TextInput::make('late_fees_less_than')
                            ->label('Denda terlambat Kurang dari')
                            ->placeholder('contoh: 100000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),

                        Forms\Components\TextInput::make('late_fees_more_than')
                            ->label('Denda terlambat Lebih dari')
                            ->placeholder('contoh: 5000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['late_fees_less_than'],
                                fn(Builder $query, $late_fees) => $query->where('late_fees', '<', $late_fees),
                            )
                            ->when(
                                $data['late_fees_more_than'],
                                fn(Builder $query, $late_fees) => $query->where('late_fees', '>', $late_fees),
                            );
                    }),
                Tables\Filters\Filter::make('total_fees')
                    ->form([
                        Forms\Components\TextInput::make('total_fees_less_than')
                            ->label('Total Pembayaran Kurang dari')
                            ->placeholder('contoh: 100000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),

                        Forms\Components\TextInput::make('total_fees_more_than')
                            ->label('Total Pembayaran Lebih dari')
                            ->placeholder('contoh: 5000.00')
                            ->numeric()
                            ->maxLength(15)
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['total_fees_less_than'],
                                fn(Builder $query, $total_fees) => $query->where('total_fees', '<', $total_fees),
                            )
                            ->when(
                                $data['total_fees_more_than'],
                                fn(Builder $query, $total_fees) => $query->where('total_fees', '>', $total_fees),
                            );
                    }),
            ])
            ->filtersFormColumns(4)
            ->filtersFormSchema(fn(array $filters): array => [
                Forms\Components\Grid::make()->columns(4)
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                $filters['status'],
                                $filters['user.name'],
                                $filters['down_payment'],
                            ])->columnSpan(1),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                $filters['rent_date'],
                                $filters['return_date'],
                                $filters['late_date'],
                                $filters['late_fees'],
                                $filters['total_fees'],
                            ])->columnSpan(3)
                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make('Lihat Detail')
                        ->label('Lihat')
                        ->icon('heroicon-o-eye')
                        ->modalHeading(fn($record) => 'Detail Peminjaman #' . $record->id . ' - ' . $record->name)
                        ->modalWidth('2xl')
                        ->modalContent(fn($record) => view('filament.custom.rental-details', [
                            'rental' => $record,
                            'details' => \App\Models\RentalDetail::where('rental_id', $record->id)
                                ->with(['item'])
                                ->get()
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
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada penyewaan yang ditemukan')
            ->emptyStateDescription('Saat ini tidak ada data penyewaan')
            ->emptyStateIcon('heroicon-o-briefcase');
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
