<?php

namespace App\Http\Livewire\Base;

use App\Models\User;
use Livewire\Component;

class Instruktur extends Component
{
    public $listInstruktur = [];
    public function render()
    {
        $this->listInstruktur = User::whereHas('roles', function($query){
            $query->where('name', 'Instruktur');
        })->get();
        return view('livewire.base.instruktur');
    }
}
