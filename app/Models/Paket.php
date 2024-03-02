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
        'diskon',
        'id_kelas',
        'is_many_times',
        'is_show_public'
    ];

    protected $appends = [
        'harga_formatted', 'harga_diskon', 'is_many_times_formatted'
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

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withDefault();
    }

    public function paketLocation()
    {
        return $this->hasMany(PaketLocation::class, 'id_paket');
    }
}
