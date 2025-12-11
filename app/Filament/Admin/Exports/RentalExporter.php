<?php

namespace App\Filament\Admin\Exports;

use App\Models\Rental;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class RentalExporter extends Exporter
{
    protected static ?string $model = Rental::class;

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
            ExportFormat::Xlsx,
        ];
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_rental')
                ->label('ID Sewa'),
            ExportColumn::make('user.name')
                ->label('Nama Penyewa'),
            ExportColumn::make('user.address')
                ->label('Alamat Penyewa'),
            ExportColumn::make('user.phone')
                ->label('Telepon Penyewa'),
            ExportColumn::make('status')
                ->label('Status Sewa'),
            ExportColumn::make('down_payment')
                ->label('Uang Muka'),
            ExportColumn::make('rent_date')
                ->label('Tanggal Sewa'),
            ExportColumn::make('return_date')
                ->label('Tanggal Pengembalian'),
            ExportColumn::make('late_date')
                ->label('Tanggal Keterlambatan'),
            ExportColumn::make('late_fees')
                ->label('Denda Keterlambatan'),
            ExportColumn::make('total_fees')
                ->label('Total Biaya'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Tanggal Diperbarui'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your rental export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
