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
        $listCard = [];
        $listKelasActivity = KelasActivity::when(Auth::user()->hasRole(['Instruktur']), function ($query) {
            return $query->where('id_user', Auth::id());
        })
            ->withCount(['bookingKelas' => function ($query) {
                $query->where('status_kehadiran', 1)
                    ->orWhereNull('status_kehadiran');
            }])
            ->whereDate('tanggal', '<=', now())
            ->having('booking_kelas_count', '>=', 2)
            ->get();
        $totalOmset = 0;
        $omsetSatuBulan = 0;
        $jumlahPertemuanSatuBulan = 0;
        $jumlahPertemuan = 0;
        foreach ($listKelasActivity as $item) {
            if (date("Y-m-d", strtotime($item->tanggal)) >= date("Y-m-d", strtotime($startDate)) && date('Y-m-d', strtotime($item->tanggal)) < now()) {
                $omsetSatuBulan += $item->kelas->fee;
                $jumlahPertemuanSatuBulan++;
            }
            $totalOmset += $item->kelas->fee;
            $jumlahPertemuan++;
        }

        array_push($listCard, Card::make('Jumlah Pertemuan 1 Bulan', $jumlahPertemuanSatuBulan)
            ->description('Jumlah Pertamuan dalam bulan ini')->color('primary'));

        array_push($listCard, Card::make('Jumlah Pertemuan', $jumlahPertemuan)
            ->description('Jumlah pertemuan seluruh nya')->color('primary'));

        array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format($omsetSatuBulan, 0, ',', '.'))
            ->description('Omset dalam bulan ini')->color('primary'));

        array_push($listCard, Card::make('Total Omset', 'Rp.' . number_format($totalOmset, 0, ',', '.'))
            ->description('Omset keseluruhan')->color('primary'));

        return $listCard;
    }
}
