<?php

namespace App\Http\Livewire;

use App\Models\Location;
use Livewire\Component;

class Footer extends Component
{
    public $listeners = [
        'refreshFooter' => '$refresh'
    ];
    public $location;
    public function render()
    {
        $this->location = Location::find(session()->get('id_location'));
        return view('livewire.footer');
    }

    public function mount(){
    }
}
