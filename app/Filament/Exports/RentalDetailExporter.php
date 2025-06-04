<?php

namespace App\Filament\Exports;

use App\Models\RentalDetail;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class RentalDetailExporter extends Exporter
{
    protected static ?string $model = RentalDetail::class;

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
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('rental.name')
                ->label('Nama Penyewa'),
            ExportColumn::make('item.name')
                ->label('Nama Alat'),
            ExportColumn::make('quantity')
                ->label('Jumlah Sewa'),
            ExportColumn::make('is_returned')
                ->label('Sudah Dikembalikan'),
            ExportColumn::make('sub_total')
                ->label('Sub Total'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Tanggal Diperbarui'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your rental detail export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
