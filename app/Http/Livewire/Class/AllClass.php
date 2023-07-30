<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class AllClass extends Component
{
    public $listKelas;
    public function render()
    {
        $this->listKelas = Kelas::get();
        return view('livewire.class.all-class');
    }
}
