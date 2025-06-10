<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class AdminStatsOverview extends \Filament\Widgets\StatsOverviewWidget
{
    private static function statModels(string $model): Model
    {
        $models = [
            'rental' => \App\Models\Rental::class,
            'item' => \App\Models\Item::class,
        ];
        return new $models[$model];
    }

    protected static bool $isLazy = true;

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Penyewaan belum diproses', self::statModels('rental')::where('status', 'pending')->count())
                ->description('Penyewaan')
                ->color('warning'),
            Stat::make('Penyewaan Berlangsung', self::statModels('rental')::where('status', 'approved')->count())
                ->description('Berlangsung')
                ->color('success'),
            Stat::make('Alat yang tidak tersedia', self::statModels('item')::where([
                ['stock', '<=', 0],
                ['is_available', true],
            ])->count())
                ->description('Tidak Tersedia dari ' . self::statModels('item')::count() . ' Alat')
                ->color('danger'),
        ];
    }
}
