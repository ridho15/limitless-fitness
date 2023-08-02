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

    protected $appends = [
        'hari_formatted'
    ];

    public function getHariFormattedAttribute(){
        switch ($this->hari) {
            case 1:
                return 'Monday';
                break;
            case 2:
                return 'Tuesday';
                break;
            case 3:
                return 'Wednesday';
                break;
            case 4:
                return 'Thurday';
                break;
            case 5:
                return 'Friday';
                break;
            case 6:
                return 'Saturday';
                break;
            case 7:
                return 'Sunday';
                break;
            default:
                'not know';
                break;
            }
    }

    public function location(){
        return $this->belongsTo(Location::class, 'id_location');
    }
}
