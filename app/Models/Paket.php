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
        'kategori_kelas',
        'eligible',
        'promote_code',
        'type_kelas',
        'location',
        'diskon'
    ];

    protected $appends = [
        'harga_formatted', 'harga_diskon', 'kategori_formatted', 'location_formatted', 'type_kelas_formatted'
    ];

    public function getTypeKelasFormattedAttribute(){
        if($this->type_kelas == 1){
            return 'Online';
        }elseif($this->type_kelas == 2){
            return 'Onsite';
        }
    }

    public function getLocationFormattedAttribute(){
        if($this->location == 0){
            return 'All Location';
        }else{
            $location = Location::find($this->location);
            return $location->nama;
        }
    }

    public function getKategoriFormattedAttribute(){
        switch ($this->kategori_kelas) {
            case 1:
                return 'Group Class';
                break;
            case 2:
                return 'Private Class';
                break;
            case 3:
                return 'Private Duet';
                break;
            case 4:
                return 'Private Group';
                break;
            default:
                return 'Not Know';
                break;
        }
    }

    public function getHargaDiskonAttribute(){
        return $this->harga - ($this->harga * $this->diskon / 100);
    }

    public function getHargaFormattedAttribute(){
        return 'Rp.' . number_format($this->harga,0,',','.');
    }
}
