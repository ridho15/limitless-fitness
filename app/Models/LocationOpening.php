<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationOpening extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'location_openings';
    protected $fillable = [
        'hari',
        'id_location',
        'jam_open',
        'jam_close'
    ];

    public function location(){
        return $this->belongsTo(Location::class, 'id_location');
    }
}
