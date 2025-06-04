<?php

namespace App\Filament\Resources\ItemResource\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemChart extends ChartWidget
{
    protected static ?string $heading = 'Alat Statistik';

    protected static bool $isLazy = true;

    protected static ?string $pollingInterval = '10s';

    protected static string $color = 'info';

    protected function getData(): array
    {
        $totalItems = Item::count();
        $availableItems = Item::where('is_available', true)->count();
        $unavailableItems = Item::where('is_available', false)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Items',
                    'data' => [
                        $totalItems,
                        $availableItems,
                        $unavailableItems,
                    ],
                    'backgroundColor' => [
                        '#3B82F6', // Biru untuk semua alat
                        '#10B981', // Hijau untuk tersedia
                        '#EF4444', // Merah untuk tidak tersedia
                    ],
                ],
            ],
            'labels' => ['Total', 'Tersedia', 'Tidak Tersedia'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
