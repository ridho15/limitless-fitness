<?php

namespace App\Http\Livewire;

use App\Models\Location;
use Livewire\Component;

class Caraousel extends Component
{
    public $listeners = [
        'refreshCaraousel' => '$refresh'
    ];

    public $location;

    public function render()
    {
        if(session()->get('id_location')){
            $this->location = Location::find(session()->get('id_location'));
        }
        return view('livewire.caraousel');
    }

    public function mount(){

    }
}
