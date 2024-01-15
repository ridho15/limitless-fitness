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
        'changeClass',
        'changeTanggal2'
    ];
    protected $listTanggal = [];
    public $indexTanggal;
    public $listKelas = [];
    public $listLocation = [];
    public $listInstruktur = [];
    public $cari;
    public $tanggal;
    public $nama_kelas = "Class";
    public function render()
    {
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

        $this->updateTanggal();
    }

    public function updateTanggal(){
        $startDate = Carbon::parse($this->tanggal)->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::parse($this->tanggal)->addDays(7)->isoFormat('DD MMMM YYYY');
        $this->listTanggal = CarbonPeriod::create($startDate, $endDate);
    }

    public function setTanggal($index)
    {
        $startDate = Carbon::parse($this->tanggal)->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::parse($this->tanggal)->addDays(7)->isoFormat('DD MMMM YYYY');
        $listTanggal = CarbonPeriod::create($startDate, $endDate)->toArray();
        $this->emit('changeTanggal', $listTanggal[$index]);
        $this->indexTanggal = $index;

        $this->updateTanggal();
    }

    public function changeTanggal2(){
        $startDate = Carbon::parse($this->tanggal)->isoFormat('DD MMMM YYYY');
        $endDate = Carbon::parse($this->tanggal)->addDays(7)->isoFormat('DD MMMM YYYY');
        $listTanggal = CarbonPeriod::create($startDate, $endDate)->toArray();
        $this->emit('changeTanggal', $this->tanggal);
        $this->indexTanggal = array_search($this->tanggal, $listTanggal);
        $this->updateTanggal();
    }

    public function onClickCari()
    {
        $this->emit('setCari', $this->cari);
        $this->updateTanggal();
    }

    public function changeClass($id_kelas)
    {
        $kelas = Kelas::find($id_kelas);
        if (!$kelas) {
            $this->nama_kelas = 'Class';
        } else {
            $this->nama_kelas = $kelas->nama_kelas;
        }

        $this->updateTanggal();
    }
}
