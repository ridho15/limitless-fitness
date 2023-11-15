<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketLocation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'paket_locations';
    protected $fillable = [
        'id_paket',
        'id_location'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location');
    }
}
