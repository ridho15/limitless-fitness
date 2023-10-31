<?php

namespace App\Http\Livewire\Packages;

use App\Models\Location;
use App\Models\Paket;
use App\Models\Voucher;
use App\Models\VoucherCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DetailPembayaran extends Component
{
    public $listeners = [
        'checkKodeVoucher',
        'batalkanVoucher',
        'gunakanKodeVoucher',
        'setIdVoucher'
    ];
    public $id_paket;
    public $paket;
    public $kode_voucher;
    public $id_voucher;
    public $isCheck = false;
    public $isGunakan = false;
    public $subtotal = 0;
    public $potongan = 0;

    public function render()
    {
        return view('livewire.packages.detail-pembayaran');
    }

    public function mount($id_paket)
    {
        $this->id_paket = $id_paket;
        $this->paket = Paket::find($this->id_paket);
        $this->subtotal = $this->paket->harga_diskon;
    }

    public function checkKodeVoucher()
    {
        $voucher = Voucher::where(DB::raw('BINARY `kode_voucher`'), $this->kode_voucher)->first();
        if (!$voucher) {
            return session()->flash('fail', 'Kode Voucher tidak valid !');
        }

        // Check tanggal aktif
        if ($voucher->tanggal_aktif > now()) {
            return session()->flash('fail', 'Voucher belum memasuki tanggal aktif');
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

        if ($voucher->disc_harga == 0) {
            $this->potongan = $this->paket->harga_diskon * $voucher->disc_percent / 100;
        } else {
            $this->potongan = $voucher->disc_harga;
        }

        $this->isCheck = true;
        $this->id_voucher = $voucher->id;
        return session()->flash('success', 'Voucher tersedia');
    }

    public function batalkanVoucher()
    {
        $this->isCheck = false;
        $this->kode_voucher = null;
        $this->potongan = 0;
    }

    public function gunakanKodeVoucher()
    {
        $this->isGunakan = true;
        $this->emit('setVoucher', $this->id_voucher);
        return session()->flash('success', 'Voucher berhasil digunakan');
    }

    public function setIdVoucher($id_voucher)
    {
        $this->id_voucher = $id_voucher;
        $voucher = Voucher::find($this->id_voucher);
        if (!$voucher) {
            return session()->flash('fail', 'Kode Voucher tidak valid !');
        }
        if ($voucher->disc_harga == 0) {
            $this->potongan = $this->paket->harga_diskon * $voucher->disc_percent / 100;
        } else {
            $this->potongan = $voucher->disc_harga;
        }
    }
}
