<?php

namespace App\Filament\Widgets;

use App\Models\Jurnal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\LineChartWidget;

class OmsetChart extends LineChartWidget
{
    // protected static ?string $heading = 'Omset Chart';

    protected static ?int $sort = 3;

    protected function getHeading(): ?string
    {
        return 'Omset Chart';
    }

    protected function getData(): array
    {
        $listData = [];
        $listWaktu = [];
        if ($this->filter == 'today') {
            $jurnal = Jurnal::whereDate('tanggal', now())
                ->orderBy('tanggal')
                ->where('tipe', 1)
                ->get();

            foreach ($jurnal as $item) {
                array_push($listData, $item->jumlah_uang);
                array_push($listWaktu, date('H:i', strtotime($item->tanggal)));
            }
        } elseif ($this->filter == 'week') {
            $periode = CarbonPeriod::create(Carbon::now()->subDays(6)->isoFormat('YYYY-MM-DD'), date('Y-m-d'));
            foreach ($periode as $key => $value) {
                $jumlah_uang = Jurnal::whereDate('tanggal', $value)
                    ->where('tipe', 1)
                    ->sum('jumlah_uang');

                array_push($listData, $jumlah_uang);
                array_push($listWaktu, date('Y-m-d', strtotime($value)));
            }
        } elseif ($this->filter == 'month') {
            $periode = CarbonPeriod::create(Carbon::now()->subMonths(1)->isoFormat('YYYY-MM-DD'), date('Y-m-d'))->toArray();
            foreach ($periode as $key => $value) {
                $jumlah_uang = Jurnal::whereDate('tanggal', $value)
                    ->where('tipe', 1)
                    ->sum('jumlah_uang');

                array_push($listData, $jumlah_uang);
                array_push($listWaktu, date('Y-m-d', strtotime($value)));
            }
        } elseif ($this->filter == 'year') {
            $periode = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            foreach ($periode as $index => $item) {
                $jumlah_uang = Jurnal::whereMonth('tanggal', $index + 1)
                    ->where('tipe', 1)
                    ->sum('jumlah_uang');

                array_push($listData, $jumlah_uang);
                array_push($listWaktu, $item);
            }
        } else {
            $jurnal = Jurnal::orderBy('tanggal')
                ->where('tipe', 1)
                ->get();

            foreach ($jurnal as $item) {
                array_push($listData, $item->jumlah_uang);
                array_push($listWaktu, date('H:i', strtotime($item->tanggal)));
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omset',
                    // 'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'data' => $listData,
                ],
            ],
            // 'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'labels' => $listWaktu,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This Year',
        ];
    }
}
