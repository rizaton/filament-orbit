<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Form;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Profil')
                    ->description('Perbarui informasi profil Anda di sini.')
                    ->schema([
                        Grid::make(2)->schema([
                            $this->getNameFormComponent()->label('Name'),
                            $this->getEmailFormComponent()->label('Alamat Email'),
                        ]),
                    ])
                    ->columns(1),

                Section::make('Ubah Kata Sandi')
                    ->description('Jika Anda ingin mengubah kata sandi, silakan masukkan kata sandi baru di bawah ini.')
                    ->schema([
                        Grid::make(2)->schema([
                            $this->getPasswordFormComponent()->label('Ubah Kata Sandi Saat Ini'),
                            $this->getPasswordConfirmationFormComponent()->label('Konfirmasi Kata Sandi'),
                        ]),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }
}
