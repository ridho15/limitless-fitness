<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Location;
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
        $listData = [];
        $listWaktu = [];
        $id_kelas = $this->filterFormData['id_kelas'];
        $id_location = $this->filterFormData['id_location'];
        $waktu = $this->filterFormData['waktu'];
        if ($waktu == 'week') {
            $periode = CarbonPeriod::create(Carbon::now()->subDays(7)->isoFormat('YYYY-MM-DD'), date('Y-m-d'));
            foreach ($periode as $key => $value) {
                $jumlah_uang = OrderPaket::when($id_location, function ($query, $id_location) {
                    return $query->where('id_location', $id_location);
                })->when($id_kelas, function ($query, $id_kelas) {
                    return $query->whereHas('paket', function ($query) use ($id_kelas) {
                        return $query->where('id_kelas', $id_kelas);
                    });
                })->whereIn('status', [0, 2])
                    ->whereDate('created_at', $value)
                    ->select(['harga'])
                    ->sum('harga');

                array_push($listData, $jumlah_uang);
                array_push($listWaktu, date('Y-m-d', strtotime($value)));
            }
        } elseif ($waktu == 'month') {
            $periode = CarbonPeriod::create(Carbon::now()->subMonths(1)->isoFormat('YYYY-MM-DD'), date('Y-m-d'))->toArray();
            foreach ($periode as $key => $value) {
                $jumlah_uang = OrderPaket::when($id_location, function ($query, $id_location) {
                    return $query->where('id_location', $id_location);
                })->when($id_kelas, function ($query, $id_kelas) {
                    return $query->whereHas('paket', function ($query) use ($id_kelas) {
                        return $query->where('id_kelas', $id_kelas);
                    });
                })->whereIn('status', [0, 2])
                    ->whereDate('created_at', $value)
                    ->select(['harga'])
                    ->sum('harga');

                array_push($listData, $jumlah_uang);
                array_push($listWaktu, date('Y-m-d', strtotime($value)));
            }
        } elseif ($waktu == 'year') {
            $periode = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            foreach ($periode as $key => $value) {
                $jumlah_uang = OrderPaket::when($id_location, function ($query, $id_location) {
                    return $query->where('id_location', $id_location);
                })->when($id_kelas, function ($query, $id_kelas) {
                    return $query->whereHas('paket', function ($query) use ($id_kelas) {
                        return $query->where('id_kelas', $id_kelas);
                    });
                })->whereIn('status', [0, 2])
                    ->whereMonth('created_at', $key + 1)
                    ->select(['harga'])
                    ->sum('harga');

                array_push($listData, $jumlah_uang);
            }
            $listWaktu = $periode;
        } else {
            $listJumlahUang = OrderPaket::when($id_location, function ($query, $id_location) {
                return $query->where('id_location', $id_location);
            })->when($id_kelas, function ($query, $id_kelas) {
                return $query->whereHas('paket', function ($query) use ($id_kelas) {
                    return $query->where('id_kelas', $id_kelas);
                });
            })->whereIn('status', [0, 2])
                ->whereDate('created_at', now())
                ->orderBy('created_at', 'ASC')
                ->select(['harga', 'created_at'])
                ->get();

            foreach ($listJumlahUang as $item) {
                array_push($listData, $item->harga);
                array_push($listWaktu, date('H:i', strtotime($item->created_at)));
            }
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
                    'data' => $listData,
                ],
            ],
            'xaxis' => [
                // 'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'categories' => $listWaktu,
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
                ->options(Kelas::get()->pluck('nama_kelas', 'id')),
            Select::make('id_location')
                ->label('Lokasi')
                ->searchable()
                ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id')),
            Select::make('waktu')
                ->label('Waktu')
                ->searchable()
                ->options([
                    'today' => 'Today',
                    'week' => 'Last Week',
                    'month' => 'Last Month',
                    'year' => 'This Year'
                ])->default('today')
        ];
    }
}
