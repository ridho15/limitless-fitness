<?php

namespace App\Http\Livewire;

use App\Mail\RegistrasiKonfirmasiMail;
use App\Models\Location;
use App\Models\LocationStudio;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use illuminate\Support\Str;

class FormRegistrasi extends Component
{
    use WithFileUploads;
    public $listeners = [
        'changeLocation',
        'changeLocationStudio',
        'postRegistration'
    ];

    public $name;
    public $email;
    public $no_hp;
    public $password;
    public $id_location;
    public $alamat;
    public $ktp = null;
    public $selfie = null;
    public $setuju = false;
    public $jenis_kelamin;
    public $nik;

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

    public function postRegistration()
    {
        $this->validate([
            'email' => 'required|email',
            'no_hp' => ['required', 'regex:/^(\+62|0)(\d{8,12})$/', 'digits_between:10,12'], // +62 followed by 9 to 15 digits
            'name' => 'required|string|max:225',
            'password' => 'required|min:8|max:20',
            'alamat' => 'required',
            'id_location' => 'required|numeric',
            'ktp' => 'required|mimes:jpeg,png,jpg,gif|max:1024',
            'selfie' => 'required|mimes:jpeg,png,jpg,gif|max:1024',
            'setuju' => 'required',
            'jenis_kelamin' => 'required',
            'nik' => 'required|numeric|digits:16'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid !',
            'name.required' => 'Nama tidak boleh kosong',
            'name.string' => 'Nama tidak valid !',
            'name.max' => 'Nama terlalu panjang',
            'no_hp.required' => 'Nomor Hp tidak boleh kosong',
            'no_hp.regex' => 'Nomor Hp tidak valid !',
            'no_hp.digits_between' => 'Nomor hp tidak valid !',
            'password.required' => 'Password tidak boleh kosong !',
            'password.min' => 'Password terlalu pendek',
            'password.max' => 'Password terlalu panjang',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'id_location.required' => 'Lokasi belum di pilih',
            'id_location.numeric' => 'Lokasi tidak valid !',
            'ktp.required' => 'KTP wajib di upload',
            'ktp.mimes' => 'Foto KTP tidak valid !',
            'ktp.max' => 'Ukuran Foto KTP terlalu besar. silahkan di kecilkan',
            'selfie.required' => 'Foto selfie wajib diisi',
            'selfie.mimes' => "Foto KTP tidak valid !",
            'selfie.max' => 'Ukuran Foto Selfie terlalu besar',
            'setuju.required' => 'Anda belum menyetujui ketentuan',
            'jenis_kelamin.required' => 'Jenis kelamin belum dipilih',
            'nik.required' => 'NIK tidak boleh kosong',
            'NIK.numeric' => 'NIK tidak valid !'
        ]);

        $checkEmail = User::where('email', $this->email)->withTrashed()->first();
        if ($checkEmail) {
            return $this->emit('finishSubmit', false, "Email sudah digunakan !");
        }

        $faker = Factory::create();
        do {
            $user_id = $faker->ean8();
            $user = User::find($user_id);
        } while ($user);

        $ktp = $this->ktp->store('public/ktp');
        $selfie = $this->selfie->store('public/selfie');
        $data['id'] = $user_id;
        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['password'] = Hash::make($this->password);
        $data['alamat'] = $this->alamat;
        $data['status'] = 0;
        $data['no_hp'] = $this->no_hp;
        $data['id_location'] = $this->id_location;
        $data['ktp'] = str_replace('public/', '', $ktp);
        $data['selfie'] = str_replace('public/', '', $selfie);
        $data['jenis_kelamin'] = $this->jenis_kelamin;
        $data['nik'] = $this->nik;
        $data['created_at'] = now();

        User::insert($data);
        $user = User::find($user_id);
        $user->assignRole('Customer');

        // return $this->emit('finishSubmit', true, "Email berhasil di buat");
        Mail::to($this->email)->send(new RegistrasiKonfirmasiMail($user));
        set_time_limit(2);
        return redirect()->route('login')->with('success', 'Akun kamu belum aktif. Tunggu 1 x 24 jam untuk aktifasi akun atau bisa menghubungi admin melalui whatsapp yang sudah di sediakan');
    }
}
