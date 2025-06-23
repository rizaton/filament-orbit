<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent()
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->prefix('+62')
                    ->required()
                    ->tel()
                    ->maxLength(15)
                    ->placeholder(''),
                TextInput::make('address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan alamat lengkap Anda'),
                Select::make('city')
                    ->label('Kota')
                    ->required()
                    ->options([
                        'Jakarta' => 'Jakarta',
                        'Bogor' => 'Bogor',
                        'Depok' => 'Depok',
                        'Tangerang' => 'Tangerang',
                        'Bekasi' => 'Bekasi',
                        'Luar Jabodetabek' => 'Luar Jabodetabek',
                    ]),
                $this->getEmailFormComponent()
                    ->label('Email'),
                $this->getPasswordFormComponent()
                    ->label('Password'),
                $this->getPasswordConfirmationFormComponent()
                    ->label('Konfirmasi Password'),
            ])
            ->statePath('data');
    }
}
