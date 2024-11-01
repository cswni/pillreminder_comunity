<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Medicines', '192.1k'),
            Stat::make('Treatments', '192.1k'),
            Stat::make('Events', '192.1k'),

        ];
    }
}
