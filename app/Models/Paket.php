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
        'jumlah_pertemuan',
        'harga',
        'valid_day',
        'promote_code',
        'location',
        'diskon',
    ];

    protected $appends = [
        'harga_formatted', 'harga_diskon', 'location_formatted',
    ];

    public function getTypeKelasFormattedAttribute()
    {
        if ($this->type_kelas == 1) {
            return 'Online';
        } elseif ($this->type_kelas == 2) {
            return 'Onsite';
        }
    }

    public function getLocationFormattedAttribute()
    {
        if ($this->location == 0) {
            return 'All Location';
        } else {
            $location = Location::find($this->location);
            return $location->nama;
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

    public function kelasPaket(){
        return $this->hasMany(KelasPaket::class, 'id_paket');
    }

    public function orderPaket(){
        return $this->hasMany(OrderPaket::class, 'id_paket');
    }
}
