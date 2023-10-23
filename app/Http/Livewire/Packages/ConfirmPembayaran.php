<?php

namespace App\Http\Livewire\Packages;

use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\Voucher;
use App\Models\VoucherCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConfirmPembayaran extends Component
{
    public $listeners = [
        'gunakanVoucher',
        'konfirmasiPembayaran'
    ];
    public $id_paket;
    public $id_voucher_customer;
    public $paket;
    public $listVoucher = [];
    public $potongan = 0;
    public function render()
    {
        return view('livewire.packages.confirm-pembayaran');
    }

    public function mount($id_paket)
    {
        $this->id_paket = $id_paket;
        $this->paket = Paket::find($this->id_paket);
        $this->listVoucher = VoucherCustomer::where('id_customer', Auth::id())
            ->where('status', 1)
            ->where('status_digunakan', 0)
            ->whereDate('expired', '>', now())
            ->get();
    }

    public function gunakanVoucher($id_voucher_customer)
    {
        $voucherCustomer = VoucherCustomer::find($id_voucher_customer);
        if (!$voucherCustomer) {
            return session()->flash('fail', 'Data voucher customer tidak ditemukan !');
        }

        $this->id_voucher_customer = $id_voucher_customer;

        if ($voucherCustomer->voucher->disc_percent == 0) {
            $this->potongan = $voucherCustomer->voucher->disc_harga;
        } else {
            $this->potongan = $this->paket * $voucherCustomer->voucher->disc_percent / 100;
        }
    }

    public function konfirmasiPembayaran()
    {
        // Check time order
        $startTime = Carbon::now()->locale('id')->subMinutes(5)->isoFormat('YYYY-MM-DD H:m');
        $endTime = Carbon::now()->isoFormat('YYYY-MM-DD H:m');

        // check order paket
        $orderPaket = OrderPaket::where('id_paket', $this->id_paket)
            ->where('id_user', Auth::id())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereTime('created_at', '>=', $startTime)
                    ->whereTime('created_at', '<=', $endTime);
            })->first();

        if ($orderPaket) {
            return session()->flash('fail', 'Paket baru saja berhasil di order. tunggu setelah 5 menit lagi untuk order paket selanjutnya');
        }

        // check paket hanya sekali order
        $orderPaket = OrderPaket::whereHas('paket', function($query){
            $query->where('id', $this->id_paket)
            ->where('is_many_times', 0);
        })
        ->where('id_user', Auth::id())
        ->where('status', 1)
        ->first();
        if($orderPaket){
            return session()->flash('fail', 'Paket sudah pernah di order. Silahkan order paket lain');
        }

        $data['id_user'] = Auth::id();
        $data['id_paket'] = $this->id_paket;
        $data['harga'] = $this->paket->harga_diskon - $this->potongan;
        $data['status'] = 2;
        $data['is_active'] = 0;
        $data['honor'] = $this->paket->honor;
        $data['jumlah_pertemuan'] = $this->paket->jumlah_pertemuan;
        $data['expired_date'] = Carbon::now()->addDays($this->paket->valid_day);

        $voucherCustomer = VoucherCustomer::find($this->id_voucher_customer);

        OrderPaket::create($data);
        activity()->causedBy(Auth::user())
            ->log('Melakukan konfirmasi pembayaran paket');

        if ($voucherCustomer) {
            if (date('Y-m-d', strtotime($voucherCustomer->expired)) < date('Y-m-d')) {
                return session()->flash('fail', date('Y-m-d', strtotime($voucherCustomer->expired)));
                return session()->flash('fail', 'Voucher sudah expired !');
            } elseif ($voucherCustomer->status == 0) {
                return session()->flash('fail', 'Voucher customer sudah tidak aktif !');
            } elseif ($voucherCustomer->status_digunakan == 1) {
                return session()->flash('fail', 'Voucher customer sudah digunakan !');
            }
            $voucherCustomer->update([
                'status_digunakan' => 1,
                'tanggal_digunakan' => now()
            ]);
            activity()->causedBy(Auth::user())
                ->log('Melakukan order paket menggunakan voucher');
        }

        return redirect()->route('profile.billing')->with([
            'success' => 'Berhasil melakukan order paket, silahkan lakukan pembayaran dan konfirmasi ke admin. terimakasih',
            'info' => 'Silahkan masuk ke tab tidak aktif dapat dilihat pada halaman ini untuk mengupload bukti pembayaran.',
        ]);
    }
}
