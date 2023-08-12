<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurnal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'jurnals';
    protected $fillable = [
        'nama',
        'jumlah_uang',
        'tipe',
        'struk',
        'tanggal',
        'id_parent',
        'id_order_paket',
        'id_user'
    ];

    protected $appends = [
        'jumlah_uang_formatted', 'tipe_formatted'
    ];

    public function getJumlahUangFormattedAttribute()
    {
        return 'Rp.' . number_format($this->jumlah_uang, 0, ',', '.');
    }

    public function getTipeFormattedAttribute()
    {
        if ($this->tipe == 1) {
            return 'Pemasukkan';
        } else if ($this->tipe == 0) {
            return 'Pengeluaran';
        }
    }

    public function parent()
    {
        return $this->belongsTo(Jurnal::class, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(Jurnal::class, 'id_parent');
    }

    public function orderPaket(){
        return $this->belongsTo(OrderPaket::class, 'id_order_paket');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
