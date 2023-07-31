<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class LeftBar extends Component
{
    public $active;
    public function render()
    {
        return view('livewire.profile.left-bar');
    }

    public function mount($active){
        $this->active = $active;
    }
}
