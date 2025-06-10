<?php

namespace App\Filament\Customer\Resources\RentalResource\Pages;

use App\Filament\Customer\Resources\RentalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentals extends ListRecords
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Sewa Alat')
                ->createAnother(false)
                ->modalDescription('Isi data penyewaan dengan lengkap dan benar. Pastikan semua informasi yang diberikan akurat untuk memproses penyewaan Anda.')
                ->modalHeading('Sewa Alat')
                ->modalSubmitActionLabel('Sewa')
                ->modalCancelActionLabel('Batal')
                ->icon('heroicon-o-plus'),
        ];
    }
}
