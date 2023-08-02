<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasPaket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_pakets';
    protected $fillable = [
        'id_kelas',
        'id_paket'
    ];

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function paket(){
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
