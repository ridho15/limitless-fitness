<?php

namespace App\Http\Livewire\Timetable;

use App\Models\Kelas;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class Tanggal extends Component
{
    public $listeners = [
        'setTanggal'
    ];
    private $listTanggal;
    public $indexTanggal;
    public $listKelas = [];
    public $listLocation = [];
    public $listInstruktur = [];
    public function render()
    {
        $startDate = Carbon::now()->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::now()->addDays(30)->isoFormat('DD MMMM YYYY');
        $this->listTanggal = CarbonPeriod::create($startDate, $endDate);
        $data['listTanggal'] = $this->listTanggal;
        return view('livewire.timetable.tanggal', $data);
    }

    public function  mount()
    {
        $this->listLocation = Location::get();
        $this->listKelas = Kelas::get();
        $this->listInstruktur = User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->get();
    }

    public function setTanggal($index)
    {
        $this->indexTanggal = $index;
    }
}
