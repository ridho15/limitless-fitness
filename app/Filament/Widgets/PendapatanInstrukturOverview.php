<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\Location;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class  PendapatanInstrukturOverview extends BaseWidget
{
    protected static string $view = 'custom-widget.filter-widget';

    protected static ?string $pollingInterval = null;

    public $tanggal_awal;
    public $tanggal_akhir;
    public $id_location;

    public $listLocation = [];

    public function render(): View
    {
        return view(static::$view, $this->getViewData());
    }

    public function mount()
    {
        $this->tanggal_awal = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_akhir = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->listLocation = Location::get();
    }

    protected function getCards(): array
    {
        $listCard = [];
        $listKelas = Kelas::get();
        $totalPendapatan = 0;
        $totalPendapatanSatuBulan = 0;
        $jumlahPertemuan = 0;
        $jumlahPertemuanSatuBulan = 0;

        foreach ($listKelas as $item) {
            $jumlahKelasAktifSatuBulan = KelasActivity::where('id_kelas', $item->id)
                ->when($this->id_location, function ($query) {
                    $query->whereHas('locationStudio', function ($query) {
                        $query->where('id_location', $this->id_location);
                    });
                })
                ->when(Auth::user()->hasRole('Instruktur'), function ($query) {
                    $query->where('id_user', Auth::id());
                })->withCount(['bookingKelas' => function ($query) {
                    $query->where('status_kehadiran', 1)
                        ->orWhereNull('status_kehadiran');
                }])->having('booking_kelas_count', '>=', $item->min_anggota)->whereDate('tanggal', '<=', $this->tanggal_akhir)
                ->whereDate('tanggal', '>=', $this->tanggal_awal)
                ->count();

            $totalPendapatanSatuBulan += $item->fee * $jumlahKelasAktifSatuBulan;

            $jumlahKelasAktif = KelasActivity::where('id_kelas', $item->id)
                ->when($this->id_location, function ($query) {
                    $query->whereHas('locationStudio', function ($query) {
                        $query->where('id_location', $this->id_location);
                    });
                })
                ->when(Auth::user()->hasRole('Instruktur'), function ($query) {
                    $query->where('id_user', Auth::id());
                })->withCount(['bookingKelas' => function ($query) {
                    $query->where('status_kehadiran', 1)
                        ->orWhereNull('status_kehadiran');
                }])->having('booking_kelas_count', '>=', $item->min_anggota)->whereDate('tanggal', '<=', now())
                ->count();

            $totalPendapatan += $item->fee * $jumlahKelasAktif;
            $jumlahPertemuanSatuBulan += $jumlahKelasAktifSatuBulan;
            $jumlahPertemuan += $jumlahKelasAktif;
        }

        array_push($listCard, Card::make('Jumlah Pertemuan 1 Bulan', $jumlahPertemuanSatuBulan)
            ->description('Jumlah Pertamuan dalam bulan ini')->color('primary'));

        array_push($listCard, Card::make('Jumlah Pertemuan', $jumlahPertemuan)
            ->description('Jumlah pertemuan seluruh nya')->color('primary'));

        array_push($listCard, Card::make('Omset 1 Bulan', 'Rp.' . number_format($totalPendapatanSatuBulan, 0, ',', '.'))
            ->description('Penghasilan dalam bulan ini')->color('primary'));

        array_push($listCard, Card::make('Total Penghasilan', 'Rp.' . number_format($totalPendapatan, 0, ',', '.'))
            ->description('Penghasilan keseluruhan')->color('primary'));

        return $listCard;
    }

    public function setFromDate($tanggal)
    {
        $this->tanggal_awal = $tanggal;
    }

    public function setToDate($tanggal)
    {
        $this->tanggal_akhir = $tanggal;
    }
}
