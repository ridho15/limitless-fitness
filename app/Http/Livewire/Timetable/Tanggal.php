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
        'setTanggal',
        'onClickCari',
        'changetanggal'
    ];
    protected $listTanggal;
    public $indexTanggal;
    public $listKelas = [];
    public $listLocation = [];
    public $listInstruktur = [];
    public $cari;
    public $tanggal;
    public function render()
    {
        $startDate = Carbon::parse($this->tanggal)->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::parse($this->tanggal)->addDays(7)->isoFormat('DD MMMM YYYY');
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
        $this->tanggal = now();
    }

    public function setTanggal($index)
    {
        $startDate = Carbon::parse($this->tanggal)->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::parse($this->tanggal)->addDays(8)->isoFormat('DD MMMM YYYY');
        $listTanggal = CarbonPeriod::create($startDate, $endDate)->toArray();
        $this->indexTanggal = $index;
        $this->emit('changeTanggalData', $listTanggal[$index + 1]);
    }

    public function onClickCari()
    {
        $this->emit('setCari', $this->cari);
    }

    public function changeTanggal()
    {
        $this->emit('changeTanggalData', $this->tanggal);
    }
}
