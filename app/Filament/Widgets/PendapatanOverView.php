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
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class PendapatanOverView extends BaseWidget
{
    public $readyToLoad = false;

    protected static ?string $pollingInterval = null;

    public $listeners = [
        'onClickOmset',
        'onClickFeeInstruktur',
        'onClickCustomer',
        'onClickProfit',
        'onClickBiayaInstruktur'
    ];

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
            $omsetDalamSatuBulan = Jurnal::whereMonth('created_at', now())
                ->where('tipe', 1)->sum('jumlah_uang');

            array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format($omsetDalamSatuBulan, 0, ',', '.'))
                ->description('Omset dalam bulan ini')->color('primary')
                ->extraAttributes([
                    'wire:click' => '$emit("onClickOmset")'
                ]));

            // Biaya Instruktur
            $biayaInstruktur = Jurnal::whereNotNull('id_parent')
                ->where('tipe', 0)
                ->whereMonth('created_at', now())
                ->sum('jumlah_uang');

            array_push($listCard, Card::make('Biaya Instruktur', 'Rp.' . number_format($biayaInstruktur, 0, ',', '.'))->description('Biaya instruktur per order paket bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickBiayaInstruktur")'
            ]));

            // Fee instruktur
            $listKelas = Kelas::select(['id', 'fee', 'min_anggota'])->get();
            $feeInstruktur = 0;
            foreach ($listKelas as $item) {
                $fee = $item->fee;
                $listKelasActivity = KelasActivity::where('id_kelas', $item->id)
                    ->where(function ($query) {
                        $query->whereMonth('tanggal', now())
                            ->whereDate('tanggal', '<=', now());
                    })
                    ->withCount(['bookingKelas' => function ($query) {
                        $query->where('status_kehadiran', 1)
                            ->orWhereNull('status_kehadiran');
                    }])->having('booking_kelas_count', '>=', $item->min_anggota)
                    ->count();
                $feeInstruktur += $fee * $listKelasActivity;
            }

            array_push($listCard, Card::make('Fee Instruktur', 'Rp.' . number_format($feeInstruktur, 0, ',', '.'))->description('Pembayaran instruktur per order paket bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickFeeInstruktur")'
            ]));

            // Jumlah customer bulan ini
            $jumlahCustomer = User::whereHas('roles', function ($query) {
                $query->where('name', 'Customer');
            })->whereMonth('created_at', now())->count();

            array_push($listCard, Card::make('Customer Bulan ini', number_format($jumlahCustomer, 0, ',', '.'))->description('Jumlah customer tambahan bulan ini')->color('primary')->extraAttributes([
                'wire:click' => '$emit("onClickCustomer")'
            ]));

            // Profit bulan ini
            $profit = $omsetDalamSatuBulan - ($biayaInstruktur + $feeInstruktur);

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
