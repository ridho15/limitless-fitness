<?php

namespace App\Filament\Resources\InstrukturJadwalResource\Widgets;

use App\Models\KelasActivity;
use App\Models\LocationStudio;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ManageTimetableWidget extends Widget
{
    // protected static string $view = 'filament.resources.instruktur-jadwal-resource.widgets.manage-timetable';

    public $startDate;
    public $endDate;
    public $listTanggal = [];
    public $listStudio = [];
    public $listKelasActivity = [];
    public $listJam = [];
    public $pesertaTerbanyak = 0;
    public function render(): View
    {
        $this->getData();
        return view('custom-widget.manage-atur-jadwal');
    }

    public function mount()
    {
        // $this->startDate = '2023-08-11';
        // $this->endDate = '2023-08-11';
        $this->startDate = date('Y-m-d');
        $this->endDate = Carbon::now()->addDays(7)->isoFormat('YYYY-MM-DD');
    }

    public function getData()
    {
        $this->listTanggal = KelasActivity::select('tanggal')
            ->where(function ($query) {
                if (Auth::user()->hasRole('Instruktur')) {
                    $query->where('id_user', Auth::id());
                }
            })
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->where('id_kelas', '!=', null)
            ->where('id_location_studio', '!=', null)
            ->groupBy('tanggal')
            ->get();
        $this->listStudio = LocationStudio::whereHas('kelasActivity', function ($query) {
            $query->whereDate('tanggal', '>=', $this->startDate)
                ->where(function ($query) {
                    if (Auth::user()->hasRole('Instruktur')) {
                        $query->where('id_user', Auth::id());
                    }
                })
                ->whereDate('tanggal', '<=', $this->endDate)
                ->where('id_kelas', '!=', null)
                ->where('id_location_studio', '!=', null);
        })->get();

        $this->listJam = KelasActivity::select('tanggal')
            ->select('jam_mulai')
            ->where(function ($query) {
                if (Auth::user()->hasRole('Instruktur')) {
                    $query->where('id_user', Auth::id());
                }
            })
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->where('id_kelas', '!=', null)
            ->where('id_location_studio', '!=', null)
            ->groupBy('jam_mulai')
            ->get();
        $this->pesertaTerbanyak = 0;
        foreach ($this->listJam as $item) {
            $listKelasActivity = KelasActivity::whereDate('tanggal', '>=', $this->startDate)
                ->whereDate('tanggal', '<=', $this->endDate)
                ->whereTime('jam_mulai', $item->jam_mulai)
                ->where(function ($query) {
                    if (Auth::user()->hasRole('Instruktur')) {
                        dd(Auth::id());
                        $query->where('id_user', Auth::id());
                    }
                })
                ->where('id_kelas', '!=', null)
                ->where('id_location_studio', '!=', null)
                ->get();
            foreach ($listKelasActivity as $kelasActivity) {
                if (count($kelasActivity->bookingKelas) >= $this->pesertaTerbanyak) {
                    $this->pesertaTerbanyak = count($kelasActivity->bookingKelas);
                }
            }
        }
    }
}
