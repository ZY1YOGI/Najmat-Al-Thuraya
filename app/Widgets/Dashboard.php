<?php

namespace App\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

use App\Models\User;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Expenses;
use App\Models\Phone;

class Dashboard extends BaseWidget
{
    protected ?string $heading = 'Dashboard Analytics';

    protected function getStats(): array
    {
        $now = now();

        $totalRevenue = Payment::sum('amount');
        $monthRevenue = Payment::whereYear('created_at', $now->year)
                               ->whereMonth('created_at', $now->month)
                               ->sum('amount');

        $monthExpenses = Expenses::whereYear('created_at', $now->year)
                                 ->whereMonth('created_at', $now->month)
                                 ->sum('amount');

        return [
            Stat::make('Users', User::where('id', '!=', 1)->count())
                ->icon('heroicon-o-users')
                ->description('Total users (excluding superuser)')
                ->color('primary'),

            Stat::make('Customers', Customer::count())
                ->icon('heroicon-o-user-group')
                ->description('Registered customers')
                ->color('info'),

            Stat::make('Revenue This Month', number_format($monthRevenue, 2))
                ->icon('heroicon-o-currency-dollar')
                ->description("₨ {$monthRevenue} / ₨ {$totalRevenue} total")
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::After)
                ->color('success')
                ->chart(
                    Payment::selectRaw('DAY(created_at) as day, SUM(amount) as sum')
                        ->whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $now->month)
                        ->groupBy('day')
                        ->orderBy('day')
                        ->pluck('sum', 'day')
                        ->values()
                        ->toArray()
                ),

            Stat::make('Expenses This Month', number_format($monthExpenses, 2))
                ->icon('heroicon-o-banknotes')
                ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::After)
                ->color('danger')
                ->chart(
                    Expenses::selectRaw('DAY(created_at) as day, SUM(amount) as sum')
                        ->whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $now->month)
                        ->groupBy('day')
                        ->orderBy('day')
                        ->pluck('sum', 'day')
                        ->values()
                        ->toArray()
                ),

            Stat::make('Phones', Phone::count())
                ->icon('heroicon-o-device-phone-mobile')
                ->description('Inventory stock count')
                ->color('warning'),
        ];
    }
}
