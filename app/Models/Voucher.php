<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $fillable = [
        'nama', 'deskripsi', 'expired', 'image', 'status', 'disc_harga', 'disc_percent', 'tanggal_aktif', 'lama_aktif'
    ];

    protected $appends = [
        'status_formatted'
    ];

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
