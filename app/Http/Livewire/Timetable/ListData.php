<?php

namespace App\Http\Livewire\Timetable;

use App\Models\KelasActivity;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class ListData extends Component
{
    public $listeners = [
        'setListTanggal',
        'setTanggal'
    ];
    public $listData;
    public $indexTanggal;
    private $listTanggal = [];
    public function render()
    {
        $startDate = Carbon::now()->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::now()->addDays(30)->isoFormat('DD MMMM YYYY');
        $this->listTanggal = CarbonPeriod::create($startDate, $endDate);
        $this->listData = [];
        foreach ($this->listTanggal as $item) {
            $kelasActivity = KelasActivity::with('kelas')
            ->whereDate('jam_latihan', $item)
            ->orderBy('jam_latihan', 'ASC')
            ->get();
            array_push($this->listData, [
                'tanggal' => $item,
                'data' => $kelasActivity
            ]);
        }
        return view('livewire.timetable.list-data');
    }

    public function mount(){

    }

    public function setListTanggal($listTanggal){
        $this->listTanggal = $listTanggal;
    }

    public function setTanggal($indexTanggal){
        $this->indexTanggal = $indexTanggal;
    }
}
