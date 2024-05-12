<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckInOut extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'check_in_outs';
    protected $fillable = [
        'id_user',
        'jadwal',
        'status',
        'id_location',
        'id_order_paket'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user')->withDefault();
    }

    public function location(){
        return $this->belongsTo(Location::class, 'id_location')->withDefault();
    }

    public function orderPaket(){
        return $this->belongsTo(OrderPaket::class, 'id_order_paket')->withDefault();
    }
}
