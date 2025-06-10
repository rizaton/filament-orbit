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
        $user = Auth::user();

        $rentalRequestCount = Rental::where('id_user', $user->id)
            ->where('status', 'pending')
            ->count();

        $rentalAcceptedCount = Rental::where('id_user', $user->id)
            ->where('status', 'approved')
            ->count();

        $rentedCount = Rental::where('id_user', $user->id)
            ->where('status', 'rented')
            ->count();
        $lateRentalCount = Rental::where('id_user', $user->id)
            ->where('status', 'late')
            ->count();

        $totalItemsRented = RentalDetail::whereHas(
            'rental',
            function ($query) use ($user) {
                $query->where('id_user', $user->id)
                    ->where('status', 'rented');
            }
        )->where('is_returned', false)->sum('quantity');

        $totalItemsNotReturned = RentalDetail::whereHas(
            'rental',
            function ($query) use ($user) {
                $query->where('id_user', $user->id)
                    ->where('status', 'returned');
            }
        )->where('is_returned', false)->count();

        return [
            Stat::make('Pengajuan Sewa', $rentalRequestCount)
                ->color(Color::Blue)
                ->description('Total pengajuan sewa yang sedang berlangsung'),

            Stat::make('Sewa Disetujui', $rentalAcceptedCount)
                ->color(Color::Yellow)
                ->description('Total penyewaan yang telah disetujui'),

            Stat::make('Sewa Berlangsung', $rentedCount)
                ->color(Color::Yellow)
                ->description('Total sewa yang sedang berlangsung'),

            Stat::make('Alat Disewa', $totalItemsRented)
                ->color(Color::Fuchsia)
                ->description('Jumlah alat yang sedang disewa'),

            Stat::make('Telat Kembali', $lateRentalCount)
                ->color(Color::Red)
                ->description('Jumlah sewa yang terlambat dikembalikan'),

            Stat::make('Alat Belum Kembali', $totalItemsNotReturned)
                ->color(Color::Red)
                ->description('Alat yang belum dikembalikan'),
        ];
    }
}
