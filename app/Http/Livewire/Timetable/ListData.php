<?php

namespace App\Http\Livewire\Timetable;

use App\Models\KelasActivity;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListData extends Component
{
    public $listeners = [
        'setListTanggal',
        'setTanggal',
        'changeCategory',
        'changeLocation',
        'changeClass',
        'changeInstruktur',
        'changeLevel',
        'setCari',
        'changeTanggal',
        'changeTanggalData'
    ];
    public $indexTanggal;
    public $category;
    public $id_location;
    public $id_kelas;
    public $id_user;
    public $level;
    public $cari;
    public $tanggal;
    private $listTanggal = [];

    public function render()
    {
        $startDate = Carbon::parse($this->tanggal)->isoFormat('YYYY-MM-DD');
        $endDate = Carbon::parse($this->tanggal)->addDays(7)->isoFormat('YYYY-MM-DD');
        $this->listTanggal = CarbonPeriod::create($startDate, $endDate);
        $listData = [];
        if ($this->id_kelas) {
            if ($this->tanggal == null) {
                foreach ($this->listTanggal as $item) {
                    $kelasActivity = KelasActivity::with(['locationStudio.location', 'kelas.paket.orderPaket'])
                        ->whereHas('kelas')
                        ->where('id_kelas', $this->id_kelas)
                        ->where(function ($query) {
                            if ($this->id_user) {
                                $query->where('id_user', $this->id_user);
                            }
                        })
                        ->whereDate('tanggal', $item)
                        ->orderBy('jam_mulai', 'ASC')
                        ->get();

                    array_push($listData, [
                        'tanggal' => $item,
                        'data' => $kelasActivity
                    ]);
                }
            } else {
                $kelasActivity = KelasActivity::with(['locationStudio.location', 'kelas.paket.orderPaket'])
                    ->whereHas('kelas')
                    ->where('id_kelas', $this->id_kelas)
                    ->where(function ($query) {
                        if ($this->id_user) {
                            $query->where('id_user', $this->id_user);
                        }
                    })
                    ->whereDate('tanggal', $this->tanggal)
                    ->orderBy('jam_mulai', 'ASC')
                    ->get();

                array_push($listData, [
                    'tanggal' => $this->tanggal,
                    'data' => $kelasActivity
                ]);
            }
        } else {
            $this->listTanggal = [];
        }
        return view('livewire.timetable.list-data', [
            'listData' => $listData
        ]);
    }

    public function mount()
    {
        $this->category = 0;
        $this->tanggal = now();
    }

    public function setListTanggal($listTanggal)
    {
        $this->listTanggal = $listTanggal;
    }

    public function setTanggal($indexTanggal)
    {
        $this->indexTanggal = $indexTanggal;
    }

    public function changeCategory($category)
    {
        $this->category = $category;
    }

    public function changeLocation($id_location)
    {
        $this->id_location = $id_location;
    }

    public function changeClass($id_kelas)
    {
        $this->id_kelas = $id_kelas;
    }

    public function changeInstruktur($id_user)
    {
        $this->id_user = $id_user;
    }

    public function changeLevel($level)
    {
        $this->level = $level;
    }

    public function setCari($cari)
    {
        $this->cari = $cari;
    }

    public function changeTanggal($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function changeTanggalData($index){
        $startDate = Carbon::parse($this->tanggal)->isoFormat('YYYY-MM-DD');
        $endDate = Carbon::parse($this->tanggal)->addDays(8)->isoFormat('YYYY-MM-DD');
        $listTanggal = CarbonPeriod::create($startDate, $endDate)->toArray();
        $this->tanggal = $listTanggal[$index + 1];
    }
}
