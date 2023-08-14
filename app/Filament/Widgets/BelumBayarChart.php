<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\OrderPaket;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BelumBayarChart extends ApexChartWidget
{
    protected static string $chartId = 'Total';

    protected function getHeading(): ?string
    {
        // return "Order Paket " . "(" . $this->id_kelas . ")";
        return "Order Paket (Belum Bayar)";
    }

    protected function getOptions(): array
    {
        $data = Trend::query(OrderPaket::whereHas('paket', function ($query) {
            $query->where('id_kelas', $this->filterFormData['id_kelas']);
        })->where('status', 2))
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )->perDay()
            ->sum('harga');

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Total',
                    // 'data' => [7, 4, 6, 10, 14, 7, 5, 9, 10, 15, 13, 18],
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                // 'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'categories' => $data->map(fn (TrendValue $value) => $value->date),
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('id_kelas')
                ->label('Kelas')
                ->searchable()
                ->preload()
                ->options(Kelas::get()->pluck('nama_kelas', 'id')),
            DatePicker::make('date_start')
                ->default(now()),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }
}
