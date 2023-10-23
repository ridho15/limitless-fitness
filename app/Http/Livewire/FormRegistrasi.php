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
    public $id_location_studio;
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
        $this->listLocationStudio = LocationStudio::where('id_location', $this->id_location)->get();
    }

    public function changeLocationStudio($id_location_studio)
    {
        $this->id_location_studio = $id_location_studio;
    }
}
