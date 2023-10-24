<?php

namespace App\Http\Livewire\Packages;

use App\Models\Paket;
use App\Models\Voucher;
use App\Models\VoucherCustomer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DetailPembayaran extends Component
{
    public $listeners = [
        'checkKodeVoucher',
        'batalkanVoucher',
        'gunakanKodeVoucher'
    ];
    public $id_paket;
    public $paket;
    public $kode_voucher;
    public $id_voucher;
    public $isCheck = false;
    public $isGunakan = false;
    public function render()
    {
        return view('livewire.packages.detail-pembayaran');
    }

    public function mount($id_paket)
    {
        $this->id_paket = $id_paket;
        $this->paket = Paket::find($this->id_paket);
    }

    public function checkKodeVoucher()
    {
        $voucher = Voucher::where('kode_voucher', $this->kode_voucher)->first();
        if (!$voucher) {
            return session()->flash('fail', 'Kode Voucher tidak valid !');
        }

        // Check Tanggal Kadaluarsa
        if ($voucher->expired <= now()) {
            return session()->flash('fail', 'Voucher sudah expired');
        }

        // check voucher paket
        $listIdPaket = json_decode($voucher->id_paket);
        if (!in_array($this->id_paket, $listIdPaket)) {
            return session()->flash('fail', 'Voucher tidak valid dengan paket !');
        }

        // Check tipe voucher
        if ($voucher->sekali_gunakan == 1) {
            $voucherCustomer = VoucherCustomer::where('id_voucher', $voucher->id)
                ->where('id_customer', Auth::id())
                ->where('status_digunakan', 1)->first();
            if ($voucherCustomer) {
                return session()->flash('fail', 'Voucher hanya boleh digunakan sekali saja');
            }
        }

        $this->isCheck = true;
        $this->id_voucher = $voucher->id;
        return session()->flash('success', 'Voucher tersedia');
    }

    public function batalkanVoucher()
    {
        $this->isCheck = false;
        $this->kode_voucher = null;
    }

    public function gunakanKodeVoucher()
    {
        $this->isGunakan = true;
        $this->emit('setVoucher', $this->id_voucher);
        return session()->flash('success', 'Voucher berhasil digunakan');
    }
}
