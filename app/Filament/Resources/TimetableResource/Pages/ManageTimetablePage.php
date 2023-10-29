<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use App\Models\KelasActivity;
use App\Models\Location;
use App\Models\LocationStudio;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ManageTimetablePage extends Page
{
    protected static string $resource = TimetableResource::class;

    protected static string $view = 'filament.resources.timetable-resource.pages.manage-timetable-page';

    public $startDate;
    public $endDate;
    public $listTanggal = [];
    public $listStudio = [];
    public $listKelasActivity = [];
    public $listJam = [];
    public $listLocation = [];
    public $id_location;
    public $pesertaTerbanyak = 0;

    public function render(): View
    {
        $this->getData();
        return view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Jadwal')
                ->icon('heroicon-o-plus-circle')
                ->url(function () {
                    return TimetableResource::getUrl('create');
                }),
            Action::make('clone_activity')
                ->label('Clone Data Minggu ini')
                ->icon('heroicon-o-duplicate')
                ->requiresConfirmation()
                ->action(function () {
                    $periode = CarbonPeriod::create($this->startDate, $this->endDate);
                    $starDateNow = Carbon::parse($this->startDate)->addDays(7)->locale('id')->format('Y-m-d H:i:s');
                    $endDateNow = Carbon::parse($this->endDate)->locale('id')->addDays(7)->format('Y-m-d H:i:s');
                    $periodeNow = CarbonPeriod::create($starDateNow, $endDateNow)->toArray();
                    foreach ($periode as $index => $item) {
                        $listKelasActivity = KelasActivity::whereDate('tanggal', $item->format('Y-m-d'))
                            ->where('id_kelas', '!=', null)
                            ->where('id_location_studio', '!=', null)
                            ->where('id_user', '!=', null)
                            ->get();
                        // $data = [];
                        foreach ($listKelasActivity as $kelasActivity) {
                            KelasActivity::updateOrCreate([
                                'tanggal' => $periodeNow[$index]->format('Y-m-d'),
                                'id_user' => $kelasActivity->id_user,
                                'jam_mulai' => $kelasActivity->jam_mulai,
                                'id_kelas' => $kelasActivity->id_kelas,
                                'id_location_studio' => $kelasActivity->id_location_studio
                            ], [
                                'id_kelas' => $kelasActivity->id_kelas,
                                'nama_latihan' => $kelasActivity->nama_latihan,
                                'id_location_studio' => $kelasActivity->id_location_studio,
                                'jam_mulai' => $kelasActivity->jam_mulai,
                                'id_user' => $kelasActivity->id_user,
                                'status' => 1,
                                'level' => $kelasActivity->level,
                                'tanggal' => $periodeNow[$index]->format('Y-m-d'),
                                'user_update' => Auth::id(),
                            ]);
                        }
                    }
                })
        ];
    }

    public function mount()
    {
        $this->startDate = date('Y-m-d');
        $this->endDate = Carbon::now()->addDays(7)->isoFormat('YYYY-MM-DD');
        $this->listLocation = Location::get();
        if(Auth::user()->hasRole('Admin')){
            $this->id_location = Auth::user()->id_location;
        }
        $this->getData();
    }

    public function getData()
    {
        $this->listTanggal = KelasActivity::select('tanggal')
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->where('id_kelas', '!=', null)
            ->whereHas('locationStudio', function ($query) {
                $query->where('id_location', $this->id_location);
            })
            ->orderBy('tanggal', 'ASC')
            ->groupBy('tanggal')
            ->get();
        $this->listStudio = LocationStudio::where('id_location', $this->id_location)
            ->whereHas('kelasActivity', function ($query) {
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
            ->orderBy('jam_mulai', 'ASC')
            ->groupBy('jam_mulai')
            ->get();

        $this->pesertaTerbanyak = 0;
        foreach ($this->listJam as $item) {
            $listKelasActivity = KelasActivity::whereDate('tanggal', '>=', $this->startDate)
                ->whereDate('tanggal', '<=', $this->endDate)
                ->whereTime('jam_mulai', $item->jam_mulai)
                ->where('id_kelas', '!=', null)
                ->whereHas('locationStudio', function ($query) {
                    $query->where('id_location', $this->id_location);
                })
                ->get();
            foreach ($listKelasActivity as $kelasActivity) {
                if (count($kelasActivity->bookingKelas) >= $this->pesertaTerbanyak) {
                    $this->pesertaTerbanyak = count($kelasActivity->bookingKelas);
                }
            }
        }
    }
}
