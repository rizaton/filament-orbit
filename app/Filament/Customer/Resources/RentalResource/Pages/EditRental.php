<?php

namespace App\Filament\Customer\Resources\RentalResource\Pages;

use App\Filament\Customer\Resources\RentalResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema(RentalResource::getFormSchema())
            ->columns(1)
            ->statePath('record')
            ->statePath('record');
    }
}
