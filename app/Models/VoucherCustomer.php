<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCustomer extends Model
{
    use HasFactory;
    protected $table = 'voucher_customers';
    protected $fillable = [
        'id_voucher', 'id_customer', 'status', 'expired', 'status_digunakan', 'tanggal_digunakan'
    ];

    protected $appends = [
        'status_formatted', 'status_digunakan_formatted'
    ];

    public function getStatusFormattedAttribute(){
        if($this->status == 1){
            return 'Voucher Aktif';
        }else{
            return 'Voucher tidak aktif';
        }
    }

    public function getStatusDigunakanFormattedAttribute(){
        if($this->status_digunakan == 1){
            return 'Sudah digunakan';
        }else{
            return 'Belum digunakan';
        }
    }

    public function voucher(){
        return $this->belongsTo(Voucher::class, 'id_voucher')->withDefault();
    }

    public function customer(){
        return $this->belongsTo(User::class, 'id_customer')->withDefault();
    }
}
