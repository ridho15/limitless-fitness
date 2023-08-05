<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ruangans';
    protected $fillable = [
        'id_location_studio',
        'nama_ruangan',
        'max_peserta'
    ];

    public function locationStudio(){
        return $this->belongsTo(LocationStudio::class, 'id_location_studio');
    }
}
