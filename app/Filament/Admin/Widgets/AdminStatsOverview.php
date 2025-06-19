<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Rental;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AdminStatsOverview extends BaseWidget
{
    private function statModels()
    {
        $rentalCounts = Rental::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        return $rentalCounts;
    }

    protected static bool $isLazy = true;

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $statusCounts = self::statModels();
        return [
            Stat::make('Penyewaan belum diproses', $statusCounts['pending'] ?? 0)
                ->description('Penyewaan')
                ->color(Color::Yellow),
            Stat::make('Penyewaan Berlangsung', $statusCounts['rented'] ?? 0)
                ->description('Berlangsung')
                ->color(Color::Green),
            Stat::make('Dalam Pengembalian', $statusCounts['returning'] ?? 0)
                ->description('Pengembalian')
                ->color(Color::Red),
        ];
    }
}
