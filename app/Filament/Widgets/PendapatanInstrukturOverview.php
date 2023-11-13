<?php

namespace App\Filament\Widgets;

use App\Models\KelasActivity;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class PendapatanInstrukturOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $listCard = [];
        $kelasActivity = KelasActivity::where('id_user', Auth::id())
            ->whereHas('bookingKelas')
            ->withCount('bookingKelas')
            // ->whereDate('tanggal', '>=', $startDate)
            // ->whereDate('tanggal', '<=', $endDate)
            ->having('booking_kelas_count', '>=', 2)
            ->get();

        $totalOmset = 0;
        $omsetSatuBulan = 0;
        foreach ($kelasActivity as $item) {
            if(date("Y-m-d", strtotime($item->tanggal)) >= date("Y-m-d", strtotime($startDate)) && date("Y-m-d", strtotime($item->tanggal)) <= date("Y-m-d", strtotime($endDate))){
                $omsetSatuBulan += $item->kelas->fee;
            }
            $totalOmset += $item->kelas->fee;
        }

        array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format($omsetSatuBulan, 0, ',', '.'))
            ->description('Omset dalam bulan ini')->color('primary'));

        array_push($listCard, Card::make('Total Omset', 'Rp.' . number_format($totalOmset, 0, ',', '.'))
            ->description('Omset dalam bulan ini')->color('primary'));

        return $listCard;
    }
}
