<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LocationStudio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormProfile extends Component
{
    use WithFileUploads;
    public $listeners = [
        'changeLocation',
        'changeLocationStudio',
        'updateProfile'
    ];

    public $name;
    public $email;
    public $no_hp;
    public $password;
    public $id_location;
    public $id_studio;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $negara;
    public $alamat;
    public $ktp;
    public $foto;
    public $foto_ktp;
    public $selfie;
    public $foto_selfie;

    public $listLocation = [];
    public $listLocationStudio = [];
    public function render()
    {
        if ($this->ktp) {
            $this->foto_ktp = $this->ktp->temporaryUrl();
        }

        if ($this->selfie) {
            $this->foto_selfie = $this->selfie->temporaryUrl();
        }

        $this->dispatchBrowserEvent('contentChange');
        return view('livewire.form-profile');
    }

    public function mount()
    {
        $this->listLocation = Location::get();
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp;
        $this->id_location = $user->id_location;
        $this->tanggal_lahir = $user->tanggal_lahir;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->negara = $user->negara;
        $this->alamat = $user->alamat;
        $this->foto_ktp = asset('storage/' . $user->ktp);
        $this->foto_selfie = asset('storage/' . $user->selfie);

        if ($user->studio) {
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

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'tanggal_lahir' => 'nullable|string',
            'negara' => 'nullable|string',
            'no_hp' => ['required', 'regex:/^(\+62|0)(\d{8,12})$/'],
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|max:20',
            'id_location' => 'required|numeric',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selfie' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($this->foto) {
            $foto = $this->foto->store('public/foto');
            $data['foto'] = str_replace('public/', '', $foto);
        }

        if ($this->ktp) {
            $ktp = $this->ktp->store('public/ktp');
            $data['ktp'] = str_replace('public/', '', $ktp);
        }

        if ($this->selfie) {
            $selfie = $this->selfie->store('public/selfie');
            $data['selfie'] = str_replace('public/', '', $selfie);
        }

        if($this->password){
            $data['password'] = Hash::make($this->password);
        }

        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['alamat'] = $this->alamat;
        $data['jenis_kelamin'] = $this->jenis_kelamin;
        $data['no_hp'] = $this->no_hp;
        $data['negara'] = $this->negara;
        $data['tanggal_lahir'] = $this->tanggal_lahir;
        $data['id_location'] = $this->id_location;

        $id_user = Auth::id();
        $user = User::find($id_user);
        $user->update($data);

        $location = Location::find($this->id_location);
        if ($location) {
            session()->put('phone_number', $location->no_hp);
            session()->put('id_location', $location->id);
            session()->put('isLocationChecked', true);
        }
        activity()->causedBy(Auth::user())
            ->log('Melakukan update profile');

        return redirect()->route('profile')->with('success', 'Profile berhasil di update');
    }
}
