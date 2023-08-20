<?php

namespace App\Filament\Widgets;

use App\Models\BookingKelas;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\OrderPaket;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PendapatanInstruktur extends ApexChartWidget
{
    protected static string $chartId = 'Total';

    protected int | string | array $columnSpan = 'full';

    protected function getHeading(): ?string
    {
        // return "Order Paket " . "(" . $this->id_kelas . ")";
        return "Pendapatan";
    }

    protected function getOptions(): array
    {
        $periode = CarbonPeriod::create($this->filterFormData['date_start'], $this->filterFormData['date_end']);
        $listFee = [];
        $listTanggal = [];
        foreach ($periode as $item) {
            $listKelasActivity = KelasActivity::with(['kelas', 'bookingKelas' => function ($query) {
                $query->where(function ($query) {
                    $query->where('status_kehadiran', '1')
                        ->orWhere('status_kehadiran', '0');
                });
            }])->where('id_kelas', $this->filterFormData['id_kelas'])
                ->where('id_location_studio', '!=', null)
                ->where(function ($query) {
                    if (Auth::user()->hasRole('Instruktur')) {
                        $query->where('id_user', Auth::id());
                    }
                })->whereDate('tanggal', $item)->get();

            $omset = 0;
            foreach ($listKelasActivity as $item) {
                $jumlahPeserta = 0;
                foreach ($item->bookingKelas as $bookingKelas) {
                    if ($bookingKelas->status_kehadiran == '1' || $bookingKelas->status_kehadiran == '0') {
                        $jumlahPeserta++;
                    }
                }

                if ($item->kelas->min_anggota <= $jumlahPeserta) {
                    $omset += $item->kelas->fee;
                }
            }

            array_push($listFee, $omset);
            array_push($listTanggal, date('Y-m-d', strtotime($item)));
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Total',
                    // 'data' => [7, 4, 6, 10, 14, 7, 5, 9, 10, 15, 13, 18],
                    'data' => $listFee,
                ],
            ],
            'xaxis' => [
                // 'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'categories' => $listTanggal,
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
                ->default(Carbon::now()->subDays(7)->isoFormat('YYYY-MM-DD')),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }
}
