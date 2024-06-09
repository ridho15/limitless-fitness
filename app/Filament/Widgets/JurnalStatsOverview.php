<?php

namespace App\Filament\Widgets;

use App\Models\Jurnal;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class JurnalStatsOverview extends BaseWidget
{
    protected $listeners = ['updateFilterFromTable'];
    public $fromDate;
    public $toDate;
    public $id_location;
    protected function getCards(): array
    {
        $omset = Jurnal::when($this->fromDate, function ($query) {
            $query->whereDate('tanggal', '>=', $this->fromDate);
        })
            ->when($this->toDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->toDate);
            })
            ->when($this->id_location, function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_location', $this->id_location);
                });
            })->sum('jumlah_uang');

        $pengeluaran = Jurnal::when($this->fromDate, function ($query) {
            $query->whereDate('tanggal', '>=', $this->fromDate);
        })
            ->when($this->toDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->toDate);
            })
            ->when($this->id_location, function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_location', $this->id_location);
                });
            })->where('tipe', 0)->sum('jumlah_uang');

        $pemasukan = Jurnal::when($this->fromDate, function ($query) {
            $query->whereDate('tanggal', '>=', $this->fromDate);
        })
            ->when($this->toDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->toDate);
            })
            ->when($this->id_location, function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_location', $this->id_location);
                });
            })->where('tipe', 1)->sum('jumlah_uang');
        return [
            Card::make('Omset', 'Rp.' . number_format($omset, 0, ',', '.'))
                ->description('Jumlah pemasukan dan pengeluaran')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
            Card::make('Pemasukan', 'Rp.' . number_format($pemasukan, 0, ',', '.'))
                ->description('Jumlah pemasukan')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
            Card::make('Pengeluaran', 'Rp.' . number_format($pengeluaran, 0, ',', '.'))
                ->description('Jumlah pengeluaran')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
        ];
    }

    public function updateFilterFromTable($fromDate, $toDate, $id_location)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->id_location = $id_location;
    }
}
