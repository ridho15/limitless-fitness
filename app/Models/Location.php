<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'locations';
    protected $fillable = [
        'nama',
        'deskripsi',
        'no_hp',
        'alamat',
        'email',
        'url_location',
        'image',
        'nama_bank',
        'no_rekening',
        'atas_nama'
    ];

    public function locationOpening()
    {
        return $this->hasMany(LocationOpening::class, 'id_location');
    }

    public function locationStudio()
    {
        return $this->hasMany(LocationStudio::class, 'id_location');
    }

    public function orderPaket()
    {
        return $this->hasMany(OrderPaket::class, 'id_location');
    }

    public function paket()
    {
        return $this->hasMany(PaketLocation::class, 'id_location');
    }
}
