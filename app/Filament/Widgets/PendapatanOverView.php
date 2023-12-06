<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\InstrukturOmsetResource;
use App\Filament\Resources\JurnalResource;
use App\Filament\Resources\OmsetResource;
use App\Models\Jurnal;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class PendapatanOverView extends BaseWidget
{
    public $listeners = [
        'onClickOmset',
        'onClickFeeInstruktur',
        'onClickCustomer',
        'onClickProfit'
    ];
    protected function getCards(): array
    {
        $listCard = [];

        $omsetDalamSatuBulan = Jurnal::whereMonth('created_at', now())
            ->where('tipe', 1)->get();
        $omset = 0;
        foreach ($omsetDalamSatuBulan as $item) {
            $omset += $item->jumlah_uang;
        }

        array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format($omset, 0, ',', '.'))
            ->description('Omset dalam bulan ini')->color('primary')
            ->extraAttributes([
                'wire:click' => '$emit("onClickOmset")'
            ]));
        $instrukturFee = 0;
        $listInstrukturFee = Jurnal::where('id_order_paket', '!=', null)
            ->where('tipe', 0)
            ->whereMonth('created_at', now())
            ->get();
        foreach ($listInstrukturFee as $item) {
            $instrukturFee += $item->jumlah_uang;
        }

        array_push($listCard, Card::make('Fee Instruktur', 'Rp.' . number_format($instrukturFee, 0, ',', '.'))->description('Pembayaran instruktur bulan ini')->color('primary')->extraAttributes([
            'wire:click' => '$emit("onClickFeeInstruktur")'
        ]));

        $jumlahCustomer = User::whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->whereMonth('created_at', now())->count();

        array_push($listCard, Card::make('Customer Bulan ini', number_format($jumlahCustomer, 0, ',', '.'))->description('Jumlah customer tambahan bulan ini')->color('primary')->extraAttributes([
            'wire:click' => '$emit("onClickCustomer")'
        ]));

        $profit = 0;
        $listJurnal = Jurnal::whereMonth('created_at', now())
            ->get();
        foreach ($listJurnal as $item) {
            if ($item->tipe == 1) {
                $profit += $item->jumlah_uang;
            } else {
                $profit -= $item->jumlah_uang;
            }
        }

        array_push($listCard, Card::make('Profit Bulan ini', number_format($profit, 0, ',', '.'))->description('Total profit dalam 1 bulan ini')->color('primary')->extraAttributes([
            'wire:click' => '$emit("onClickProfit")'
        ]));

        return $listCard;
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
}
