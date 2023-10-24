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
        'equipment',
        'image',
        'no_hp'
    ];

    protected $appends = [
        'nama_location',
    ];

    public function getNamaLocationAttribute()
    {
        $location = Location::find($this->id_location);
        if ($location) {
            return $this->nama . ' ' . $location->nama;
        }

        return null;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location')->withDefault();
    }

    public function kelasActivity()
    {
        return $this->hasMany(KelasActivity::class, 'id_location_studio');
    }
}
