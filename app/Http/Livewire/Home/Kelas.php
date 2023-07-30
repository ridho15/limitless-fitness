<?php

namespace App\Http\Livewire\Home;

use App\Models\Kelas as ModelsKelas;
use Livewire\Component;

class Kelas extends Component
{
    public $listKelas;
    public function render()
    {
        $this->listKelas = ModelsKelas::with(['kelasImage', 'kelasRating'])
        ->select(['nama_kelas', 'keterangan', 'id'])
        ->take(3)->get();
        return view('livewire.home.kelas');
    }
}
