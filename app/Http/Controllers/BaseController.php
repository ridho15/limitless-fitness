<?php

namespace App\Http\Controllers;

use App\Models\BookingKelas;
use App\Models\BookingOrder;
use App\Models\CustomerKelas;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\KelasPaket;
use App\Models\Location;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Carbon\Carbon;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BaseController extends Controller
{
    use WithRateLimiting;
    public function index()
    {
        // $helper = new HelperController;
        // return $helper->updateData();
        $data['active'] = 'home';
        return view('base-2.index', $data);
    }

    public function class()
    {
        $data['active'] = 'class';
        return view('base-2.class', $data);
    }

    public function detailClass($id)
    {
        $data['active'] = 'class';
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return redirect()->back()->with('fail', 'Data kelas tidak ditemukan !');
        }

        $id_kelas = $kelas->id;
        $jumlahReview = 0;
        foreach ($kelas->kelasActivity as $kelasActivity) {
            $jumlahReview += $kelasActivity->kelasRating->count();
        }

        $data['kelas'] = $kelas;
        $data['jumlahReview'] = $jumlahReview;
        $data['listPaket'] = KelasPaket::where('id_kelas', $id)->get();

        $data['relatedClass'] = Kelas::where('id', '!=', $kelas->id)->take(3)->get();
        return view('base-2.detail-class', $data);
    }

    public function packages()
    {
        $data['active'] = 'packages';
        $allPaket = Paket::get();
        $listKelas = Kelas::get();
        $dataKelas = [];
        foreach ($listKelas as $item) {
            $listPaket = Paket::whereHas('kelasPaket', function ($query) use ($item) {
                $query->where('id_kelas', $item->id);
            })->get();

            array_push($dataKelas, [
                'kelas' => $item,
                'listPaket' => $listPaket
            ]);
        }
        $data['allPaket'] = $allPaket;
        $data['listKelas'] = $dataKelas;
        return view('base-2.packages', $data);
    }

    public function detailPackages($id)
    {
        $data['active'] = 'packages';
        $paket = Paket::find($id);
        if (!$paket) {
            return redirect()->back()->with('fail', 'Paket tidak ditemukan !');
        }
        $data['paket'] = $paket;
        return view('base-2.detail-packages', $data);
    }

    public function timetable()
    {
        $data['active'] = 'timetable';
        return view('base-2.timetable', $data);
    }

    public function termCondition(){
        $data['active'] = 'term-condition';
        return view('base-2.term-condition', $data);
    }

    public function detailTimetable($id)
    {
        $data['active'] = 'timetable';
        $kelasActivity = KelasActivity::find($id);
        if (!$kelasActivity) {
            return redirect()->back()->with('fail', 'Data kelas tidak ditemukan !');
        }

        $bookingKelas = BookingKelas::where('id_user', Auth::id())
        ->where('id_kelas_activity', $kelasActivity->id)
        ->first();

        if($bookingKelas){
            return redirect()->back()->with('fail', 'Anda sudah mengambil kelas ini. silahkan konfirmasi kehadiran terlebih dahulu');
        }

        $orderPaket = OrderPaket::where('id_user', Auth::id())
            ->whereHas('paket', function ($query) use ($kelasActivity) {
                $query->whereHas('kelasPaket', function ($query) use ($kelasActivity) {
                    $query->where('id_kelas', $kelasActivity->id_kelas);
                });
            })->first();

        $isActive = false;
        if ($orderPaket && $orderPaket->status == 1 && $orderPaket->is_active == 1) {
            $isActive = true;
        }

        $isBooking = false;
        if ($kelasActivity->bookingKelas->where('id_user', Auth::id())->first()) {
            $isBooking = true;
        }

        $data['kelasActivity'] = $kelasActivity;
        $data['orderPaket'] = $orderPaket;
        $data['isActive'] = $isActive;
        $data['isBooking'] = $isBooking;
        return view('base-2.detail-timetable', $data);
    }

    public function contactus()
    {
        $data['active'] = 'contact us';
        return view('base-2.contact-us', $data);
    }

    public function location()
    {
        $data['listLocation'] = Location::get();
        $data['active'] = 'location';
        return view('base-2.location', $data);
    }

    public function login()
    {
        $data['active'] = 'login';
        return view('base-2.login', $data);
    }

    public function registrasi()
    {
        $data['active'] = 'registrasi';
        return view('base-2.registrasi', $data);
    }

    public function detailLocation($id_location)
    {
        $location = Location::find($id_location);
        if (!$location) {
            return redirect()->back()->with('fail', 'Data lokasi tidak ditemukan !');
        }
        $data['location'] = $location;
        $data['active'] = 'location';
        return view('base-2.detail-location', $data);
    }

    public function postLogin(Request $request)
    {
        $data = $request->only(['email', 'password', 'remember']);
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data['remember'] = $request->remember ? true : false;
        if (!Filament::auth()->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $data['remember'])) {
            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);
        }

        session()->regenerate();
        if (Auth::user()->hasRole('Instruktur') || Auth::user()->hasRole('Super Admin')) {
            return app(LoginResponse::class);
        }
        return redirect()->route('base')->with('success', 'Login Berhasil');
    }

    public function confirmPembayaranPaket($paket)
    {
        $paket = Paket::find($paket);
        if (!$paket) {
            return redirect()->back()->with('fail', 'Data peket tidak ditemukan !');
        }

        if (count($paket->kelasPaket) == 0) {
            return redirect()->back()->with('fail', 'Kelas belum tersedia di dalam paket');
        }

        // Check Aktif Paket

        $data['id_user'] = Auth::user()->id;
        $data['id_paket'] = $paket->id;
        $data['harga'] = $paket->harga_diskon;
        $data['status'] = 2;
        $data['is_active'] = 0;
        $data['expired_date'] = Carbon::now()->addDays($paket->valid_day);

        OrderPaket::create($data);

        return redirect()->route('profile.billing')->with([
            'success' => 'Berhasil melakukan order paket, silahkan lakukan pembayaran dan konfirmasi ke admin. terimakasih',
            'info' => 'Silahkan masuk ke tab tidak aktif dapat dilihat pada halaman ini untuk mengupload bukti pembayaran.',
        ]);
    }

    public function postRegistrasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'no_hp' => ['required', 'regex:/^(\+62|0)(\d{8,12})$/'], // +62 followed by 9 to 15 digits
            'name' => 'required|string|max:225',
            'password' => 'required|min:8|max:20',
            'alamat' => 'required',
            // 'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max size as per your requirement
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        $checkEmail = User::where('email', $request->email)->first();
        if ($checkEmail) {
            return redirect()->back()->with('fail', 'Email sudah digunakan');
        }

        // $path = $request->file('foto')->store('public/customer');
        // $path = str_replace('public/', '', $path);
        // $data['foto'] = $path;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['alamat'] = $request->alamat;
        $data['status'] = 1;
        $data['no_hp'] = $request->no_hp;

        $user = User::create($data);

        $user->assignRole('Customer');

        return redirect()->route('login')->with('success', 'Akun berhasil di buat');
    }

    public function uploadBuktiPembayaran(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => 'required|image|mimes:png,jpg,jpeg,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        $bookingOrder = OrderPaket::find($id);
        if (!$bookingOrder) {
            return redirect()->back()->with('fail', 'Data tidak ditemukan !');
        }

        $path = $request->file('bukti_pembayaran')->store('public');
        $path = str_replace('public/', '', $path);
        $data['tanggal_pembayaran'] = now();
        $data['bukti_pembayaran'] = $path;

        $bookingOrder->update($data);

        return redirect()->back()->with('success', 'Berhasil mengupdate bukti pembayaran. silahkan tunggu konfirmasi dari Admin');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'tanggal_lahir' => 'nullable|string',
            'negara' => 'nullable|string',
            'no_hp' => ['required', 'regex:/^(\+62|0)(\d{8,12})$/'],
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/customer');
            $path = str_replace('public/', '', $path);

            $data['foto'] = $path;
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['alamat'] = $request->alamat;
        $data['jenis_kelamin'] = $request->jenis_kelamin;
        $data['no_hp'] = $request->no_hp;
        $data['negara'] = $request->negara;
        $data['tanggal_lahir'] = $request->tanggal_lahir;

        $id_user = Auth::id();
        $user = User::find($id_user);
        $user->update($data);

        return redirect()->back()->with('success', 'Profile berhasil di update');
    }

    public function orderKelas(Request $request)
    {
        if (!Auth::user()->hasRole('Customer')) {
            return redirect()->back()->with('fail', 'Mohon maaf kamu bukan sebagai customer');
        }
        $id_user = Auth::id();
        $id_kelas = Crypt::decryptString($request->kelas);
        $kelas = Kelas::find($id_kelas);
        if (!$kelas) {
            return redirect()->back()->with('fail', 'Data kelas tidak ditemukan !');
        }
        CustomerKelas::create([
            'id_user' => $id_user,
            'id_kelas' => $id_kelas,
            'status' => 2,
            'jumlah_pertemuan' => $kelas->jumlah_pertemuan
        ]);

        return redirect()->route('profile')->with('success', 'Berhasil melakukan order kelas');
    }


    public function postOrderKelas(Request $request, $id_kelas_activity)
    {
        $kelasActivity = KelasActivity::find($id_kelas_activity);
        if (!$kelasActivity) {
            return redirect()->back()->with('fail', 'Data tidak ditemukan !');
        }

        $data['id_user'] = Auth::id();
        $data['id_kelas_activity'] = $kelasActivity->id;
        $data['set_jam'] = $request->set_jam;
        $data['is_send_email'] = true;

        BookingKelas::create($data);
        return redirect()->route('profile.bookings')->with('success', 'Berhasil mengambil jadwal kelas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('base');
    }
}
