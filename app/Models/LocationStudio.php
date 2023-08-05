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
        'nama_location', 'level_formatted'
    ];

    public function getLevelFormattedAttribute()
    {
        if ($this->level == 1) {
            return 'Intermeidate';
        } elseif ($this->level == 2) {
            return 'Beginer';
        } elseif ($this->level == 3) {
            return 'Advanced';
        }else{
            return 'Open to all';
        }
    }

    public function getNamaLocationAttribute()
    {
        $location = Location::find($this->id_location);
        return $this->nama . ' ' . $location->nama;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location')->withDefault();
    }

    public function kelasActivity()
    {
        return $this->hasMany(KelasActivity::class, 'id_location_studio');
    }

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_location_studio');
    }
}
