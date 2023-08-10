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
        'changeTanggal'
    ];
    public $listData;
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
        $this->listData = [];
        if ($this->id_kelas) {
            foreach ($this->listTanggal as $item) {
                $kelasActivity = KelasActivity::with('kelas')
                    ->whereHas('kelas', function ($query) {
                        $query->where('id_kelas', $this->id_kelas)->whereHas('paket', function ($query) {
                            $query->whereHas('orderPaket', function ($query) {
                                $query->where('id_user', Auth::id());
                            });
                        });
                    })
                    ->whereHas('kelas', function ($query) {
                        $query->where('nama_kelas', 'LIKE', '%' . $this->cari . '%');
                    })
                    ->where(function ($query) {
                        if ($this->category != 0 || $this->level != 0) {
                            $query->where('category', $this->category)
                                ->orWhere('level', $this->level);
                        }
                    })
                    ->where(function ($query) {
                        if ($this->id_kelas != 0) {
                            $query->where('id_kelas', $this->id_kelas);
                        }
                    })->where(function ($query) {
                        if ($this->id_user != 0) {
                            $query->where('id_user', $this->id_user);
                        }
                    })
                    ->whereDate('tanggal', $item)
                    ->orderBy('jam_mulai', 'ASC')
                    ->get();

                array_push($this->listData, [
                    'tanggal' => $item,
                    'data' => $kelasActivity
                ]);
            }
        } else {
            $this->listTanggal = [];
        }
        return view('livewire.timetable.list-data');
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
}
