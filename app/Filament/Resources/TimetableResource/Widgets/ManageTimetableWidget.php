<?php

namespace App\Filament\Resources\TimetableResource\Widgets;

use App\Models\KelasActivity;
use App\Models\LocationStudio;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class ManageTimetableWidget extends Widget
{
    // protected static string $view = 'filament.resources.timetable-resource.widgets.manage-timetable';
    // protected static string $view = 'custom-widget.manage-timetable';

    public $startDate;
    public $endDate;
    public $listTanggal = [];
    public $listStudio = [];
    public $listKelasActivity = [];
    public $listJam = [];
    public $pesertaTerbanyak = 0;
    public function render(): View
    {
        $this->listTanggal = KelasActivity::select('tanggal')
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->where('id_kelas', '!=', null)
            ->where('id_location_studio', '!=', null)
            ->groupBy('tanggal')
            ->get();
        $this->listStudio = LocationStudio::whereHas('kelasActivity', function ($query) {
            $query->whereDate('tanggal', '>=', $this->startDate)
                ->whereDate('tanggal', '<=', $this->endDate)
                ->where('id_kelas', '!=', null)
                ->where('id_location_studio', '!=', null);
        })->get();

        $this->listJam = KelasActivity::select('tanggal')
            ->select('jam_mulai')
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
                ->where('id_kelas', '!=', null)
                ->where('id_location_studio', '!=', null)
                ->get();
            foreach ($listKelasActivity as $kelasActivity) {
                if (count($kelasActivity->bookingKelas) >= $this->pesertaTerbanyak) {
                    $this->pesertaTerbanyak = count($kelasActivity->bookingKelas);
                }
            }
        }

        return view('custom-widget.manage-timetable');
    }

    public function mount()
    {
        $this->startDate = date('Y-m-d');
        $this->endDate = date('Y-m-d');
    }
}
