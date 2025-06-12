<?php

namespace App\Filament\Customer\Resources\RentalResource\Pages;

use Exception;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\DatePicker;
use App\Filament\Customer\Resources\RentalResource;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }
    protected function getRedirectUrl(): string
    {
        if ($this->record) {
            $rentalData = $this->record;
            $dirtyReturnDate = $this->record->getChanges()['return_date'] ?? $rentalData->return_date;
            try {
                $rentDate = Carbon::parse($rentalData->rent_date);
                $changedReturnDate = Carbon::parse($dirtyReturnDate);
                $rentDateDiff = $rentDate->diffInDays($changedReturnDate);
                $rentDateDiff += 1;
                dd($rentDate, $changedReturnDate, $rentDateDiff);
                $totalFees = $rentalData->rentalDetails->sum(function ($detail) use ($rentDateDiff) {
                    return $detail->item?->rent_price * $rentDateDiff * $detail->quantity ?? 0;
                });
                $user = $rentalData->user;
                if (!$user || !$user->city) throw new Exception('User or city not found');

                $downPayment = match (true) {
                    $user->city === 'Luar Jabodetabek' => $totalFees,
                    $totalFees > 2000000 => $totalFees * 0.5,
                    default => $totalFees * 0.25,
                };
                $rentalData->update([
                    'total_fees' => $totalFees,
                    'down_payment' => $downPayment,
                ]);
            } catch (\Throwable $th) {
                throw new Exception($th);
            }
        }
        return $this->getResource()::getUrl('index');
    }
}
