<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LocationStudio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormProfile extends Component
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
    public $id_studio;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $negara;
    public $alamat;

    public $listLocation = [];
    public $listLocationStudio = [];
    public function render()
    {
        $this->dispatchBrowserEvent('contentChange');
        return view('livewire.form-profile');
    }

    public function mount()
    {
        $this->listLocation = Location::get();
        $user = Auth::user();
        $this->nama = $user->name;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp;
        $this->id_studio = $user->id_studio;
        $this->tanggal_lahir = $user->tanggal_lahir;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->negara = $user->negara;
        $this->alamat = $user->alamat;

        if($user->studio){
            $this->id_location = $user->studio->id_location;
            $this->id_studio = $user->id_studio;
            $this->listLocationStudio = LocationStudio::where('id_location', $this->id_location)->get();
        }
    }

    public function changeLocation($id_location)
    {
        $this->id_location = $id_location;
        $this->listLocationStudio = LocationStudio::where('id_location', $this->id_location)->get();
    }

    public function changeLocationStudio($id_studio)
    {
        $this->id_studio = $id_studio;
    }
}
