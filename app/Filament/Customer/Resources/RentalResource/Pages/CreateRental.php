<?php

namespace App\Filament\Customer\Resources\RentalResource\Pages;

use Exception;
use Filament\Actions;
use App\Models\Rental;
use App\Models\RentalDetail;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Customer\Resources\RentalResource;
use Carbon\Carbon;

class CreateRental extends CreateRecord
{
    protected static string $resource = RentalResource::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $title = 'Sewa Alat';

    protected function getRedirectUrl(): string
    {
        try {
            $rentalData = $this->record;
            $rentalData->loadMissing(['rentalDetails.item', 'user']);
            $rentalData->recalculateTotals();
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data Sewa alat berhasil ditambahkan';
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
    public static function saved(Rental $rental): void
    {
        $totalFees = $rental->rentalDetails->sum(function ($detail) {
            return $detail->item?->rent_price * $detail->quantity ?? 0;
        });

        $user = $rental->user;

        if (!$user || !$user->city) return;

        $downPayment = match (true) {
            $user->city === 'Luar Jabodetabek' => $totalFees,
            $totalFees > 2000000 => $totalFees * 0.5,
            default => $totalFees * 0.25,
        };

        $rental->update([
            'total_fees' => $totalFees,
            'down_payment' => $downPayment,
        ]);
    }
}
