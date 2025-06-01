<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    private $models = [
        'rental' => \App\Models\Rental::class,
        'item' => \App\Models\Item::class,
        'category' => \App\Models\Category::class,
    ];

    protected ?string $heading = 'Statistik';

    protected ?string $description = 'Statistik penyewaan dan inventori';

    protected static bool $isLazy = true;

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Penyewaan belum diproses', $this->models['rental']::where('status', 'pending')->count())
                ->description('Penyewaan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
            // ->color('success'),
            Stat::make('Penyewaan Berlangsung', $this->models['rental']::where('status', 'approved')->count())
                ->description('Berlangsung')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success'),
            // ->color('danger'),
            Stat::make('Alat yang tidak tersedia', $this->models['item']::where([
                ['stock', '<=', 0],
                ['is_available', true],
            ])->count())
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
