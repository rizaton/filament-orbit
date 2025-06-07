<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_user')
                ->label('ID Pengguna'),
            ExportColumn::make('name')
                ->label('Nama Pengguna'),
            ExportColumn::make('email')
                ->label('Email Pengguna'),
            ExportColumn::make('is_admin')
                ->label('Status Pengguna')
                ->formatUsing(fn(bool $value) => $value ? 'Admin' : 'Pelanggan Biasa'),
            ExportColumn::make('phone')
                ->label('Nomor Telepon Pengguna'),
            ExportColumn::make('address')
                ->label('Alamat Pengguna'),
            ExportColumn::make('city')
                ->label('Kota Pengguna'),
            ExportColumn::make('email_verified_at')
                ->label('Email Terverifikasi')
                ->formatUsing(fn($value) => $value ? $value->format('Y-m-d H:i:s') : 'Tidak Terverifikasi'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->formatUsing(fn($value) => $value->format('Y-m-d H:i:s')),
            ExportColumn::make('updated_at')
                ->label('Tanggal Diperbarui')
                ->formatUsing(fn($value) => $value->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
