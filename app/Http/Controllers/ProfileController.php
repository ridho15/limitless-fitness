<?php

namespace App\Http\Controllers;

use App\Mail\RegistrasiKonfirmasiMail;
use App\Models\BookingKelas;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'profile';
        return view('base-2.profile.index', $data);
    }

    public function bookings()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'bookings';

        $listData = [];
        $listKelas = Kelas::whereHas('paket', function ($query) {
            $query->whereHas('orderPaket', function ($query) {
                $query->where('id_user', Auth::id())
                    ->where('is_active', 1);
            });
        })->get();

        foreach ($listKelas as $index => $item) {
            $jumlahKredit = 0;
            $listOrderPaket = OrderPaket::whereHas('paket', function ($query) use ($item) {
                $query->where('id_kelas', $item->id);
            })->where('is_active', 1)
                ->get();
            foreach ($listOrderPaket as $orderPaket) {
                if ($orderPaket->expired_date >= now()) {
                    $jumlahKredit += $orderPaket->jumlah_pertemuan;
                }
            }

            $digunakan = BookingKelas::where('id_user', Auth::id())
                ->whereHas('kelasActivity', function ($query) use ($item) {
                    $query->where('id_kelas', $item->id);
                })
                ->whereIn('status_kehadiran', ['0', '1'])
                ->count();

            if($jumlahKredit == 0){
                $sisa = 0;
                $digunakan = 0;
            }else{
                $sisa = $jumlahKredit - $digunakan;
            }

            $digunakan = $jumlahKredit -
                array_push($listData, [
                    'nama_kelas' => $item->nama_kelas,
                    'jumlah_kredit' => $jumlahKredit,
                    'digunakan' => $digunakan,
                    'sisa' => $sisa
                ]);
        }

        $listOrderPaket = OrderPaket::where('id_user', $user->id)
            ->where('is_active', 1)
            ->get();

        $listPaket = Paket::whereHas('kelas', function($query){
            $query->where('nama_kelas', 'Private Class');
        })->get();

        $jumlahKredit = 0;
        foreach ($listOrderPaket as $item) {
            $jumlahKredit += $item->paket->jumlah_pertemuan;
        }

        $jumlahDigunakan = BookingKelas::where('id_user', $user->id)
            ->where(function ($query) {
                $query->where('status_kehadiran', '0')
                    ->orWhere('status_kehadiran', '1');
            })->count();

        $jumlahHadir = BookingKelas::where('id_user', $user->id)
            ->where('status_kehadiran', '1')
            ->count();

        $jumlahIzin = BookingKelas::where('id_user', $user->id)
            ->where('status_kehadiran', '2')
            ->count();

        $jumlahTidakHadir = BookingKelas::where('id_user', $user->id)
            ->where('status_kehadiran', '0')
            ->count();

        $sisaKredit = $jumlahKredit - $jumlahDigunakan;
        $data['listBookingKelas'] = BookingKelas::where('id_user', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['jumlahKredit'] = $jumlahKredit;
        $data['jumlahDigunakan'] = $jumlahDigunakan;
        $data['sisaKredit'] = $sisaKredit;
        $data['jumlahHadir'] = $jumlahHadir;
        $data['jumlahIzin'] = $jumlahIzin;
        $data['jumlahTidakHadir'] = $jumlahTidakHadir;
        $data['listPaket'] = $listPaket;
        $data['listKelas'] = $listKelas;
        $data['listData'] = $listData;
        return view('base-2.profile.bookings', $data);
    }

    public function updateKehadiran(Request $request, $id_booking_kelas)
    {
        $validator = Validator::make($request->all(), [
            'status_kehadiran' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        $bookingKelas = BookingKelas::find($id_booking_kelas);
        if (!$bookingKelas) {
            return redirect()->back()->with('fail', 'Data tidak ditemukan !');
        }

        $kelasActivity = $bookingKelas->kelasActivity;
        $lastTimeClass = Carbon::parse($kelasActivity->jam_mulai)->subDays(1)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');
        if ($lastTimeClass <= now()) {
            return redirect()->back()->with('fail', 'Sudah tidak update kelas');
        }

        $bookingKelas->update([
            'status_kehadiran' => $request->status_kehadiran
        ]);

        return redirect()->back()->with('success', 'Berhasil mengupdate status kehadiran');
    }

    public function batalBookingKelas($id)
    {
        $bookingKelas = BookingKelas::find($id);
        if (!$bookingKelas) {
            return redirect()->back()->with('fail', 'Data Booking Kelas tidak ditemukan !');
        }

        $bookingKelas->delete();
        return redirect()->back()->with('success', 'Kelas berhasil di batalkan !');
    }

    public function packages()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'packages';
        $listPaketAktif = OrderPaket::where('id_user', $user->id)->where(function ($query) {
            $query->where('status', 1)
                ->orWhere('status', 2);
        })->where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        // Update paket
        foreach($listPaketAktif as $item){
            if($item->expired_date < now()){
                $item->update([
                    'is_active' => 0
                ]);
            }
            $bookingKelas = OrderPaket::where('id_user', Auth::id())
            ->where('id', $item->id)
            ->whereHas('paket', function($query){
                $query->whereHas('kelas', function($query){
                    $query->whereHas('kelasActivity', function($query){
                        $query->whereHas('bookingKelas', function($query){
                            $query->where('id_user', Auth::id())
                            ->where('status_kehadiran', 1);
                        });
                    });
                });
            })->first();
            if($bookingKelas){
                $item['keterangan'] = 'Paket sudah digunakan';
            }else{
                $item['keterangan'] = 'Paket belum digunakan';
            }
        }
        $listPaketExpired = OrderPaket::where('id_user', $user->id)
        ->where('expired_date', '<=', now())
            ->orderBy('created_at', 'DESC')
            ->get();
        $data['listPaketAktif'] = $listPaketAktif;
        $data['listPaketExpired'] = $listPaketExpired;
        return view('base-2.profile.packages', $data);
    }

    public function memberships()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'memberships';
        return view('base-2.profile.memberships', $data);
    }

    public function billing()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'billing';

        $listPaymentDue = OrderPaket::where('id_user', $user->id)
            ->where('expired_date', '>=', now())
            ->where('is_active', 0)
            ->get();

        $listTransactionHistory = OrderPaket::where('id_user', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $listGenerateInvoice = OrderPaket::where('id_user', $user->id)
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['listPaymentDue'] = $listPaymentDue;
        $data['listTransactionHistory'] = $listTransactionHistory;
        $data['listGenerateInvoice'] = $listGenerateInvoice;
        return view('base-2.profile.billing', $data);
    }

    public function notificationSettings()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'notification-settings';
        return view('base-2.profile.notification-settings', $data);
    }

    public function waiverForm()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'waiver-form';
        return view('base-2.profile.waiver-form', $data);
    }

    public function resendVerifikasiEmail($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('fail', 'User tidak ditemukan !');
        }

        Mail::to($user->email)->send(new RegistrasiKonfirmasiMail($user));

        return redirect()->back()->with('success', 'Berhasil mengirimkan konfirmasi konfirmasi email');
    }
}
