<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerKelas extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'customer_kelas';
    protected $fillable = [
        'id_user',
        'id_kelas',
        'status',
        'tanggal_bayar',
        'bukti_bayar',
    ];

    protected $appends = [
        'status_formatted'
    ];

    public function getStatusFormattedAttribute(){
        if($this->status == 1){
            return 'Avaiable';
        }else if($this->status == 2){
            return 'Belum Bayar';
        }else if($this->status == 0){
            return 'Dibatalkan';
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
