<?php

namespace App\Resources\ExpensesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Expenses;
use Illuminate\Support\Number;

class Overview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = Expenses::selectRaw('
            SUM(amount) AS total_amount,
            MIN(amount) AS min_price,
            AVG(amount) AS avg_price,
            MAX(amount) AS max_price
        ')->first();

        return [
            Stat::make(trans('field.total_amount'), Number::currency($stats->total_amount ?? 0, 'EGP')),
            Stat::make(trans('field.min_price'), Number::currency($stats->min_price ?? 0, 'EGP')),
            Stat::make(trans('field.average_price'), Number::currency($stats->avg_price ?? 0, 'EGP')),
            Stat::make(trans('field.max_price'), Number::currency($stats->max_price ?? 0, 'EGP')),
        ];
    }
}
