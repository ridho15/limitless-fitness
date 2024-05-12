<?php

namespace App\Http\Controllers;

use App\Mail\RegistrasiKonfirmasiMail;
use App\Models\BookingKelas;
use App\Models\CustomerJadwalStatic;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use App\Models\VoucherCustomer;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'profile';
        $kode = Crypt::encrypt($user->id);
        $data['kode'] = $kode;
        return view('base-2.profile.index', $data);
    }

    public function bookings()
    {
        // return Carbon::now()->addWeeks()->startOfWeek(Carbon::TUESDAY)->isoFormat('DD-MM-YYYY');
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'bookings';

        $listData = [];
        $listKelas = Kelas::with(['paket.orderPaket' => function ($query) {
            $query->where('id_user', Auth::id())
                ->whereDate('expired_date', '>=', now())
                ->where('status', 1)
                ->where('is_active', 1)
                ->where('sisa', '>', 0);
        }])
            ->whereHas('paket', function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_user', Auth::id())
                        ->where('is_active', 1);
                });
            })->get();

        foreach ($listKelas as $index => $item) {
            $jumlahKredit = 0;
            $sisa = 0;
            $listOrderPaket = OrderPaket::whereHas('paket', function ($query) use ($item) {
                $query->where('id_kelas', $item->id);
            })->where('is_active', 1)
                ->where('status', 1)
                ->where('id_user', Auth::id())
                ->whereDate('expired_date', '>=', now())
                ->get();
            foreach ($listOrderPaket as $orderPaket) {
                $jumlahKredit += $orderPaket->jumlah_pertemuan;
                $sisa += $orderPaket->sisa;
            }

            $digunakan = BookingKelas::where('id_user', Auth::id())
                ->whereHas('kelasActivity', function ($query) use ($item) {
                    $query->where('id_kelas', $item->id);
                })
                ->whereIn('status_kehadiran', ['0', '1'])
                ->count();

            if ($jumlahKredit == 0) {
                $sisa = 0;
                $digunakan = 0;
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

        $listPaket = Paket::whereHas('kelas', function ($query) {
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

        $listBookingKelas = BookingKelas::where('id_user', $user->id)
            ->whereHas('kelasActivity', function ($query) {
                $query->whereDate('tanggal', '<=', now());
            })
            ->get()->sortByDesc('kelasActivity.tanggal');

        $listKelasConfirmed = BookingKelas::where('id_user', Auth::id())
            ->whereIn('status_kehadiran', [0, 1])
            ->with('kelasActivity')
            ->whereHas('kelasActivity', function ($query) {
                $query->whereDate('tanggal', '<=', now());
            })
            ->get()
            ->sortByDesc('kelasActivity.tanggal');
        $sisaKredit = $jumlahKredit - $jumlahDigunakan;

        $listCustomerJadwalStatis = CustomerJadwalStatic::where('id_user', Auth::id())
            ->get();

        $data['listBookingKelas'] = $listBookingKelas;
        $data['jumlahKredit'] = $jumlahKredit;
        $data['jumlahDigunakan'] = $jumlahDigunakan;
        $data['sisaKredit'] = $sisaKredit;
        $data['jumlahHadir'] = $jumlahHadir;
        $data['jumlahIzin'] = $jumlahIzin;
        $data['jumlahTidakHadir'] = $jumlahTidakHadir;
        $data['listPaket'] = $listPaket;
        $data['listKelas'] = $listKelas;
        $data['listData'] = $listData;
        $data['listKelasConfirmed'] = $listKelasConfirmed;
        $data['listCustomerJadwalStatis'] = $listCustomerJadwalStatis;
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

        activity()->causedBy(Auth::user())
            ->log('Melakukan update kehadiran');

        return redirect()->back()->with('success', 'Berhasil mengupdate status kehadiran');
    }

    public function batalBookingKelas($id)
    {
        $bookingKelas = BookingKelas::find($id);
        if (!$bookingKelas) {
            return redirect()->back()->with('fail', 'Data Booking Kelas tidak ditemukan !');
        }

        $bookingKelas->delete();

        activity()->causedBy(Auth::user())
            ->log('Melakukan pembatalan booking kelas');
        return redirect()->back()->with('success', 'Kelas berhasil di batalkan !');
    }

    public function packages()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'packages';
        $listPaketAktif = OrderPaket::where('id_user', $user->id)->whereIn('status', [1, 2])->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        // Update paket
        foreach ($listPaketAktif as $item) {
            $id_order_paket = $item->id;
            $bookingKelas = BookingKelas::where('id_user', Auth::id())
                ->where('status_kehadiran', 1)
                ->whereHas('kelasActivity', function ($query) use ($id_order_paket) {
                    $query->whereHas('kelas', function ($query) use ($id_order_paket) {
                        $query->whereHas('paket', function ($query) use ($id_order_paket) {
                            $query->whereHas('orderPaket', function ($query) use ($id_order_paket) {
                                $query->where('id', $id_order_paket);
                            });
                        });
                    });
                })->first();
            if ($bookingKelas) {
                $item['keterangan'] = 'Paket sudah digunakan';
            } else {
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

    public function voucher()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'voucher';
        $data['listVoucherCustomer'] = VoucherCustomer::where('id_customer', Auth::id())->orderBy('created_at', 'DESC')->get();
        return view('base-2.profile.voucher', $data);
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
            ->where('is_active', 0)
            ->orderBy('created_at', 'DESC')
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

    public function invoice($id)
    {
        $orderPaket = OrderPaket::find($id);
        if (!$orderPaket) {
            return redirect()->back()->with('fail', 'Data tidak ditemukan !');
        }

        $data['orderPaket'] = $orderPaket;
        $pdf = FacadePdf::loadView('pdf.invoice-2', $data)->setPaper('a4', 'landscape');
        return $pdf->download('invoice#' . sprintf('%08d', $orderPaket->id) . '_' . date('d-m-Y', strtotime($orderPaket->created_at)) . '.pdf');

        // return view('pdf.invoice-2', $data);
    }

    public function postCustomerJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|numeric',
            'jam' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all()[0]);
        }

        $customerJadwalStatic = CustomerJadwalStatic::where('hari', $request->hari)
            ->where('jam', $request->jam)
            ->where('id_user', Auth::id())
            ->first();

        if ($customerJadwalStatic) {
            return redirect()->back()->with('fail', 'Jadwal sudah di ada');
        }

        CustomerJadwalStatic::create([
            'id_user' => Auth::id(),
            'jam' => $request->jam,
            'hari' => $request->hari
        ]);

        activity()
            ->causedBy(Auth::user())
            ->log("Membuat jadwal statis pada customer");

        return redirect()->back()->with('success', 'Data waktu berhasil di simpan');
    }

    public function hapusRepetitifTime($id)
    {
        $customerJadwalStatic = CustomerJadwalStatic::find($id);
        if (!$customerJadwalStatic) {
            return redirect()->back()->with('fail', 'Data tidak ditemukan !');
        }

        $customerJadwalStatic->delete();

        return redirect()->back()->with('success', 'Repetitif time berhasil di hapus');
    }

    public function webcame(Request $request)
    {
        return view('base-2.profile.webcame');
    }

    public function store(Request $request)
    {
        $img = $request->image;
        $folderPath = "uploads/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';

        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);
        return public_path('/storage/upload/' . $fileName);
    }

    public function upload(Request $request)
    {
        try {
            $image = $request->image;
        if ($image) {
            $folderPath = "public/qrcode/";
            $image_parts = explode(";base64,", $image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';

            $file = $folderPath . $fileName;
            Storage::put($file, $image_base64);

            $file = str_replace('public/', '', $file);
            $result = (new QRCode())->readFromFile(public_path('/storage/' . $file));

            $content = $result->data;
            $matrix = $result->getQRMatrix();

            return [
                'content' => $content,
                'matrix' => $matrix
            ];
        } else {
            return response()->json([
                'message'   => "Image tidak terbaca"
            ]);
        }
        } catch (\Throwable $th) {
            return response()->json([
                'status'=> false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
