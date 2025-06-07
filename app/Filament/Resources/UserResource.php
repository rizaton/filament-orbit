<?php

namespace App\Filament\Resources;

use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Pengguna';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $breadcrumb = 'Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email Pengguna')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('is_admin')
                    ->label('Status Pengguna')
                    ->options([
                        0 => 'Pengguna Biasa',
                        1 => 'Pengguna Admin',
                    ])
                    ->default('0')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon Pengguna')
                    ->prefix('+62')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('address')
                    ->label('Alamat Pengguna')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('city')
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
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Waktu Verifikasi Email')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi Pengguna')
                    ->password()
                    ->revealable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(UserExporter::class)
                    ->label('Ekspor Pengguna')
                    ->fileName('users_export_' . now()->format('Y_m_d_H_i_s'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Pengguna')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('phone')
                    ->prefix('+62')
                    ->label('Telepon Pengguna')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->icon('heroicon-m-building-office')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Pengguna')
                    ->searchable()
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('primary')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\CheckboxColumn::make('is_admin')
                    ->label('Admin')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_admin')
                    ->label('Status Pengguna')
                    ->options([
                        0 => 'Pengguna Biasa',
                        1 => 'Pengguna Admin',
                    ]),
                Tables\Filters\Filter::make('verified_email')
                    ->label('Filter Verifikasi Email')
                    ->form([
                        Forms\Components\Select::make('email_verified_at')
                            ->options([
                                'verified' => 'Terverifikasi',
                                'unverified' => 'Tidak Terverifikasi',
                            ])->default(null)
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['email_verified_at'] === 'verified',
                                fn(Builder $query) => $query->whereNotNull('email_verified_at'),
                            )
                            ->when(
                                $data['email_verified_at'] === 'unverified',
                                fn(Builder $query) => $query->whereNull('email_verified_at'),
                            );
                    }),
                Tables\Filters\Filter::make('city')
                    ->label('Kota Pengguna')
                    ->form([
                        Forms\Components\Select::make('city')
                            ->label('Kota')
                            ->options([
                                'Jakarta' => 'Jakarta',
                                'Bogor' => 'Bogor',
                                'Depok' => 'Depok',
                                'Tangerang' => 'Tangerang',
                                'Bekasi' => 'Bekasi',
                                'Luar Jabodetabek' => 'Luar Jabodetabek',
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['city'] ?? null,
                                fn(Builder $query) => $query->where('city', $data['city'])
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
