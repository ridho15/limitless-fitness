<?php

namespace App\Filament\Widgets;

use App\Models\KelasActivity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class InstrukturWidgetOverview extends BaseWidget
{
    public $record;
    public function __construct($record)
    {
        $this->record = $record;
    }
    protected function getCards(): array
    {
        $listCard = [];
        $listKelasActivity = KelasActivity::with(['bookingKelas' => function ($query) {
            $query->where('status_kehadiran', 1)
                ->orWhereNull('status_kehadiran');
        }])
            ->withCount('bookingKelas')
            ->having('booking_kelas_count', '>=', 2)
            ->where('id_user', $this->record->id)
            ->whereDate('tanggal', '<=', now())
            ->get();

        $jumlahPertemuan = $listKelasActivity->count();
        $jumlahPendapatan = 0;
        foreach ($listKelasActivity as $item) {
            $jumlahPendapatan += $item->kelas->fee;
        }

        if ($jumlahPertemuan == 0 || $jumlahPendapatan == 0) {
            $rataRataPendapatanPertemuan = 0;
        } else {
            $rataRataPendapatanPertemuan = $jumlahPendapatan / $jumlahPertemuan;
        }
        array_push($listCard, Card::make('Jumlah Pertemuan', $jumlahPertemuan)
            ->description('Jumlah Pertamuan / Jumlah Jam Mengajar')->color('primary'));
        array_push($listCard, Card::make('Pendapatan Per Pertemuan', 'Rp.' . number_format($rataRataPendapatanPertemuan, 0, ',', '.'))
            ->description('Jumlah Pendapatan Per Pertemuan')->color('primary'));
        array_push($listCard, Card::make('Jumlah Pendapatan', 'Rp.' . number_format($jumlahPendapatan, 0, ',', '.'))
            ->description('Jumlah Pendapatan')->color('primary'));

        return $listCard;
    }
}
