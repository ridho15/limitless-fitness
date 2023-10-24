<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FloatingWhatsApp extends Component
{
    public $listeners = [
        'refreshFloatingWhatsApp' => '$refresh'
    ];
    public function render()
    {
        return view('livewire.floating-whats-app');
    }

    public function mount(){

    }
}
