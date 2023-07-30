<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class PrivateClass extends Component
{
    public $listKelas;
    public function render()
    {
        $this->listKelas = Kelas::where('category', 1)
        ->get();
        return view('livewire.class.private-class');
    }
}
