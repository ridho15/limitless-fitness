<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pakets';
    protected $fillable = [
        'nama',
        'harga',
        'valid_month',
        'promote_code',
        'diskon',
        'is_many_times',
        'is_show_public',
        'price_per_additional',
        'additional_member',
        'pro_rata',
        'signup_fee'
    ];

    protected $appends = [
        'harga_formatted',
        'harga_diskon',
        'is_many_times_formatted'
    ];

    public function getIsManyTimesFormattedAttribute()
    {
        if ($this->is_many_times == 1) {
            return 'Paket di order berkali kali';
        } else {
            return 'Paket di order hanya sekali';
        }
    }

    public function getTypeKelasFormattedAttribute()
    {
        if ($this->type_kelas == 1) {
            return 'Online';
        } elseif ($this->type_kelas == 2) {
            return 'Onsite';
        }
    }

    public function getHargaDiskonAttribute()
    {
        return $this->harga - ($this->harga * $this->diskon / 100);
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp.' . number_format($this->harga, 0, ',', '.');
    }

    public function orderPaket()
    {
        return $this->hasMany(OrderPaket::class, 'id_paket');
    }

    public function paketLocation()
    {
        return $this->hasMany(PaketLocation::class, 'id_paket');
    }

    public function location()
    {
        return $this->belongsToMany(Location::class, 'paket_locations', 'id_paket', 'id_location');
    }

    public function kelasPaket(){
        return $this->hasMany(KelasPaket::class, 'id_paket');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_pakets', 'id_paket', 'id_kelas');
    }
}
