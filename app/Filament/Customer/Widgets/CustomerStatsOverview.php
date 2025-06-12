<?php

namespace App\Filament\Customer\Widgets;

use App\Models\User as UserModel;
use App\Models\RentalDetail;
use App\Models\Rental;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CustomerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::user()->id_user;

        $rentalCounts = Rental::where('id_user', $userId)
            ->whereIn('status', ['pending', 'approved', 'rented', 'late'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalItemsRented = RentalDetail::whereHas('rental', function ($query) use ($userId) {
            $query->where('id_user', $userId)
                ->where('status', 'rented');
        })->where('is_returned', false)->sum('quantity');

        $totalItemsNotReturned = RentalDetail::whereHas('rental', function ($query) use ($userId) {
            $query->where('id_user', $userId)
                ->where('status', 'returned');
        })->where('is_returned', false)->count();

        return [
            Stat::make('Pengajuan Sewa', $rentalCounts['pending'] ?? 0)
                ->color(Color::Blue)
                ->description('Total pengajuan sewa yang sedang berlangsung'),

            Stat::make('Sewa Disetujui', $rentalCounts['approved'] ?? 0)
                ->color(Color::Yellow)
                ->description('Total penyewaan yang telah disetujui'),

            Stat::make('Sewa Berlangsung', $rentalCounts['rented'] ?? 0)
                ->color(Color::Yellow)
                ->description('Total sewa yang sedang berlangsung'),

            Stat::make('Alat Disewa', $totalItemsRented)
                ->color(Color::Fuchsia)
                ->description('Jumlah alat yang sedang disewa'),

            Stat::make('Telat Kembali', $rentalCounts['late'] ?? 0)
                ->color(Color::Red)
                ->description('Jumlah sewa yang terlambat dikembalikan'),

            Stat::make('Alat Belum Kembali', $totalItemsNotReturned)
                ->color(Color::Red)
                ->description('Alat yang belum dikembalikan'),
        ];
    }
}
