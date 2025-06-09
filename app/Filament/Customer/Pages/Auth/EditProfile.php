<?php

namespace App\Filament\Customer\Pages\Auth;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

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
                            TextInput::make('phone')
                                ->label('Nomor Telepon')
                                ->tel()
                                ->required()
                                ->maxLength(15)
                                ->placeholder('Masukkan nomor telepon Anda')
                                ->helperText('Pastikan nomor telepon Anda valid untuk menerima notifikasi penting.'),
                            TextInput::make('address')
                                ->label('Alamat')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Masukkan alamat Anda')
                                ->helperText('Masukkan alamat lengkap Anda untuk pengiriman atau komunikasi.'),
                            Select::make('city')
                                ->label('Kota Pengguna')
                                ->required()
                                ->options([
                                    'Jakarta' => 'Jakarta',
                                    'Bogor' => 'Bogor',
                                    'Depok' => 'Depok',
                                    'Tangerang' => 'Tangerang',
                                    'Bekasi' => 'Bekasi',
                                    'Luar Jabodetabek' => 'Luar Jabodetabek',
                                ])->default('jakarta'),
                        ]),
                    ])
                    ->columns(1),

                Section::make('Ubah Kata Sandi')
                    ->description('Jika Anda ingin mengubah kata sandi, silakan masukkan kata sandi baru di bawah ini.')
                    ->schema([
                        Grid::make(2)->schema([
                            $this->getPasswordFormComponent()
                                ->label('Ubah Kata Sandi Saat Ini'),
                            $this->getPasswordConfirmationFormComponent()
                                ->label('Konfirmasi Kata Sandi'),
                        ]),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data')
            ->inlineLabel(! static::isSimple());
    }
}
