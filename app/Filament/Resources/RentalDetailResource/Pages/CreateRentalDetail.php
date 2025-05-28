<?php

namespace App\Filament\Resources\RentalDetailResource\Pages;

use App\Filament\Resources\RentalDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRentalDetail extends CreateRecord
{
    protected static string $resource = RentalDetailResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Detail penyewaan baru berhasil ditambahkan';
    }
}
