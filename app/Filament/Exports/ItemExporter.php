<?php

namespace App\Filament\Exports;

use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;

class ItemExporter extends Exporter
{
    protected static ?string $model = Item::class;

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
            ExportColumn::make('category_id')
                ->label('ID Kategori'),
            ExportColumn::make('category.name')
                ->label('Nama Kategori'),
            ExportColumn::make('name')
                ->label('Nama Alat'),
            ExportColumn::make('slug')
                ->label('Slug Alat'),
            ExportColumn::make('stock')
                ->label('Stok Alat'),
            ExportColumn::make('description')
                ->label('Deskripsi Alat'),
            ExportColumn::make('is_available')
                ->label('Tersedia'),
            ExportColumn::make('rent_price')
                ->label('Harga Sewa'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Tanggal Diperbarui'),
            ExportColumn::make('image')
                ->label('Gambar Alat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
