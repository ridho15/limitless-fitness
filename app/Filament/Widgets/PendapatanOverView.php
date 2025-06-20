<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BiayaInstruktruResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\InstrukturOmsetResource;
use App\Filament\Resources\JurnalResource;
use App\Filament\Resources\OmsetResource;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\Location;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PendapatanOverView extends BaseWidget
{
    public $readyToLoad = false;

    protected static string $view = 'custom-widget.filter-widget-dashboard';

    protected static ?string $pollingInterval = null;

    public $listeners = [
        'onClickOmset',
        'onClickFeeInstruktur',
        'onClickCustomer',
        'onClickProfit',
        'onClickBiayaInstruktur'
    ];

    public $listLocation = [];
    public $id_location;

    public function mount()
    {
        $this->listLocation = Location::get();
    }

    public function loadData()
    {
        $this->readyToLoad = true;
    }

    protected function getCards(): array
    {
        $listCard = [];
        if (!$this->readyToLoad) {
            array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format(0, 0, ',', '.'))
                ->description('Omset dalam bulan ini loading ...')->color('primary'));
            array_push($listCard, Card::make('Biaya Instruktur', 'Rp.' . number_format(0, 0, ',', '.'))->description('Biaya instruktur per order paket bulan ini loading ...')->color('primary'));
            array_push($listCard, Card::make('Fee Instruktur', 'Rp.' . number_format(0, 0, ',', '.'))->description('Pembayaran instruktur per order paket bulan ini loading ...')->color('primary'));
            array_push($listCard, Card::make('Customer Bulan ini', number_format(0, 0, ',', '.'))->description('Jumlah customer tambahan bulan ini loading ...')->color('primary'));
            array_push($listCard, Card::make('Profit Bulan ini', 'Rp.' . number_format(0, 0, ',', '.'))->description('Total profit dalam 1 bulan ini loading ...')->color('primary'));

            return $listCard;
        } else {
            // Omset dalam 1 bulan
            $omsetDalamSatuBulan = Jurnal::when($this->id_location, function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_location', $this->id_location);
                });
            })->whereHas('orderPaket')->whereMonth('created_at', now())
                ->where('tipe', 1)->sum('jumlah_uang');

            array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format($omsetDalamSatuBulan, 0, ',', '.'))
                ->description('Omset dalam bulan ini')->color('primary')
                ->extraAttributes([
                    'wire:click' => '$emit("onClickOmset")'
                ]));

            // Biaya Instruktur
            $biayaInstruktur = Jurnal::when($this->id_location, function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_location', $this->id_location);
                });
            })->whereNotNull('id_parent')
                ->where('tipe', 0)
                ->whereMonth('created_at', now())
                ->sum('jumlah_uang');

            array_push($listCard, Card::make('Biaya Instruktur', 'Rp.' . number_format($biayaInstruktur, 0, ',', '.'))->description('Biaya instruktur per order paket bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickBiayaInstruktur")'
            ]));

            // Fee instruktur
            $listKelas = Kelas::select(['id', 'min_anggota'])->get();
            $feeInstruktur = 0;
            foreach ($listKelas as $item) {
                $feeInstruktur += $item->fee;
            }

            array_push($listCard, Card::make('Fee Instruktur', 'Rp.' . number_format($feeInstruktur, 0, ',', '.'))->description('Pembayaran instruktur per order paket bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickFeeInstruktur")'
            ]));

            // Jumlah customer bulan ini
            $jumlahCustomer = User::whereHas('roles', function ($query) {
                $query->where('name', 'Customer');
            })->when($this->id_location, function ($query) {
                $query->where('id_location', $this->id_location);
            })->whereMonth('created_at', now())->count();

            array_push($listCard, Card::make('Customer Bulan ini', number_format($jumlahCustomer, 0, ',', '.'))->description('Jumlah customer tambahan bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickCustomer")'
            ]));

            // Profit bulan ini
            $profit = $omsetDalamSatuBulan - $biayaInstruktur;

            array_push($listCard, Card::make('Profit Bulan ini', 'Rp.' . number_format($profit, 0, ',', '.'))->description('Total profit dalam 1 bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickProfit")'
            ]));

            return $listCard;
        }
    }

    public function onClickOmset()
    {
        return redirect()->to(OmsetResource::getUrl('index'));
    }

    public function onClickFeeInstruktur()
    {
        return redirect()->to(InstrukturOmsetResource::getUrl('index'));
    }

    public function onClickCustomer()
    {
        return redirect()->to(CustomerResource::getUrl('index'));
    }

    public function onClickProfit()
    {
        return redirect()->to(JurnalResource::getUrl('index'));
    }

    public function onClickBiayaInstruktur()
    {
        return redirect()->to(BiayaInstruktruResource::getUrl('index'));
    }
}
