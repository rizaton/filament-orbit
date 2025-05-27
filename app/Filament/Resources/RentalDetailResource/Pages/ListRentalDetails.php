<?php

namespace App\Filament\Resources\RentalDetailResource\Pages;

use App\Filament\Resources\RentalDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalDetails extends ListRecords
{
    protected static string $resource = RentalDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
