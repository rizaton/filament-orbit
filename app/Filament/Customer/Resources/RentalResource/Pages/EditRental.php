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
use Illuminate\Support\Facades\Log;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    public function form(Form $form): Form
    {
        if ($this->record->status === "pending") {
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
        } else {
            Log::error('Attempted to edit rental with unsupported status: ' . $this->record->status, [
                'rental_id' => $this->record->id,
                'status' => $this->record->status,
                'timestamp' => now(),
            ]);
            return RentalResource::form($form);
        }
    }
    protected function getRedirectUrl(): string
    {
        $rentalData = $this->record;

        if ($rentalData) {
            try {
                $dirtyReturnDate = $rentalData->getChanges()['return_date'] ?? null;
                $rentalData->loadMissing(['rentalDetails.item', 'user']);
                $rentalData->recalculateTotals($dirtyReturnDate);
            } catch (\Throwable $th) {
                Log::error('Error calculating rental fees', [
                    'rental_id' => $rentalData->id,
                    'error' => $th->getMessage(),
                    'timestamp' => now(),
                ]);
                throw $th;
            }
        }
        return $this->getResource()::getUrl('index');
    }
}
