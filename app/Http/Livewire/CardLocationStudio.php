<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LocationStudio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CardLocationStudio extends Component
{
    public $listeners = [
        'changeLocation',
        'changeLocationStudio',
        'updateStudioUser'
    ];
    public $listLocation = [];
    public $listLocationStudio = [];
    public $id_location;
    public $id_studio;
    public function render()
    {
        $this->dispatchBrowserEvent('contentChange');
        return view('livewire.card-location-studio');
    }

    public function mount()
    {
        $this->listLocation = Location::get();
        if (Auth::user() && Auth::user()->id_studio != null) {
            $this->id_studio = Auth::user()->id_studio;
            $this->id_location = Auth::user()->studio->id_location;
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

    public function updateStudioUser()
    {
        $user = User::find(Auth::id());
        if ($user) {
            $user->update([
                'id_location' => $this->id_location
            ]);
        }

        $location = Location::find($this->id_location);
        session()->put('phone_number', $location->no_hp);
        session()->put('isStudioChecked', true);

        $this->emit('refreshListData');
        $this->emit('refreshFloatingWhatsApp');
        $this->emit('finishUpdate');
    }
}
