<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasInstruktur extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_instrukturs';
    protected $fillable = [
        'id_kelas',
        'id_user'
    ];

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas')->withDefault();
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user')->withDefault();
    }
}
