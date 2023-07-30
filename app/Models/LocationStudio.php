<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationStudio extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'location_studios';
    protected $fillable = [
        'id_location',
        'nama',
        'level',
        'equipment',
        'image',
        'max_peserta'
    ];

    protected $appends = [
        'nama_location'
    ];

    public function getNamaLocationAttribute()
    {
        $location = Location::find($this->id_location);
        return $this->nama . ' ' . $location->nama;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location');
    }
}
