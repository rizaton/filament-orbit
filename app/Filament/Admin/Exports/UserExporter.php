<?php

namespace App\Filament\Admin\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
        ];
    }

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
                ->label('Status Pengguna'),
            ExportColumn::make('phone')
                ->label('Nomor Telepon Pengguna'),
            ExportColumn::make('address')
                ->label('Alamat Pengguna'),
            ExportColumn::make('city')
                ->label('Kota Pengguna'),
            ExportColumn::make('email_verified_at')
                ->label('Email Terverifikasi'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Tanggal Diperbarui'),
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
