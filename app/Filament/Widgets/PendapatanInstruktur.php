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
        return "Pendapatan";
    }

    protected function getOptions(): array
    {
        $periode = CarbonPeriod::create($this->filterFormData['date_start'], $this->filterFormData['date_end']);
        $listFee = [];
        $listTanggal = [];
        foreach ($periode as $item) {
            $listKelasActivity = KelasActivity::whereHas('bookingKelas', function ($query) {
                return $query->whereIn('status_kehadiran', [0, 1])
                    ->select(['status_kehadiran']);
            })->withCount('bookingKelas')
                ->having('booking_kelas_count', '>=', 2)->when($this->filterFormData['id_kelas'], function ($query) {
                    return $query->where('id_kelas', $this->filterFormData['id_kelas']);
                })
                ->whereNotNull('id_location_studio')
                ->when(Auth::user()->hasRole('Instruktur'), function ($query) {
                    return $query->where('id_user', Auth::id());
                })
                ->whereDate('tanggal', $item->format('Y-m-d'))->get();
            $omset = 0;
            foreach ($listKelasActivity as $kelasActivity) {
                $jumlahPeserta = 0;
                $omset += $kelasActivity->kelas->fee;
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
                    'name' => 'Omset',
                    'data' => $listFee,
                ],
            ],
            'xaxis' => [
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
                ->default(Carbon::now()->startOfMonth()->isoFormat('YYYY-MM-DD')),
            DatePicker::make('date_end')
                ->disabled()
                ->default(Carbon::now()->endOfMonth()->isoFormat('YYYY-MM-DD')),
        ];
    }
}
