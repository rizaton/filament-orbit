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
            $rentDate = Carbon::parse($rentalData->rent_date);
            $returnDate = Carbon::parse($rentalData->return_date);
            $rentDateDiff = $rentDate->diffInDays($returnDate);
            $rentDateDiff += 1;
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
