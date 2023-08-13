<?php

namespace App\Filament\Widgets;

use App\Models\Jurnal;
use App\Models\KelasActivity;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TotalJamInstruktur extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        $totalJamInstrukturAssingTimetable = KelasActivity::where('id_kelas', '!=', null)
            ->where('id_location_studio', '!=', null)
            ->where('jam_mulai', '!=', null)
            ->where('tanggal', '!=', null)
            ->count();

        $totalInstrukturJadwal = KelasActivity::where('id_kelas', null)
            ->where('id_location_studio', null)
            ->count();

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

        return [
            Card::make('Gross Profit', 'Rp.' . number_format($omset - $pengeluaran, 0, ',', '.'))
                ->description('Omeset di kurangi dengan fee instruktur')
                ->color('primary'),
            Card::make('Jumlah Customer', $jumlahCustomer)
                ->description('Jumlah Customer Dalam Bulan Ini')
                ->color('primary'),
            Card::make('Total Jam', $totalJamInstrukturAssingTimetable)
                ->color('primary')
                ->description('Total instructor hours'),
            Card::make('Total Jadwal', $totalInstrukturJadwal)
                ->description('number of hours available now')
                ->color('primary'),
        ];
    }
}
