<?php

namespace App\Filament\Admin\Resources\RentalResource\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RentalChart extends ChartWidget
{
    protected static ?string $heading = 'Diagram Sewa - Tahun Ini';

    protected static bool $isLazy = true;

    protected static ?string $pollingInterval = '10s';

    protected static ?string $maxHeight = '235px';

    protected static string $color = 'info';

    public function getDescription(): ?string
    {
        return 'Jumlah sewa yang dilakukan per bulan sepanjang tahun ini.';
    }

    protected function getData(): array
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        // Query rentals grouped by month
        $rentals = Rental::query()
            ->selectRaw('MONTH(rent_date) as month, COUNT(*) as total')
            ->whereBetween('rent_date', [$startOfYear, $endOfYear])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Prepare month labels (Jan to Dec)
        $labels = [];
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create()->month($month)->format('M');
            $data[] = $rentals[$month]->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sewa Bulanan',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                        'callback' => 'function(value) { return Number.isInteger(value) ? value : null; }',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
