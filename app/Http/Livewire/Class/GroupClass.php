<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class GroupClass extends Component
{
    public $listKelas;
    public function render()
    {
        $this->listKelas = Kelas::where('category', 2)
        ->get();
        return view('livewire.class.group-class');
    }
}
