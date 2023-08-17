<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\InstrukturResource;
use App\Http\Livewire\Base\Instruktur;
use App\Models\Jurnal;
use App\Models\KelasActivity;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class TotalJamInstruktur extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        $jurnal = Jurnal::where('id_order_paket', '!=', null)
            ->get();

        $omset = 0;
        $pengeluaran = 0;
        foreach ($jurnal as $item) {
            if ($item->tipe == 1) {
                $omset += $item->jumlah_uang;
            } elseif ($item->tipe == '0') {
                $pengeluaran += $item->jumlah_uang;
            }
        }

        $jumlahCustomer = User::whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->whereMonth('created_at', now())->count();

        $listInstruktur = User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->get();

        $listCard = [];

        if (Auth::user()->hasRole('Super Admin')) {
            array_push($listCard, Card::make('Gross Profit', 'Rp.' . number_format($omset - $pengeluaran, 0, ',', '.'))
                ->description('Omset di kurangi dengan fee instruktur')
                ->color('primary'));

            array_push($listCard,  Card::make('Jumlah Customer', $jumlahCustomer)
                ->description('Jumlah Customer Dalam Bulan Ini')
                ->color('primary'));
        }

        foreach ($listInstruktur as $item) {
            $jumlahJadwal = $item->kelasActivity->count();
            $jumlahAssing = KelasActivity::where('id_user', $item->id)
                ->where('id_location_studio', '!=', null)
                ->where('id_kelas', '!=', null)
                ->where('tanggal', '!=', null)
                ->where('jam_mulai', '!=', null)->count();

            array_push($listCard, Card::make($item->name, $jumlahAssing . '/' . $jumlahJadwal)
                ->url(InstrukturResource::getUrl('view', ['record' => $item]))
                ->description('Jumlah assing / Jumlah jadwal instruktur')
                ->color('primary'));
        }

        return $listCard;
    }
}
