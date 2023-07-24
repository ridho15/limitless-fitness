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
        'id_coa',
        'nama',
        'jumlah_uang',
        'tipe',
        'struk',
        'tanggal'
    ];

    protected $appends = [
        'jumlah_uang_formatted', 'tipe_formatted'
    ];

    public function getJumlahUangFormattedAttribute(){
        return 'Rp.' . number_format($this->jumlah_uang,0,',','.');
    }

    public function getTipeFormattedAttribute(){
        if($this->tipe == 1){
            return 'Pemasukkan';
        }else if($this->tipe == 0){
            return 'Pengeluaran';
        }
    }

    public function coa(){
        return $this->belongsTo(Coa::class, 'id_coa');
    }
}
