<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LocationStudio;
use Livewire\Component;

class FormRegistrasi extends Component
{
    public $listeners = [
        'changeLocation',
        'changeLocationStudio'
    ];

    public $nama;
    public $email;
    public $no_hp;
    public $password;
    public $id_location;
    public $alamat;

    public $listLocation = [];
    public $listLocationStudio = [];
    public function render()
    {
        $this->dispatchBrowserEvent('contentChange');
        return view('livewire.form-registrasi');
    }

    public function mount()
    {
        $this->listLocation = Location::get();
    }

    public function changeLocation($id_location)
    {
        $this->id_location = $id_location;
    }
}
