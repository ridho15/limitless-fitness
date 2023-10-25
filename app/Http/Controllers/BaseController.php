<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Base\Instruktur;
use App\Mail\RegistrasiKonfirmasiMail;
use App\Mail\SendForgotPasswordMail;
use App\Models\BookingKelas;
use App\Models\BookingOrder;
use App\Models\CustomerJadwalStatic;
use App\Models\CustomerKelas;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\KelasPaket;
use App\Models\Location;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use App\Models\VoucherCustomer;
use Carbon\Carbon;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Input\Input;

class BaseController extends Controller
{
    use WithRateLimiting;
    public function index()
    {
        // $helper = new HelperController;
        // return $helper->updateData();
        // $instruktur = User::with('kelasActivity')->whereHas('roles', function ($query) {
        //     $query->where('name', 'Instruktur');
        // })->whereHas('kelasActivity', function ($query) {
        //     $query->where('tanggal', '2023-08-16')
        //         ->whereJamMulai([
        //             '10:00:00', '08:00:00', '12:00:00'
        //         ]);
        // })->get();
        // return $instruktur;
        if (Auth::user() && Auth::user()->id_location != null) {
            session()->put('phone_number', Auth::user()->location->no_hp);
            session()->put('isLocationChecked', true);
        }
        $data['active'] = 'home';
        return view('base-2.index', $data);
    }

    public function forgotPassword()
    {
        $data['active'] = 'forgot-password';
        return view('base-2.forgot-password', $data);
    }

    public function postForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        // Check email
        $user = User::where('email', $request->email)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Customer');
            })->first();

        if ($user) {
            Mail::to($user->email)->send(new SendForgotPasswordMail($user));
            return redirect()->back()->with('success', 'Link untuk update password sudah di kirimkan ke email anda. silahkan check kotak masuk email anda');
        } else {
            return redirect()->back()->with('fail', 'Email kamu tidak terdaftar');
        }
    }

    public function checkToken(Request $request)
    {
        $token = Crypt::decryptString($request->token);
        $data = json_decode($token);
        if (date('Y-m-d H:i', strtotime($data->waktu)) >= date('Y-m-d H:i')) {
            return view('base-2.update-password', [
                'id_user' => $data->id_user
            ]);
        } else {
            return view('403');
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        $user = User::find($request->id_user);
        if (!$user) {
            return redirect()->back()->with('fail', 'Data customer tidak ditemukan !');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        activity()
            ->causedBy($user)
            ->log('Melakukan update password akun');

        return redirect()->route('login')->with('success', 'Berhasil melakukan update password akun');
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
        $data['kelas'] = $kelas;
        return view('base-2.detail-class', $data);
    }

    public function packages()
    {
        $user = User::find(Auth::id());
        $data['active'] = 'packages';
        $allPaket = Paket::where(function ($query) use ($user) {
            if (isset($user->studio)) {
                $query->where('location', $user->studio->id_location);
            }
        })
            ->get();
        $listKelas = Kelas::with('paket')->whereHas('paket', function ($query) use ($user) {
            if (isset($user->studio)) {
                $query->where('location', $user->studio->id_location);
            }
        })->get();
        $data['allPaket'] = $allPaket;
        $data['listKelas'] = $listKelas;
        return view('base-2.packages', $data);
    }

    public function detailPackages($id)
    {
        $data['active'] = 'packages';
        $paket = Paket::find($id);
        if (!$paket) {
            return redirect()->back()->with('fail', 'Paket tidak ditemukan !');
        }
        $isDisable = false;
        if ($paket->is_many_times == 0) {
            $orderPaket = OrderPaket::where('id_user', Auth::id())
                ->whereHas('paket', function ($query) use ($paket) {
                    $query->where('id', $paket->id)
                        ->where('is_many_times', 0);
                })
                ->where('status', 1)
                ->first();
            if ($orderPaket) {
                $isDisable = true;
            }
        }

        $data['paket'] = $paket;
        $data['isDisable'] = $isDisable;
        return view('base-2.detail-packages', $data);
    }

    public function timetable()
    {
        $listInstruktur = User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->where('status', 1)->get();
        $data['listInstruktur'] = $listInstruktur;
        $listRepetitifTime = CustomerJadwalStatic::where('id_user', Auth::id())
            ->get();
        $data['active'] = 'timetable';
        $data['listRepetitifTime'] = $listRepetitifTime;
        return view('base-2.timetable', $data);
    }

    public function termCondition()
    {
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

        if ($bookingKelas) {
            return redirect()->back()->with('fail', 'Anda sudah mengambil kelas ini. silahkan konfirmasi kehadiran terlebih dahulu');
        }

        $orderPaket = OrderPaket::where('id_user', Auth::id())
            ->whereHas('paket', function ($query) use ($kelasActivity) {
                $query->where('id_kelas', $kelasActivity->id_kelas);
            })->where('is_active', 1)
            ->whereDate('expired_date', '>=', now())
            ->where('sisa', '>=', 0)
            ->orderBy('sisa', 'DESC')
            ->first();

        if ($orderPaket) {
            $isActive = true;
        } else {
            $isActive = false;
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

    public function konfirmasiAkun($id_user)
    {
        $id_user = Crypt::decryptString($id_user);
        $user = User::find($id_user);

        if (!$user) {
            return redirect()->back()->with('fail', 'Data user tidak ditemukan !');
        }

        $user->update([
            'status' => 1,
            'email_verified_at' => now()
        ]);

        activity()->causedBy(Auth::user())
            ->log('Melakukan Konfirmasi akun');

        return redirect()->route('login')->with('success', 'Akun dan email berhasil di konfirmasi');
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
        $data['listLocation'] = Location::get();
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
            $error = ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);
            return redirect()->back()->with('fail', $error->validator->getMessageBag()->getMessages()['email'][0]);
        }

        Auth::logoutOtherDevices($data['password']);
        session()->regenerate();
        if (Auth::user()->id_studio != null) {
            session()->put('isStudioChecked', true);
            session()->put('phone_number', Auth::user()->studio->no_hp);
        }
        activity()->causedBy(Auth::user())
            ->log('Melakukan login');
        if (Auth::user()->hasRole(['Super Admin', 'Instruktur', 'Admin'])) {
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

        // Check Aktif Paket

        $data['id_user'] = Auth::user()->id;
        $data['id_paket'] = $paket->id;
        $data['harga'] = $paket->harga_diskon;
        $data['status'] = 2;
        $data['is_active'] = 0;
        $data['honor'] = $paket->honor;
        $data['jumlah_pertemuan'] = $paket->jumlah_pertemuan;
        $data['expired_date'] = Carbon::now()->addDays($paket->valid_day);

        // Check time order
        $startTime = Carbon::now()->locale('id')->subMinutes(5)->isoFormat('YYYY-MM-DD H:m');
        $endTime = Carbon::now()->isoFormat('YYYY-MM-DD H:m');

        $orderPaket = OrderPaket::where('id_paket', $paket->id)
            ->where('id_user', Auth::id())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereTime('created_at', '>=', $startTime)
                    ->whereTime('created_at', '<=', $endTime);
            })->first();

        if ($orderPaket) {
            return redirect()->back()->with('fail', 'Paket baru saja berhasil di order. tunggu setelah 5 menit lagi untuk order paket selanjutnya');
        }

        OrderPaket::create($data);

        activity()->causedBy(Auth::user())
            ->log('Melakukan konfirmasi pembayaran paket');

        return redirect()->route('profile.billing')->with([
            'success' => 'Berhasil melakukan order paket, silahkan lakukan pembayaran dan konfirmasi ke admin. terimakasih',
            'info' => 'Silahkan masuk ke tab tidak aktif dapat dilihat pada halaman ini untuk mengupload bukti pembayaran.',
        ]);
    }

    public function postRegistrasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'no_hp' => ['required', 'regex:/^(\+62|0)(\d{8,12})$/', 'digits_between:10,12'], // +62 followed by 9 to 15 digits
            'name' => 'required|string|max:225',
            'password' => 'required|min:8|max:20',
            'alamat' => 'required',
            'id_location' => 'required|numeric'
            // 'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max size as per your requirement
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(array_merge([
                'fail' => $validator->errors()->all()[0],
            ], $request->all()));
        }

        $checkEmail = User::where('email', $request->email)->withTrashed()->first();
        if ($checkEmail) {
            return redirect()->back()->with(array_merge([
                'fail' => 'Email sudah digunakan',
            ], $request->all()));
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['alamat'] = $request->alamat;
        $data['status'] = 0;
        $data['no_hp'] = $request->no_hp;
        $data['id_location'] = $request->id_location;

        $user = User::create($data);

        $user->assignRole('Customer');

        Mail::to($request->email)->send(new RegistrasiKonfirmasiMail($user));
        // return redirect()->route('login')->with('success', 'Akun berhasil di buat');
        return view('base-2.registration-success')->with('success', 'Akun berhasil di buat');
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

        activity()->causedBy(Auth::user())
            ->log('Melakukan upload bukti pembayaran');

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
            'id_location' => 'required|numeric'
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
        $data['id_location'] = $request->id_location;

        $id_user = Auth::id();
        $user = User::find($id_user);
        $user->update($data);

        activity()->causedBy(Auth::user())
            ->log('Melakukan update profile');

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
        // Check Kelas activity
        $kelasActivity = KelasActivity::find($id_kelas_activity);
        if (!$kelasActivity) {
            return redirect()->back()->with('fail', 'Data tidak ditemukan !');
        }


        // Check Order paket
        $orderPaket = OrderPaket::where('id_user', Auth::id())
            ->whereHas('paket', function ($query) use ($kelasActivity) {
                $query->where('id_kelas', $kelasActivity->id_kelas);
            })->where('sisa', '>', 0)
            ->whereDate('expired_date', '>=', now())
            ->where('status', 1)
            ->where('is_active', 1)
            ->first();

        if (!$orderPaket) {
            return redirect()->back()->with('fail', 'Silahkan order paket terlebih dahulu');
        }

        //Check slot kelas
        $jumlahBookingKelas = BookingKelas::where('id_kelas_activity', $kelasActivity->id)
            ->where(function ($query) {
                $query->where('status_kehadiran', '1')
                    ->orWhere('status_kehadiran', '0');
            })->count();

        if ($jumlahBookingKelas >= $kelasActivity->kelas->max_anggota) {
            return redirect()->back()->with('fail', 'Kelas Sudah Penuh');
        }

        $bookingKelas = BookingKelas::where('id_user', Auth::id())
            ->where('id_kelas_activity', $kelasActivity->id)->first();

        if ($bookingKelas) {
            return redirect()->back()->with('fail', 'Anda telah mengambil jadwal kelas ini');
        }

        $data['id_user'] = Auth::id();
        $data['id_kelas_activity'] = $kelasActivity->id;
        $data['set_jam'] = $request->set_jam;
        $data['status_kehadiran'] = 1;
        $data['is_send_email'] = true;

        BookingKelas::create($data);

        $orderPaket->update([
            'sisa' => $orderPaket->sisa - 1
        ]);

        activity()->causedBy(Auth::user())
            ->log('Melakukan Bookingk kelas');
        return redirect()->route('profile.bookings')->with('success', 'Berhasil mengambil jadwal kelas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        activity()->causedBy(Auth::user())
            ->log('Logout account');
        return redirect()->route('base');
    }

    public function konfirmasiKehadiran(Request $request)
    {
        $bookingKelas = BookingKelas::find($request->id_booking_kelas);
        $tanggal = Carbon::parse($bookingKelas->kelasActivity->tanggal)->locale('id')->subDays(1)->isoFormat('YYYY-MM-DD');
        $waktu = $tanggal . ' ' . date('H:i:s');
        if (date('Y-m-d') . ' ' . '19:00:00' >= $waktu) {
            return redirect()->route('profile.bookings')->with('fail', "Confirmation of attendance is no longer valid");
        }

        activity()->causedBy(Auth::user())
            ->log('Melakukan konfirmasi kehadiran');
        return redirect()->route('profile.bookings')->with('success', 'Berhasil mengkonfirmasi kehadiran');
    }

    public function simpanKeterangan(Request $request, $id_booking_kelas)
    {
        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->route('base')->with('fail', 'Keterangan kosong');
        }
        $bookingKelas = BookingKelas::find($id_booking_kelas);
        if (now() <= $bookingKelas->kelasActivity->tanggal . ' ' . $bookingKelas->kelasActivity->jam_mulai && date('H:i') <= '19:00') {
            $keterangan = $request->keterangan;

            $bookingKelas->update([
                'keterangan' => $keterangan,
                'status_kehadiran' => 2
            ]);

            $orderPaket = OrderPaket::where('id_user', $bookingKelas->id_user)
                ->whereHas('paket', function ($query) use ($bookingKelas) {
                    $query->where('id_kelas', $bookingKelas->kelasActivity->id_kelas);
                })->whereDate('expired_date', '>=', now())
                ->where('is_active', 1)
                ->where('status', 1)
                ->first();

            if ($orderPaket) {
                $orderPaket->update([
                    'sisa' => $orderPaket->sisa + 1
                ]);
            }

            activity()->causedBy(Auth::user())
                ->log('Menyimpan keterangan kehadiran / Customer tidak dapat hadir dengan keterangan');

            return redirect()->route('profile.bookings')->with('success', 'Berhasil menghkonfirmasi kehadiran');
        } else {
            return redirect()->route('profile.bookings')->with('fail', 'Jadwal sudah tidak valid !');
        }
    }
}
