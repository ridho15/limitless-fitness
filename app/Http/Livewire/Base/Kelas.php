<?php

namespace App\Http\Livewire\Base;

use App\Models\Kelas as ModelsKelas;
use Livewire\Component;

class Kelas extends Component
{
    public $listKelas = [];
    public function render()
    {
        $this->listKelas = ModelsKelas::where('is_active', 1)
        ->get();
        return view('livewire.base.kelas');
    }
}
