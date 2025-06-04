<?php

namespace App\Filament\Resources\RentalDetailResource\Pages;

use App\Filament\Resources\RentalDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalDetail extends EditRecord
{
    protected static string $resource = RentalDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Detail Penyewaan')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->tooltip('Hapus detail penyewaan ini'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Detail penyewaan berhasil diperbarui';
    }
}
