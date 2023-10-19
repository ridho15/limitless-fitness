<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $fillable = [
        'nama', 'deskripsi', 'expired', 'image', 'status', 'disc_harga', 'disc_percent', 'tanggal_aktif', 'lama_aktif', 'id_paket'
    ];

    protected $appends = [
        'status_formatted', 'avaiable_voucher'
    ];

    public function getAvaiableVoucherAttribute()
    {
        if ($this->id_paket == null) {
            return 'Voucher avaiable untuk semua paket';
        } else {
            $id_paket = json_decode($this->id_paket);
            $listPaket = Paket::whereIn('id', $id_paket)->select('nama')->get();
            $text = "";
            foreach ($listPaket as $item) {
                $text .= $item->nama . ",";
            }

            return $text;
        }
    }

    public function getStatusFormattedAttribute()
    {
        if ($this->status == 1) {
            return 'Aktif';
        } else {
            return 'Tidak Aktif';
        }
    }

    public function voucherCustomer()
    {
        return $this->hasMany(VoucherCustomer::class, 'id_voucher');
    }
}
