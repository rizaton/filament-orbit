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
            Actions\DeleteAction::make(),
        ];
    }
}
