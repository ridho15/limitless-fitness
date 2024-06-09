<?php

namespace App\Filament\Resources\PaketResource\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;

class PaketStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Testing', 1)
        ];
    }
}
